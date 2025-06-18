<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Sprint;
use App\Models\Task;
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

        $sprint->tasks()->create(array_merge($validated, [
            'project_id' => $project->id,
            'reporter_id' => Auth::id(), // L'utilisateur connecté est le rapporteur par défaut
        ]));

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

        $task->update(['assignee_id' => $request->assignee_id]);

        return response()->json(['message' => 'Tâche assignée avec succès']);
    }
}