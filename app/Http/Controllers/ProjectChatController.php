<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProjectChatController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Project $project)
    {
        if (!Gate::allows('view', $project)) {
            abort(403, 'Non autorisé.');
        }

        $messages = $project->messages()
            ->with(['user', 'reads'])
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        // Marquer les messages comme lus
        $messages->each(function ($message) {
            if (!$message->isReadBy(Auth::id())) {
                $message->markAsRead();
            }
        });

        // Si c'est une requête AJAX avec last_id, retourner seulement les nouveaux messages
        if (request()->ajax() && request()->has('last_id')) {
            $lastId = request()->get('last_id');
            $newMessages = $project->messages()
                ->with(['user', 'reads'])
                ->where('id', '>', $lastId)
                ->orderBy('created_at', 'asc')
                ->get();

            return response()->json([
                'messages' => $newMessages,
                'success' => true
            ]);
        }

        return view('projects.chat.index', compact('project', 'messages'));
    }

    public function store(Request $request, Project $project)
    {
        $request->validate([
            'content' => 'nullable|string|max:2000',
            'file' => 'nullable|file|max:5120', // 5MB max
            'reply_to_message_id' => 'nullable|exists:project_messages,id',
            'reply_to_user' => 'nullable|string|max:255',
            'reply_to_content' => 'nullable|string|max:1000',
        ]);

        $messageData = [
            'user_id' => Auth::id(),
            'content' => $request->content ?? '',
        ];

        // Gestion des réponses aux messages
        if ($request->filled('reply_to_message_id')) {
            $messageData['reply_to_message_id'] = $request->reply_to_message_id;
            $messageData['reply_to_user'] = $request->reply_to_user;
            $messageData['reply_to_content'] = $request->reply_to_content;
        }

        // Gestion de l'upload de fichier
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileType = $file->getMimeType();
            
            // Vérifier le type de fichier autorisé
            $allowedTypes = [
                'image/jpeg',
                'image/jpg', 
                'image/png',
                'image/gif',
                'application/pdf'
            ];
            
            if (!in_array($fileType, $allowedTypes)) {
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'error' => 'Type de fichier non autorisé. Seuls les images (JPG, PNG, GIF) et PDF sont acceptés.'
                    ], 400);
                }
                return redirect()->back()->with('error', 'Type de fichier non autorisé');
            }

            // Générer un nom unique pour le fichier
            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $filePath = 'chat-files/' . $project->id . '/' . $filename;
            
            // Stocker le fichier
            $file->storeAs('public/' . dirname($filePath), $filename);
            
            $messageData = array_merge($messageData, [
                'filename' => $filename,
                'original_name' => $file->getClientOriginalName(),
                'file_path' => $filePath,
                'file_size' => $file->getSize(),
                'file_type' => $fileType
            ]);
        }

        $message = $project->messages()->create($messageData);
        $message->load('user');

        // Ajouter les informations de réponse pour l'affichage
        if ($message->reply_to_message_id) {
            $message->reply_to = true;
            $message->reply_to_user = $message->reply_to_user;
            $message->reply_to_content = $message->reply_to_content;
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message
            ]);
        }

        return redirect()->back()->with('success', 'Message envoyé !');
    }

    public function update(Request $request, Project $project, ProjectMessage $message)
    {
        // Vérifier que l'utilisateur est propriétaire du message
        if ($message->user_id !== Auth::id()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'error' => 'Vous ne pouvez pas modifier ce message'
                ], 403);
            }
            return redirect()->back()->with('error', 'Vous ne pouvez pas modifier ce message');
        }

        $request->validate([
            'content' => 'required|string|max:2000',
        ]);

        $message->update([
            'content' => $request->content,
            'is_edited' => true,
        ]);

        $message->load('user');

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message
            ]);
        }

        return redirect()->back()->with('success', 'Message modifié !');
    }

    public function destroy(Request $request, Project $project, ProjectMessage $message)
    {
        // Vérifier que l'utilisateur est propriétaire du message
        if ($message->user_id !== Auth::id()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'error' => 'Vous ne pouvez pas supprimer ce message'
                ], 403);
            }
            return redirect()->back()->with('error', 'Vous ne pouvez pas supprimer ce message');
        }

        // Supprimer le fichier associé s'il existe
        if ($message->has_file) {
            $message->deleteFile();
        }

        $messageId = $message->id;
        $message->delete();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message_id' => $messageId
            ]);
        }

        return redirect()->back()->with('success', 'Message supprimé !');
    }

    public function markAsRead(Request $request, Project $project)
    {
        $messageIds = $request->input('message_ids', []);
        
        ProjectMessage::whereIn('id', $messageIds)
            ->where('project_id', $project->id)
            ->get()
            ->each(function ($message) {
                $message->markAsRead();
            });

        return response()->json(['success' => true]);
    }

    public function downloadFile(Project $project, ProjectMessage $message)
    {
        // Vérifier que l'utilisateur a accès au projet
        if (!Gate::allows('view', $project)) {
            abort(403, 'Non autorisé.');
        }

        // Vérifier que le message appartient au projet
        if ($message->project_id !== $project->id) {
            abort(404, 'Message non trouvé.');
        }

        // Vérifier que le message a un fichier
        if (!$message->has_file) {
            abort(404, 'Aucun fichier associé à ce message.');
        }

        // Vérifier que le fichier existe
        if (!Storage::exists($message->file_path)) {
            abort(404, 'Fichier non trouvé.');
        }

        // Marquer le message comme lu
        $message->markAsRead();

        // Retourner le fichier pour téléchargement
        return Storage::download($message->file_path, $message->original_name);
    }
} 