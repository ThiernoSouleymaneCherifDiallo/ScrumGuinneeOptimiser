<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class SummaryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(Project $project)
    {
        try {
            // Vérifier que l'utilisateur a accès au projet
            if ($project->owner_id !== Auth::id() && !$project->members->contains(Auth::id())) {
                abort(403, 'Vous n\'avez pas accès à ce projet.');
            }

            // Initialiser les statistiques par défaut
            $taskStats = [
                'total' => 0,
                'to_do' => 0,
                'in_progress' => 0,
                'done' => 0,
            ];

            // Charger les tâches du projet pour les 7 derniers jours
            $tasks = $project->tasks()
                ->whereBetween('created_at', [now()->subDays(7), now()])
                ->get();

            // Vérifier si les tâches sont chargées
            if (!$tasks->isEmpty()) {
                // Calculer les statistiques des tâches
                $taskStats = [
                    'total' => $tasks->count(),
                    'to_do' => $tasks->where('status', 'open')->count(),
                    'in_progress' => $tasks->where('status', 'in_progress')->count(),
                    'done' => $tasks->where('status', 'done')->count(),
                ];
            } else {
                \Log::info('Aucune tâche trouvée pour le projet ID: ' . $project->id);
            }

            // Calculer les statistiques des tâches assignées
            $assignedStats = $tasks->groupBy('assignee_id')->map(function ($group) use ($taskStats) {
                $user = $group->first()->assignee ?? null;
                return [
                    'name' => $user ? $user->name : 'Non assignée',
                    'count' => $group->count(),
                    'percentage' => $taskStats['total'] > 0 ? round(($group->count() / $taskStats['total']) * 100) : 0,
                ];
            })->values()->all();

            // Charger les épics (si disponibles)
            $epics = $project->epics ?? collect();

            // Passer toutes les variables à la vue
            return view('dashboard.summary', compact('project', 'taskStats', 'assignedStats', 'epics'));
        } catch (\Exception $e) {
            \Log::error('Erreur dans SummaryController@show: ' . $e->getMessage());
            dd('Erreur capturée : ' . $e->getMessage()); // Afficher l'erreur pour débogage
        }
    }
}