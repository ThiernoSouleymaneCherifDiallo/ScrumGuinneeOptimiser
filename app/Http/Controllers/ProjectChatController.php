<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

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
            'content' => 'required|string|max:2000',
        ]);

        $message = $project->messages()->create([
            'user_id' => Auth::id(),
            'content' => $request->content,
        ]);

        $message->load('user');

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
} 