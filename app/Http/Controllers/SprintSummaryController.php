<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Sprint;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;

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
            $query->with(['assignee', 'reporter']);
        }]);

        // Calculer les métriques de base
        $totalTasks = $sprint->tasks->count();
        $completedTasks = $sprint->tasks->where('status', 'done')->count();
        $inProgressTasks = $sprint->tasks->whereIn('status', ['in_progress', 'review'])->count();
        $todoTasks = $sprint->tasks->where('status', 'todo')->count();
        $blockedTasks = $sprint->tasks->where('is_blocked', true)->count();
        
        // Calculer les points d'histoire
        $totalStoryPoints = $sprint->tasks->sum('story_points');
        $completedStoryPoints = $sprint->tasks->where('status', 'done')->sum('story_points');
        $inProgressStoryPoints = $sprint->tasks->whereIn('status', ['in_progress', 'review'])->sum('story_points');
        $todoStoryPoints = $sprint->tasks->where('status', 'todo')->sum('story_points');
        
        // Calculer les métriques de temps
        $startDate = Carbon::parse($sprint->start_date);
        $endDate = Carbon::parse($sprint->end_date);
        $now = Carbon::now();
        
        $totalDays = $startDate->diffInDays($endDate) + 1;
        $daysElapsed = max(0, $startDate->diffInDays($now));
        $daysRemaining = max(0, $now->diffInDays($endDate));
        
        // Calculer la progression temporelle
        $timeProgress = min(100, ($daysElapsed / $totalDays) * 100);
        $workProgress = $totalStoryPoints > 0 ? ($completedStoryPoints / $totalStoryPoints) * 100 : 0;
        
        // Calculer la vélocité
        $velocityPerDay = $daysElapsed > 0 ? $completedStoryPoints / $daysElapsed : 0;
        $projectedVelocity = $velocityPerDay * $totalDays;
        $velocityEfficiency = $totalStoryPoints > 0 ? ($projectedVelocity / $totalStoryPoints) * 100 : 0;
        
        // Préparer les données pour le burndown chart
        $burndownData = $this->generateBurndownData($sprint, $startDate, $endDate, $totalStoryPoints);
        
        // Calculer les métriques par assigné
        $assigneeMetrics = $this->calculateAssigneeMetrics($sprint);
        
        // Calculer les métriques de qualité
        $qualityMetrics = $this->calculateQualityMetrics($sprint);
        
        // Préparer les données pour le graphique circulaire
        $chartData = [
            'labels' => ['Terminées', 'En cours', 'À faire', 'Bloquées'],
            'data' => [
                $completedTasks,
                $inProgressTasks,
                $todoTasks,
                $blockedTasks
            ],
            'colors' => ['#10B981', '#F59E0B', '#3B82F6', '#EF4444']
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
            'inProgressStoryPoints' => $inProgressStoryPoints,
            'todoStoryPoints' => $todoStoryPoints,
            'totalDays' => $totalDays,
            'daysElapsed' => $daysElapsed,
            'daysRemaining' => $daysRemaining,
            'timeProgress' => $timeProgress,
            'workProgress' => $workProgress,
            'velocityPerDay' => $velocityPerDay,
            'projectedVelocity' => $projectedVelocity,
            'velocityEfficiency' => $velocityEfficiency,
            'burndownData' => $burndownData,
            'assigneeMetrics' => $assigneeMetrics,
            'qualityMetrics' => $qualityMetrics,
            'chartData' => $chartData
        ]);
    }

    /**
     * Fournit les données JSON pour les mises à jour dynamiques
     *
     * @param Project $project
     * @param Sprint $sprint
     * @return JsonResponse
     */
    public function getData(Project $project, Sprint $sprint): JsonResponse
    {
        // Vérifier que le sprint appartient bien au projet
        if ($sprint->project_id !== $project->id) {
            abort(404);
        }

        // Charger les relations nécessaires
        $sprint->load(['tasks' => function ($query) {
            $query->with(['assignee', 'reporter']);
        }]);

        // Calculer les métriques
        $totalTasks = $sprint->tasks->count();
        $completedTasks = $sprint->tasks->where('status', 'done')->count();
        $inProgressTasks = $sprint->tasks->whereIn('status', ['in_progress', 'review'])->count();
        $todoTasks = $sprint->tasks->where('status', 'todo')->count();
        $blockedTasks = $sprint->tasks->where('is_blocked', true)->count();
        
        $totalStoryPoints = $sprint->tasks->sum('story_points');
        $completedStoryPoints = $sprint->tasks->where('status', 'done')->sum('story_points');
        
        $startDate = Carbon::parse($sprint->start_date);
        $endDate = Carbon::parse($sprint->end_date);
        $now = Carbon::now();
        
        $totalDays = $startDate->diffInDays($endDate) + 1;
        $daysElapsed = max(0, $startDate->diffInDays($now));
        $daysRemaining = max(0, $now->diffInDays($endDate));
        
        $timeProgress = min(100, ($daysElapsed / $totalDays) * 100);
        $workProgress = $totalStoryPoints > 0 ? ($completedStoryPoints / $totalStoryPoints) * 100 : 0;
        
        $velocityPerDay = $daysElapsed > 0 ? $completedStoryPoints / $daysElapsed : 0;
        $projectedVelocity = $velocityPerDay * $totalDays;
        $velocityEfficiency = $totalStoryPoints > 0 ? ($projectedVelocity / $totalStoryPoints) * 100 : 0;
        
        $burndownData = $this->generateBurndownData($sprint, $startDate, $endDate, $totalStoryPoints);
        $qualityMetrics = $this->calculateQualityMetrics($sprint);

        // Générer le HTML pour les listes de tâches
        $completedTasksHtml = view('sprints.partials.completed-tasks', ['tasks' => $sprint->tasks->where('status', 'done')])->render();
        $incompleteTasksHtml = view('sprints.partials.incomplete-tasks', ['tasks' => $sprint->tasks->where('status', '!=', 'done')])->render();

        return response()->json([
            'totalTasks' => $totalTasks,
            'completedTasks' => $completedTasks,
            'inProgressTasks' => $inProgressTasks,
            'todoTasks' => $todoTasks,
            'blockedTasks' => $blockedTasks,
            'totalStoryPoints' => $totalStoryPoints,
            'completedStoryPoints' => $completedStoryPoints,
            'daysRemaining' => $daysRemaining,
            'timeProgress' => $timeProgress,
            'workProgress' => $workProgress,
            'velocityPerDay' => $velocityPerDay,
            'projectedVelocity' => $projectedVelocity,
            'velocityEfficiency' => $velocityEfficiency,
            'progressPercentage' => $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0,
            'storyPointsPercentage' => $totalStoryPoints > 0 ? round(($completedStoryPoints / $totalStoryPoints) * 100) : 0,
            'burndownData' => $burndownData,
            'qualityMetrics' => $qualityMetrics,
            'completedTasksHtml' => $completedTasksHtml,
            'incompleteTasksHtml' => $incompleteTasksHtml,
            'lastUpdated' => now()->format('H:i:s')
        ]);
    }

    /**
     * Génère les données pour le burndown chart
     */
    private function generateBurndownData(Sprint $sprint, Carbon $startDate, Carbon $endDate, int $totalStoryPoints): array
    {
        $totalDays = $startDate->diffInDays($endDate) + 1;
        $now = Carbon::now();
        
        // Si pas de story points, utiliser le nombre de tâches
        if ($totalStoryPoints === 0) {
            $totalStoryPoints = $sprint->tasks->count();
        }
        
        // Si toujours pas de données, créer des données d'exemple
        if ($totalStoryPoints === 0) {
            $totalStoryPoints = 10; // Valeur par défaut pour l'affichage
        }
        
        $idealBurndown = [];
        $actualBurndown = [];
        $labels = [];
        
        for ($i = 0; $i <= $totalDays; $i++) {
            $currentDate = $startDate->copy()->addDays($i);
            $labels[] = $currentDate->format('d/m');
            
            // Burndown idéal
            $idealBurndown[] = max(0, $totalStoryPoints - (($totalStoryPoints / $totalDays) * $i));
            
            // Burndown réel
            if ($currentDate <= $now) {
                $completedAtDate = $sprint->tasks()
                    ->where('status', 'done')
                    ->where('updated_at', '<=', $currentDate->endOfDay())
                    ->sum('story_points');
                
                // Si pas de story points, compter les tâches
                if ($completedAtDate === 0) {
                    $completedAtDate = $sprint->tasks()
                        ->where('status', 'done')
                        ->where('updated_at', '<=', $currentDate->endOfDay())
                        ->count();
                }
                
                $actualBurndown[] = max(0, $totalStoryPoints - $completedAtDate);
            } else {
                $actualBurndown[] = null;
            }
        }
        
        // S'assurer qu'il y a au moins 2 points de données
        if (count($labels) < 2) {
            $labels = ['Début', 'Fin'];
            $idealBurndown = [$totalStoryPoints, 0];
            $actualBurndown = [$totalStoryPoints, 0];
        }
        
        return [
            'labels' => $labels,
            'ideal' => $idealBurndown,
            'actual' => $actualBurndown,
            'totalStoryPoints' => $totalStoryPoints,
            'totalDays' => $totalDays
        ];
    }

    /**
     * Calcule les métriques par assigné
     */
    private function calculateAssigneeMetrics(Sprint $sprint): array
    {
        $assigneeMetrics = [];
        
        foreach ($sprint->tasks as $task) {
            if ($task->assignee) {
                $assigneeId = $task->assignee->id;
                
                if (!isset($assigneeMetrics[$assigneeId])) {
                    $assigneeMetrics[$assigneeId] = [
                        'name' => $task->assignee->name,
                        'total_tasks' => 0,
                        'completed_tasks' => 0,
                        'total_points' => 0,
                        'completed_points' => 0,
                        'avatar' => substr($task->assignee->name, 0, 1)
                    ];
                }
                
                $assigneeMetrics[$assigneeId]['total_tasks']++;
                $assigneeMetrics[$assigneeId]['total_points'] += $task->story_points ?? 0;
                
                if ($task->status === 'done') {
                    $assigneeMetrics[$assigneeId]['completed_tasks']++;
                    $assigneeMetrics[$assigneeId]['completed_points'] += $task->story_points ?? 0;
                }
            }
        }
        
        // Calculer les pourcentages
        foreach ($assigneeMetrics as &$metrics) {
            $metrics['completion_rate'] = $metrics['total_tasks'] > 0 
                ? round(($metrics['completed_tasks'] / $metrics['total_tasks']) * 100) 
                : 0;
            $metrics['points_completion_rate'] = $metrics['total_points'] > 0 
                ? round(($metrics['completed_points'] / $metrics['total_points']) * 100) 
                : 0;
        }
        
        return array_values($assigneeMetrics);
    }

    /**
     * Calcule les métriques de qualité
     */
    private function calculateQualityMetrics(Sprint $sprint): array
    {
        $totalTasks = $sprint->tasks->count();
        $completedTasks = $sprint->tasks->where('status', 'done')->count();
        $blockedTasks = $sprint->tasks->where('is_blocked', true)->count();
        
        // Calculer le taux de blocage
        $blockageRate = $totalTasks > 0 ? ($blockedTasks / $totalTasks) * 100 : 0;
        
        // Calculer l'efficacité (tâches complétées vs temps écoulé)
        $startDate = Carbon::parse($sprint->start_date);
        $endDate = Carbon::parse($sprint->end_date);
        $now = Carbon::now();
        
        $totalDays = $startDate->diffInDays($endDate) + 1;
        $daysElapsed = max(0, $startDate->diffInDays($now));
        
        $efficiency = $daysElapsed > 0 ? ($completedTasks / $daysElapsed) : 0;
        
        return [
            'blockage_rate' => round($blockageRate, 1),
            'efficiency' => round($efficiency, 2),
            'total_tasks' => $totalTasks,
            'completed_tasks' => $completedTasks,
            'blocked_tasks' => $blockedTasks
        ];
    }
}
