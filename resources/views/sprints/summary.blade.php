@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-jira-dark text-white">
    <!-- Header du Sprint -->
    <div class="bg-jira-card border-b border-jira p-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="flex items-center space-x-3">
                    <div class="w-3 h-3 rounded-full animate-pulse {{ $sprint->status === 'active' ? 'bg-green-500' : ($sprint->status === 'completed' ? 'bg-blue-500' : 'bg-gray-500') }}"></div>
                    <h1 class="text-2xl font-bold text-white">{{ $sprint->name }}</h1>
                </div>
                <div class="text-jira-gray text-sm">
                    {{ $project->name }} • Sprint {{ $sprint->sprint_number }}
                </div>
            </div>
            
            <div class="flex items-center space-x-3">
                <div class="flex items-center space-x-2 text-sm text-jira-gray">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <span>{{ \Carbon\Carbon::parse($sprint->start_date)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($sprint->end_date)->format('d/m/Y') }}</span>
                </div>
                
                @if($sprint->status === 'active')
                    <button onclick="closeSprint()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                        Clôturer le Sprint
                    </button>
                @endif
                
                <!-- Bouton de rafraîchissement -->
                <button onclick="refreshData()" class="bg-gray-600 hover:bg-gray-700 text-white p-2 rounded-md transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div class="p-6 space-y-6">
        <!-- Métriques principales -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Progression -->
            <div class="bg-jira-card rounded-lg p-6 border border-jira hover:bg-jira-card-hover transition-all duration-300">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-white">Progression</h3>
                    <div id="progress-percentage" class="text-2xl font-bold text-green-400 transition-all duration-500">{{ $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0 }}%</div>
                </div>
                <div class="w-full bg-gray-700 rounded-full h-2 mb-2">
                    <div id="progress-bar" class="bg-green-500 h-2 rounded-full transition-all duration-500 ease-out" style="width: {{ $totalTasks > 0 ? ($completedTasks / $totalTasks) * 100 : 0 }}%"></div>
                </div>
                <div class="text-sm text-jira-gray">
                    <span id="completed-tasks">{{ $completedTasks }}</span> sur <span id="total-tasks">{{ $totalTasks }}</span> tâches terminées
                </div>
            </div>

            <!-- Points d'histoire -->
            <div class="bg-jira-card rounded-lg p-6 border border-jira hover:bg-jira-card-hover transition-all duration-300">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-white">Story Points</h3>
                    <div id="story-points-display" class="text-2xl font-bold text-blue-400 transition-all duration-500">{{ $completedStoryPoints }}/{{ $totalStoryPoints }}</div>
                </div>
                <div class="w-full bg-gray-700 rounded-full h-2 mb-2">
                    <div id="story-points-bar" class="bg-blue-500 h-2 rounded-full transition-all duration-500 ease-out" style="width: {{ $totalStoryPoints > 0 ? ($completedStoryPoints / $totalStoryPoints) * 100 : 0 }}%"></div>
                </div>
                <div class="text-sm text-jira-gray">
                    <span id="story-points-percentage">{{ $totalStoryPoints > 0 ? round(($completedStoryPoints / $totalStoryPoints) * 100) : 0 }}</span>% complétés
                </div>
            </div>

            <!-- Vélocité -->
            <div class="bg-jira-card rounded-lg p-6 border border-jira hover:bg-jira-card-hover transition-all duration-300">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-white">Vélocité</h3>
                    <div id="velocity-display" class="text-2xl font-bold text-purple-400 transition-all duration-500">{{ number_format($velocityPerDay, 1) }}</div>
                </div>
                <div class="text-sm text-jira-gray mb-2">Points par jour</div>
                <div class="text-xs text-jira-gray">
                    Projeté: <span id="projected-velocity">{{ round($projectedVelocity) }}</span> pts
                </div>
            </div>

            <!-- Temps restant -->
            <div class="bg-jira-card rounded-lg p-6 border border-jira hover:bg-jira-card-hover transition-all duration-300">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-white">Temps restant</h3>
                    <div id="days-remaining" class="text-2xl font-bold text-orange-400 transition-all duration-500">{{ $daysRemaining }}</div>
                </div>
                <div class="text-sm text-jira-gray mb-2">Jours restants</div>
                <div class="text-xs text-jira-gray">
                    <span id="time-progress">{{ round($timeProgress) }}</span>% du temps écoulé
                </div>
            </div>
        </div>

        <!-- Graphiques et métriques détaillées -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Burndown Chart -->
            <div class="lg:col-span-2 bg-jira-card rounded-lg p-6 border border-jira">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-white">Burndown Chart</h3>
                    <div class="flex space-x-2">
                        <button onclick="updateChart()" class="text-xs bg-blue-600 hover:bg-blue-700 px-2 py-1 rounded transition-colors">
                            Actualiser
                        </button>
                    </div>
                </div>
                <div class="h-64 flex items-center justify-center">
                    <canvas id="burndownChart" width="400" height="200"></canvas>
                </div>
            </div>

            <!-- Répartition par statut -->
            <div class="bg-jira-card rounded-lg p-6 border border-jira">
                <h3 class="text-lg font-semibold text-white mb-4">Répartition par statut</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <div class="w-3 h-3 rounded-full bg-green-500"></div>
                            <span class="text-sm text-white">Terminées</span>
                        </div>
                        <span id="completed-count" class="text-sm font-semibold text-green-400 transition-all duration-300">{{ $completedTasks }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
                            <span class="text-sm text-white">En cours</span>
                        </div>
                        <span id="in-progress-count" class="text-sm font-semibold text-yellow-400 transition-all duration-300">{{ $inProgressTasks }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <div class="w-3 h-3 rounded-full bg-blue-500"></div>
                            <span class="text-sm text-white">À faire</span>
                        </div>
                        <span id="todo-count" class="text-sm font-semibold text-blue-400 transition-all duration-300">{{ $todoTasks }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <div class="w-3 h-3 rounded-full bg-red-500"></div>
                            <span class="text-sm text-white">Bloquées</span>
                        </div>
                        <span id="blocked-count" class="text-sm font-semibold text-red-400 transition-all duration-300">{{ $blockedTasks }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Métriques par assigné -->
        @if(count($assigneeMetrics) > 0)
        <div class="bg-jira-card rounded-lg p-6 border border-jira">
            <h3 class="text-lg font-semibold text-white mb-4">Performance par membre</h3>
            <div id="assignee-metrics" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($assigneeMetrics as $assignee)
                <div class="bg-jira-dark rounded-lg p-4 border border-jira hover:bg-jira-card-hover transition-all duration-300">
                    <div class="flex items-center space-x-3 mb-3">
                        <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-sm font-semibold text-white">
                            {{ $assignee['avatar'] }}
                        </div>
                        <div>
                            <div class="text-sm font-medium text-white">{{ $assignee['name'] }}</div>
                            <div class="text-xs text-jira-gray">{{ $assignee['completed_tasks'] }}/{{ $assignee['total_tasks'] }} tâches</div>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <div class="flex justify-between text-xs">
                            <span class="text-jira-gray">Progression</span>
                            <span class="text-green-400 font-semibold">{{ $assignee['completion_rate'] }}%</span>
                        </div>
                        <div class="w-full bg-gray-700 rounded-full h-1">
                            <div class="bg-green-500 h-1 rounded-full transition-all duration-500" style="width: {{ $assignee['completion_rate'] }}%"></div>
                        </div>
                        <div class="flex justify-between text-xs">
                            <span class="text-jira-gray">Points</span>
                            <span class="text-blue-400 font-semibold">{{ $assignee['completed_points'] }}/{{ $assignee['total_points'] }}</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Métriques de qualité -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-jira-card rounded-lg p-6 border border-jira hover:bg-jira-card-hover transition-all duration-300">
                <h3 class="text-lg font-semibold text-white mb-4">Qualité</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-jira-gray">Taux de blocage</span>
                        <span id="blockage-rate" class="text-sm font-semibold {{ $qualityMetrics['blockage_rate'] > 10 ? 'text-red-400' : 'text-green-400' }} transition-all duration-300">
                            {{ $qualityMetrics['blockage_rate'] }}%
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-jira-gray">Efficacité</span>
                        <span id="efficiency-rate" class="text-sm font-semibold text-blue-400 transition-all duration-300">{{ $qualityMetrics['efficiency'] }}</span>
                    </div>
                </div>
            </div>

            <div class="bg-jira-card rounded-lg p-6 border border-jira hover:bg-jira-card-hover transition-all duration-300">
                <h3 class="text-lg font-semibold text-white mb-4">Progression temporelle</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-jira-gray">Temps écoulé</span>
                        <span id="time-elapsed" class="text-sm font-semibold text-orange-400 transition-all duration-300">{{ round($timeProgress) }}%</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-jira-gray">Travail accompli</span>
                        <span id="work-done" class="text-sm font-semibold text-green-400 transition-all duration-300">{{ round($workProgress) }}%</span>
                    </div>
                    <div class="w-full bg-gray-700 rounded-full h-2">
                        <div id="work-progress-bar" class="bg-green-500 h-2 rounded-full transition-all duration-500" style="width: {{ $workProgress }}%"></div>
                    </div>
                </div>
            </div>

            <div class="bg-jira-card rounded-lg p-6 border border-jira hover:bg-jira-card-hover transition-all duration-300">
                <h3 class="text-lg font-semibold text-white mb-4">Prévisions</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-jira-gray">Vélocité projetée</span>
                        <span id="velocity-projected" class="text-sm font-semibold text-purple-400 transition-all duration-300">{{ round($projectedVelocity) }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-jira-gray">Efficacité vélocité</span>
                        <span id="velocity-efficiency" class="text-sm font-semibold {{ $velocityEfficiency > 100 ? 'text-green-400' : 'text-yellow-400' }} transition-all duration-300">
                            {{ round($velocityEfficiency) }}%
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Objectifs du Sprint -->
        <div class="bg-jira-card rounded-lg p-6 border border-jira">
            <h3 class="text-lg font-semibold text-white mb-4">Objectifs du Sprint</h3>
            <div class="bg-jira-dark rounded-lg p-4 border border-jira">
                <p class="text-jira-gray">{{ $sprint->goal ?: 'Aucun objectif défini pour ce sprint.' }}</p>
            </div>
        </div>

        <!-- Tâches complétées vs non complétées -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Tâches complétées -->
            <div class="bg-jira-card rounded-lg p-6 border border-jira">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-white">Tâches complétées</h3>
                    <span class="text-sm text-green-400 font-semibold">{{ $completedTasks }}</span>
                </div>
                <div id="completed-tasks-list" class="space-y-3 max-h-64 overflow-y-auto">
                    @forelse($sprint->tasks->where('status', 'done') as $task)
                        <div class="bg-jira-dark rounded-lg p-3 border border-jira hover:bg-jira-card-hover transition-all duration-300">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-2">
                                    <div class="w-2 h-2 rounded-full bg-green-500"></div>
                                    <span class="text-sm text-white font-medium">{{ $task->title }}</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    @if($task->assignee)
                                        <div class="w-6 h-6 bg-blue-500 rounded-full flex items-center justify-center text-xs text-white">
                                            {{ substr($task->assignee->name, 0, 1) }}
                                        </div>
                                    @endif
                                    @if($task->story_points)
                                        <span class="text-xs bg-gray-600 text-white px-2 py-1 rounded">{{ $task->story_points }} pts</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-jira-gray text-sm py-4">
                            Aucune tâche complétée
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Tâches non complétées -->
            <div class="bg-jira-card rounded-lg p-6 border border-jira">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-white">Tâches non complétées</h3>
                    <span class="text-sm text-red-400 font-semibold">{{ $totalTasks - $completedTasks }}</span>
                </div>
                <div id="incomplete-tasks-list" class="space-y-3 max-h-64 overflow-y-auto">
                    @forelse($sprint->tasks->where('status', '!=', 'done') as $task)
                        <div class="bg-jira-dark rounded-lg p-3 border border-jira hover:bg-jira-card-hover transition-all duration-300">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-2">
                                    <div class="w-2 h-2 rounded-full 
                                        {{ $task->status === 'in_progress' ? 'bg-yellow-500' : 
                                           ($task->status === 'review' ? 'bg-purple-500' : 'bg-blue-500') }}"></div>
                                    <span class="text-sm text-white font-medium">{{ $task->title }}</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    @if($task->assignee)
                                        <div class="w-6 h-6 bg-blue-500 rounded-full flex items-center justify-center text-xs text-white">
                                            {{ substr($task->assignee->name, 0, 1) }}
                                        </div>
                                    @endif
                                    @if($task->story_points)
                                        <span class="text-xs bg-gray-600 text-white px-2 py-1 rounded">{{ $task->story_points }} pts</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-jira-gray text-sm py-4">
                            Toutes les tâches sont complétées !
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Actions rapides -->
        <div class="bg-jira-card rounded-lg p-6 border border-jira">
            <h3 class="text-lg font-semibold text-white mb-4">Actions rapides</h3>
            <div class="flex flex-wrap gap-3">
                <button onclick="createNextSprint()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                    Créer le prochain sprint
                </button>
                <button onclick="generatePDF()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                    Générer rapport PDF
                </button>
                <button onclick="exportData()" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                    Exporter données
                </button>
                <button onclick="scheduleRetrospective()" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                    Planifier rétrospective
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de notification -->
<div id="notification-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
    <div class="bg-jira-card rounded-lg p-6 max-w-md mx-4">
        <div class="flex items-center space-x-3 mb-4">
            <div id="notification-icon" class="w-8 h-8 rounded-full flex items-center justify-center">
                <!-- Icône sera ajoutée dynamiquement -->
            </div>
            <h3 id="notification-title" class="text-lg font-semibold text-white"></h3>
        </div>
        <p id="notification-message" class="text-jira-gray mb-4"></p>
        <div class="flex justify-end space-x-3">
            <button onclick="closeNotification()" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                Fermer
            </button>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .bg-jira-dark {
        background-color: #1D2125;
    }
    .bg-jira-card {
        background-color: #22272B;
        border: 1px solid #3C3F42;
    }
    .bg-jira-card-hover:hover {
        background-color: #2C333A;
    }
    .text-jira-gray {
        color: #9FADBC;
    }
    .border-jira {
        border-color: #3C3F42;
    }
    .bg-jira-input {
        background-color: #22272B;
        border-color: #3C3F42;
    }
    .bg-jira-input:focus {
        background-color: #22272B;
        border-color: #85B8FF;
        box-shadow: 0 0 0 1px #85B8FF;
    }
    
    /* Scrollbar personnalisée */
    ::-webkit-scrollbar {
        width: 6px;
    }
    ::-webkit-scrollbar-track {
        background: #1D2125;
    }
    ::-webkit-scrollbar-thumb {
        background: #3C3F42;
        border-radius: 3px;
    }
    ::-webkit-scrollbar-thumb:hover {
        background: #4C5154;
    }
    
    /* Animations */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }
    
    .fade-in {
        animation: fadeIn 0.5s ease-out;
    }
    
    .pulse-animation {
        animation: pulse 2s infinite;
    }
    
    /* Transitions fluides */
    .transition-all {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
</style>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
let burndownChart;
let refreshInterval;

document.addEventListener('DOMContentLoaded', function() {
    initializeChart();
    startAutoRefresh();
    addEventListeners();
});

function initializeChart() {
    const ctx = document.getElementById('burndownChart').getContext('2d');
    const burndownData = @json($burndownData);
    
    burndownChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: burndownData.labels,
            datasets: [
                {
                    label: 'Burndown idéal',
                    data: burndownData.ideal,
                    borderColor: '#3B82F6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    borderWidth: 2,
                    fill: false,
                    tension: 0.1
                },
                {
                    label: 'Burndown réel',
                    data: burndownData.actual,
                    borderColor: '#10B981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    borderWidth: 2,
                    fill: false,
                    tension: 0.1
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    labels: {
                        color: '#9FADBC'
                    }
                },
                tooltip: {
                    backgroundColor: '#22272B',
                    titleColor: '#FFFFFF',
                    bodyColor: '#9FADBC',
                    borderColor: '#3C3F42',
                    borderWidth: 1
                }
            },
            scales: {
                x: {
                    ticks: {
                        color: '#9FADBC'
                    },
                    grid: {
                        color: '#3C3F42'
                    }
                },
                y: {
                    ticks: {
                        color: '#9FADBC'
                    },
                    grid: {
                        color: '#3C3F42'
                    }
                }
            },
            interaction: {
                intersect: false,
                mode: 'index'
            }
        }
    });
}

function startAutoRefresh() {
    // Rafraîchir les données toutes les 30 secondes
    refreshInterval = setInterval(refreshData, 30000);
}

function addEventListeners() {
    // Ajouter des événements pour les interactions
    document.addEventListener('visibilitychange', function() {
        if (document.hidden) {
            clearInterval(refreshInterval);
        } else {
            startAutoRefresh();
        }
    });
}

async function refreshData() {
    try {
        showLoadingState();
        
        const response = await axios.get(`/projects/{{ $project->id }}/sprints/{{ $sprint->id }}/summary/data`);
        const data = response.data;
        
        updateMetrics(data);
        updateChart(data.burndownData);
        updateTaskLists(data);
        
        showNotification('success', 'Données mises à jour', 'Les métriques ont été actualisées avec succès.');
        
    } catch (error) {
        console.error('Erreur lors du rafraîchissement:', error);
        showNotification('error', 'Erreur', 'Impossible de mettre à jour les données.');
    } finally {
        hideLoadingState();
    }
}

function updateMetrics(data) {
    // Mettre à jour les métriques principales avec animations
    animateValue('progress-percentage', data.progressPercentage + '%');
    animateValue('story-points-display', data.completedStoryPoints + '/' + data.totalStoryPoints);
    animateValue('velocity-display', data.velocityPerDay.toFixed(1));
    animateValue('days-remaining', data.daysRemaining);
    
    // Mettre à jour les barres de progression
    animateProgressBar('progress-bar', data.progressPercentage);
    animateProgressBar('story-points-bar', data.storyPointsPercentage);
    animateProgressBar('work-progress-bar', data.workProgress);
    
    // Mettre à jour les compteurs
    animateValue('completed-tasks', data.completedTasks);
    animateValue('total-tasks', data.totalTasks);
    animateValue('completed-count', data.completedTasks);
    animateValue('in-progress-count', data.inProgressTasks);
    animateValue('todo-count', data.todoTasks);
    animateValue('blocked-count', data.blockedTasks);
    
    // Mettre à jour les métriques de qualité
    animateValue('blockage-rate', data.qualityMetrics.blockage_rate + '%');
    animateValue('efficiency-rate', data.qualityMetrics.efficiency);
    animateValue('time-elapsed', Math.round(data.timeProgress) + '%');
    animateValue('work-done', Math.round(data.workProgress) + '%');
    animateValue('velocity-projected', Math.round(data.projectedVelocity));
    animateValue('velocity-efficiency', Math.round(data.velocityEfficiency) + '%');
}

function updateChart(burndownData) {
    burndownChart.data.labels = burndownData.labels;
    burndownChart.data.datasets[0].data = burndownData.ideal;
    burndownChart.data.datasets[1].data = burndownData.actual;
    burndownChart.update('active');
}

function updateTaskLists(data) {
    // Mettre à jour les listes de tâches
    const completedList = document.getElementById('completed-tasks-list');
    const incompleteList = document.getElementById('incomplete-tasks-list');
    
    if (completedList) {
        completedList.innerHTML = data.completedTasksHtml;
    }
    
    if (incompleteList) {
        incompleteList.innerHTML = data.incompleteTasksHtml;
    }
}

function animateValue(elementId, newValue) {
    const element = document.getElementById(elementId);
    if (!element) return;
    
    element.style.transform = 'scale(1.1)';
    element.style.transition = 'transform 0.2s ease-out';
    
    setTimeout(() => {
        element.textContent = newValue;
        element.style.transform = 'scale(1)';
    }, 100);
}

function animateProgressBar(elementId, percentage) {
    const element = document.getElementById(elementId);
    if (!element) return;
    
    element.style.width = percentage + '%';
}

function showLoadingState() {
    // Ajouter un indicateur de chargement
    const refreshButton = document.querySelector('button[onclick="refreshData()"]');
    if (refreshButton) {
        refreshButton.innerHTML = '<svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
    }
}

function hideLoadingState() {
    // Retirer l'indicateur de chargement
    const refreshButton = document.querySelector('button[onclick="refreshData()"]');
    if (refreshButton) {
        refreshButton.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>';
    }
}

function showNotification(type, title, message) {
    const modal = document.getElementById('notification-modal');
    const icon = document.getElementById('notification-icon');
    const titleEl = document.getElementById('notification-title');
    const messageEl = document.getElementById('notification-message');
    
    // Configurer l'icône selon le type
    if (type === 'success') {
        icon.innerHTML = '<svg class="w-6 h-6 text-green-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>';
        icon.className = 'w-8 h-8 rounded-full bg-green-900 flex items-center justify-center';
    } else {
        icon.innerHTML = '<svg class="w-6 h-6 text-red-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" /></svg>';
        icon.className = 'w-8 h-8 rounded-full bg-red-900 flex items-center justify-center';
    }
    
    titleEl.textContent = title;
    messageEl.textContent = message;
    
    modal.classList.remove('hidden');
    modal.classList.add('fade-in');
}

function closeNotification() {
    const modal = document.getElementById('notification-modal');
    modal.classList.add('hidden');
}

// Fonctions pour les actions rapides
function closeSprint() {
    if (confirm('Êtes-vous sûr de vouloir clôturer ce sprint ?')) {
        // Logique pour clôturer le sprint
        showNotification('success', 'Sprint clôturé', 'Le sprint a été clôturé avec succès.');
    }
}

function createNextSprint() {
    showNotification('info', 'Création du sprint', 'Fonctionnalité en cours de développement.');
}

function generatePDF() {
    showNotification('info', 'Génération PDF', 'Fonctionnalité en cours de développement.');
}

function exportData() {
    showNotification('info', 'Export de données', 'Fonctionnalité en cours de développement.');
}

function scheduleRetrospective() {
    showNotification('info', 'Planification rétrospective', 'Fonctionnalité en cours de développement.');
}

function updateChart() {
    refreshData();
}
</script>
@endsection

@section('header')
Résumé du Sprint
@endsection
