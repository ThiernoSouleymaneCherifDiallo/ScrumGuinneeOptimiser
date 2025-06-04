<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create(Project $project)
    {
        return view('tasks.create', compact('project'));
    }

    public function store(Request $request, Project $project)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,medium,high,urgent',
            'type' => 'required|in:bug,feature,task,improvement',
            'assignee_id' => 'nullable|exists:users,id',
            'due_date' => 'nullable|date',
        ]);

        $validated['project_id'] = $project->id;
        $validated['reporter_id'] = Auth::id();
        $validated['status'] = 'open';

        $task = Task::create($validated);

        return redirect()->route('projects.show', $project)
                        ->with('success', 'Tâche créée avec succès.');
    }
}
