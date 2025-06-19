<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Sprint;
use App\Models\Task;
use App\Models\User;
use App\Services\TaskAssignmentNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SprintTaskController extends Controller
{
    public function store(Request $request, Project $project, Sprint $sprint)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,medium,high',
            'story_points' => 'nullable|integer|min:0',
            'status' => 'required|in:todo,in_progress,review,done',
            'assignee_id' => 'nullable|exists:users,id',
        ]);

        $task = $sprint->tasks()->create(array_merge($validated, [
            'project_id' => $project->id,
            'reporter_id' => Auth::id(), // L'utilisateur connecté est le rapporteur par défaut
        ]));

        // Envoyer une notification si la tâche est assignée
        if ($task->assignee_id) {
            $assignedUser = User::find($task->assignee_id);
            if ($assignedUser) {
                TaskAssignmentNotificationService::sendAssignmentNotification(
                    $task, 
                    $assignedUser, 
                    Auth::user()
                );
            }
        }

        return back()->with('success', 'Tâche ajoutée au sprint avec succès !');
    }

    public function updateStatus(Request $request, Project $project, Sprint $sprint, Task $task)
    {
        $request->validate([
            'status' => 'required|in:todo,in_progress,review,done',
        ]);

        $task->update(['status' => $request->status]);

        return response()->json(['message' => 'Statut mis à jour avec succès']);
    }

    public function assignTask(Request $request, Project $project, Sprint $sprint, Task $task)
    {
        $request->validate([
            'assignee_id' => 'nullable|exists:users,id',
        ]);

        $oldAssigneeId = $task->assignee_id;
        $task->update(['assignee_id' => $request->assignee_id]);

        // Envoyer une notification si la tâche est assignée à quelqu'un
        if ($request->assignee_id) {
            $assignedUser = User::find($request->assignee_id);
            if ($assignedUser) {
                // Si c'est une nouvelle assignation ou un changement d'assignation
                if ($oldAssigneeId !== $request->assignee_id) {
                    TaskAssignmentNotificationService::sendReassignmentNotification(
                        $task, 
                        $assignedUser, 
                        Auth::user()
                    );
                }
            }
        }

        return response()->json(['message' => 'Tâche assignée avec succès']);
    }
}