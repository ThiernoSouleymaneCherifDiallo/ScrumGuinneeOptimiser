<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Sprint;
use App\Models\Task;
use Illuminate\Http\Request;

class BacklogController extends Controller
{
    public function index(Project $project)
    {
        // Tâches non assignées à un sprint (backlog)
        $backlogTasks = $project->tasks()->whereNull('sprint_id')->get();
        
        // Sprints actifs du projet avec leurs tâches
        $sprints = $project->sprints()
            ->with('tasks')
            ->orderBy('start_date')
            ->get();
            
        return view('backlog.index', compact('project', 'backlogTasks', 'sprints'));
    }
}
