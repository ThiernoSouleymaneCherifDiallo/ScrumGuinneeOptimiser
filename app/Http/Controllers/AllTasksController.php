<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AllTasksController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request, Project $project)
    {
        $user = Auth::user();
        
        // Vérifier que l'utilisateur a accès au projet
        if (!$project->members()->where('user_id', $user->id)->exists() && $project->owner_id !== $user->id) {
            abort(403, 'Vous n\'avez pas accès à ce projet.');
        }
        
        // Construire la requête de base pour ce projet
        $query = Task::where('project_id', $project->id)
                    ->with(['assignee', 'sprint', 'reporter']);
        
        // Filtres
        $filters = $request->only(['assignee', 'status', 'priority', 'type', 'search', 'sprint']);
        
        // Filtre par assigné
        if (!empty($filters['assignee'])) {
            if ($filters['assignee'] === 'me') {
                $query->where('assignee_id', $user->id);
            } elseif ($filters['assignee'] === 'unassigned') {
                $query->whereNull('assignee_id');
            } else {
                $query->where('assignee_id', $filters['assignee']);
            }
        }
        
        // Filtre par statut
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        
        // Filtre par priorité
        if (!empty($filters['priority'])) {
            $query->where('priority', $filters['priority']);
        }
        
        // Filtre par type
        if (!empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }
        
        // Filtre par sprint
        if (!empty($filters['sprint'])) {
            if ($filters['sprint'] === 'backlog') {
                $query->whereNull('sprint_id');
            } else {
                $query->where('sprint_id', $filters['sprint']);
            }
        }
        
        // Recherche par titre ou description
        if (!empty($filters['search'])) {
            $query->where(function($q) use ($filters) {
                $q->where('title', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('description', 'like', '%' . $filters['search'] . '%');
            });
        }
        
        // Tri
        $sortBy = $request->get('sort', 'created_at');
        $sortOrder = $request->get('order', 'desc');
        $query->orderBy($sortBy, $sortOrder);
        
        // Pagination
        $tasks = $query->paginate(20)->withQueryString();
        
        // Statistiques pour ce projet
        $stats = [
            'total' => Task::where('project_id', $project->id)->count(),
            'my_tasks' => Task::where('project_id', $project->id)->where('assignee_id', $user->id)->count(),
            'urgent' => Task::where('project_id', $project->id)->where('priority', 'urgent')->count(),
            'overdue' => Task::where('project_id', $project->id)->where('due_date', '<', now())->count(),
            'backlog' => Task::where('project_id', $project->id)->whereNull('sprint_id')->count(),
        ];
        
        // Sprints du projet
        $sprints = $project->sprints()->orderBy('start_date')->get();
        
        return view('tasks.all', compact('tasks', 'project', 'sprints', 'filters', 'stats'));
    }
}
