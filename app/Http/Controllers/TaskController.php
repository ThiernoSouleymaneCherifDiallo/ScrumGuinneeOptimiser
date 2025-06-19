<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use App\Models\User;
use App\Services\TaskAssignmentNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function __construct()
    {
        // parent::__construct($param);
        $this->middleware('auth');
    }

    public function index(Project $project){
        $tasks = $project->tasks()->latest()->get();
        return view('tasks.index', compact('project' ,'tasks'));
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

        return redirect()->route('projects.show', $project)
                        ->with('success', 'Tâche créée avec succès.');
    }

    public function show(Project $project, Task $task)
    {
        // Vérifier que la tâche appartient au projet
        if ($task->project_id !== $project->id) {
            abort(404, 'Tâche non trouvée.');
        }

        // Charger les relations nécessaires
        $task->load(['assignee', 'reporter', 'sprint', 'comments.user', 'comments.replies.user']);

        return view('tasks.show', compact('project', 'task'));
    }
}
