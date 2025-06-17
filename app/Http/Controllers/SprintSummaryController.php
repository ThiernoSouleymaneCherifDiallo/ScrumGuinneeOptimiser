<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Sprint;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SprintSummaryController extends Controller
{
    /**
     * Affiche le résumé du sprint
     *
     * @param Project $project
     * @param Sprint $sprint
     * @return View
     */
    public function show(Project $project, Sprint $sprint): View
    {
        // Vérifier que le sprint appartient bien au projet
        if ($sprint->project_id !== $project->id) {
            abort(404);
        }

        // Charger les relations nécessaires
        $sprint->load(['tasks' => function ($query) {
            $query->with('assignee');
        }]);

        // Calculer les métriques
        $totalTasks = $sprint->tasks->count();
        $completedTasks = $sprint->tasks->where('status', 'done')->count();
        $inProgressTasks = $sprint->tasks->whereIn('status', ['in_progress', 'review'])->count();
        $todoTasks = $sprint->tasks->where('status', 'todo')->count();
        $blockedTasks = $sprint->tasks->where('is_blocked', true)->count();
        
        // Calculer les points d'histoire
        $totalStoryPoints = $sprint->tasks->sum('story_points');
        $completedStoryPoints = $sprint->tasks->where('status', 'done')->sum('story_points');
        
        // Préparer les données pour le graphique circulaire
        $chartData = [
            'labels' => ['Terminées', 'En cours', 'À faire'],
            'data' => [
                $completedTasks,
                $inProgressTasks,
                $todoTasks
            ],
            'colors' => ['#10B981', '#F59E0B', '#3B82F6']
        ];

        return view('sprints.summary', [
            'project' => $project,
            'sprint' => $sprint,
            'totalTasks' => $totalTasks,
            'completedTasks' => $completedTasks,
            'inProgressTasks' => $inProgressTasks,
            'todoTasks' => $todoTasks,
            'blockedTasks' => $blockedTasks,
            'totalStoryPoints' => $totalStoryPoints,
            'completedStoryPoints' => $completedStoryPoints,
            'chartData' => $chartData
        ]);
    }
}
