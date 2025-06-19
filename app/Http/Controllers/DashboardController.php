<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Task;

class DashboardController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();
        
        // Récupérer les tâches en cours de l'utilisateur
        $myTasks = Task::where('assignee_id', $user->id)
            ->whereIn('status', ['todo', 'in_progress', 'review'])
            ->with(['project', 'sprint'])
            ->orderBy('priority', 'desc')
            ->orderBy('due_date', 'asc')
            ->limit(10)
            ->get();
        
        // Récupérer toutes les tâches en cours des projets de l'utilisateur
        $allActiveTasks = Task::whereHas('project', function($query) use ($user) {
            $query->where('owner_id', $user->id)
                  ->orWhereHas('members', function($q) use ($user) {
                      $q->where('user_id', $user->id);
                  });
        })
        ->whereIn('status', ['todo', 'in_progress', 'review'])
        ->with(['project', 'assignee', 'sprint'])
        ->orderBy('priority', 'desc')
        ->orderBy('due_date', 'asc')
        ->limit(15)
        ->get();
        
        // Statistiques
        $stats = [
            'my_tasks_count' => Task::where('assignee_id', $user->id)->whereIn('status', ['todo', 'in_progress', 'review'])->count(),
            'urgent_tasks_count' => $allActiveTasks->where('priority', 'urgent')->count(),
            'overdue_tasks_count' => $allActiveTasks->where('due_date', '<', now())->count(),
            'total_active_tasks' => $allActiveTasks->count(),
        ];
        
        return view('dashboard', compact('myTasks', 'allActiveTasks', 'stats'));
    }
}
