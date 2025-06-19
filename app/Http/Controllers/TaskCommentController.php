<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskComment;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class TaskCommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Project $project, Task $task)
    {
        // Vérifier les permissions
        if (!Gate::allows('view', $project)) {
            abort(403, 'Non autorisé.');
        }

        $comments = $task->comments()->with(['user', 'replies.user'])->get();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'comments' => $comments
            ]);
        }

        return view('tasks.comments.index', compact('project', 'task', 'comments'));
    }

    public function store(Request $request, Project $project, Task $task)
    {
        // Vérifier les permissions
        if (!Gate::allows('view', $project)) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'error' => 'Non autorisé.'
                ], 403);
            }
            abort(403, 'Non autorisé.');
        }

        $request->validate([
            'content' => 'required|string|max:2000',
            'parent_id' => 'nullable|exists:task_comments,id'
        ]);

        $commentData = [
            'task_id' => $task->id,
            'user_id' => Auth::id(),
            'content' => $request->content,
        ];

        // Si c'est une réponse à un commentaire
        if ($request->filled('parent_id')) {
            $parentComment = TaskComment::find($request->parent_id);
            
            // Vérifier que le commentaire parent appartient à la même tâche
            if ($parentComment->task_id !== $task->id) {
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'error' => 'Commentaire parent invalide.'
                    ], 400);
                }
                return redirect()->back()->with('error', 'Commentaire parent invalide.');
            }

            $commentData['parent_id'] = $request->parent_id;
        }

        $comment = TaskComment::create($commentData);
        $comment->load(['user', 'replies.user']);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'comment' => $comment
            ]);
        }

        return redirect()->back()->with('success', 'Commentaire ajouté !');
    }

    public function update(Request $request, Project $project, Task $task, TaskComment $comment)
    {
        // Vérifier que l'utilisateur est propriétaire du commentaire
        if ($comment->user_id !== Auth::id()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'error' => 'Vous ne pouvez pas modifier ce commentaire'
                ], 403);
            }
            return redirect()->back()->with('error', 'Vous ne pouvez pas modifier ce commentaire');
        }

        // Vérifier que le commentaire appartient à la tâche
        if ($comment->task_id !== $task->id) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'error' => 'Commentaire invalide'
                ], 400);
            }
            return redirect()->back()->with('error', 'Commentaire invalide');
        }

        $request->validate([
            'content' => 'required|string|max:2000',
        ]);

        $comment->update([
            'content' => $request->content,
            'is_edited' => true,
            'edited_at' => now(),
        ]);

        $comment->load(['user', 'replies.user']);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'comment' => $comment
            ]);
        }

        return redirect()->back()->with('success', 'Commentaire modifié !');
    }

    public function destroy(Request $request, Project $project, Task $task, TaskComment $comment)
    {
        // Vérifier que l'utilisateur est propriétaire du commentaire
        if ($comment->user_id !== Auth::id()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'error' => 'Vous ne pouvez pas supprimer ce commentaire'
                ], 403);
            }
            return redirect()->back()->with('error', 'Vous ne pouvez pas supprimer ce commentaire');
        }

        // Vérifier que le commentaire appartient à la tâche
        if ($comment->task_id !== $task->id) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'error' => 'Commentaire invalide'
                ], 400);
            }
            return redirect()->back()->with('error', 'Commentaire invalide');
        }

        $commentId = $comment->id;
        $comment->delete(); // Les réponses seront supprimées en cascade

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'comment_id' => $commentId
            ]);
        }

        return redirect()->back()->with('success', 'Commentaire supprimé !');
    }
}
