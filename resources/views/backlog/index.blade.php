@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-900 text-white">
    <div class="container mx-auto px-4 py-8">
        <!-- En-tête -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-white mb-2">Backlog - {{ $project->name }}</h1>
                    <div class="flex items-center space-x-6 text-gray-400">
                        <span>{{ $backlogTasks->count() }} tâches en backlog</span>
                        <span>{{ $sprints->count() }} sprints</span>
                    </div>
                </div>
                
                <div class="flex space-x-3">
                    <button onclick="openTaskModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                        + Nouvelle tâche
                    </button>
                    <a href="{{ route('projects.sprints.create', $project) }}" class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
                        + Nouveau sprint
                    </a>
                </div>
            </div>
        </div>

        <!-- Grille Backlog + Sprints -->
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            
            <!-- Colonne Backlog -->
            <div class="bg-gray-800 rounded-lg p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-bold text-blue-400">Backlog</h2>
                    <span class="bg-blue-900 text-blue-300 px-2 py-1 rounded text-sm">{{ $backlogTasks->count() }}</span>
                </div>
                
                <div class="space-y-3">
                    @forelse($backlogTasks as $task)
                        <div class="bg-gray-700 rounded-lg p-4 border border-gray-600 hover:border-blue-500/50 transition-all duration-200">
                            <div class="flex items-start justify-between mb-2">
                                <h3 class="text-sm font-medium text-white flex-1 mr-2">{{ $task->title }}</h3>
                                <div class="flex items-center space-x-1 flex-shrink-0">
                                    @if($task->story_points)
                                        <span class="bg-gray-600 text-gray-300 px-1.5 py-0.5 rounded text-xs font-medium">{{ $task->story_points }} pts</span>
                                    @endif
                                    <span class="bg-gray-600 text-gray-300 px-1.5 py-0.5 rounded text-xs font-medium">{{ ucfirst($task->priority) }}</span>
                                </div>
                            </div>
                            
                            @if($task->description)
                                <p class="text-xs text-gray-400 mb-3 line-clamp-2">{{ Str::limit($task->description, 80) }}</p>
                            @endif
                            
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center space-x-2">
                                    @if($task->assignee)
                                        <div class="flex items-center">
                                            <span class="bg-blue-500 text-white px-1.5 py-0.5 rounded text-xs font-medium">{{ substr($task->assignee->name, 0, 1) }}</span>
                                            <span class="ml-1 text-xs text-gray-400">{{ $task->assignee->name }}</span>
                                        </div>
                                    @else
                                        <span class="text-xs text-gray-500">Non assigné</span>
                                    @endif
                                </div>
                                <span class="bg-gray-600 text-gray-300 px-1.5 py-0.5 rounded text-xs font-medium">{{ ucfirst(str_replace('_', ' ', $task->status)) }}</span>
                            </div>
                            
                            <!-- Actions pour déplacer vers un sprint -->
                            @if($sprints->count() > 0)
                                <div class="border-t border-gray-600 pt-3">
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs text-gray-400 font-medium">Déplacer vers :</span>
                                        <div class="flex items-center space-x-1">
                                            @if($sprints->count() <= 3)
                                                @foreach($sprints as $sprint)
                                                    <button onclick="moveTaskToSprint({{ $task->id }}, {{ $sprint->id }})" 
                                                            class="bg-blue-600 hover:bg-blue-700 text-white px-2 py-1 rounded text-xs font-medium transition-colors duration-200">
                                                        {{ Str::limit($sprint->name, 8) }}
                                                    </button>
                                                @endforeach
                                            @else
                                                @foreach($sprints->take(2) as $sprint)
                                                    <button onclick="moveTaskToSprint({{ $task->id }}, {{ $sprint->id }})" 
                                                            class="bg-blue-600 hover:bg-blue-700 text-white px-2 py-1 rounded text-xs font-medium transition-colors duration-200">
                                                        {{ Str::limit($sprint->name, 8) }}
                                                    </button>
                                                @endforeach
                                                <button onclick="showSprintSelector({{ $task->id }})" 
                                                        class="bg-gray-600 hover:bg-gray-700 text-white px-2 py-1 rounded text-xs font-medium transition-colors duration-200">
                                                    +{{ $sprints->count() - 2 }}
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-500 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            <p class="text-gray-500 text-sm">Aucune tâche dans le backlog</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Colonnes des Sprints -->
            @foreach($sprints as $sprint)
                <div class="bg-gray-800 rounded-lg p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex-1 min-w-0">
                            <h3 class="text-lg font-bold text-white truncate">{{ $sprint->name }}</h3>
                            <p class="text-xs text-gray-400 mt-1">
                                @if($sprint->start_date && $sprint->end_date)
                                    {{ $sprint->start_date->format('d M') }} - {{ $sprint->end_date->format('d M') }}
                                @else
                                    Dates non définies
                                @endif
                            </p>
                        </div>
                        <span class="bg-gray-700 text-gray-300 px-2 py-1 rounded text-sm font-medium ml-2">{{ $sprint->tasks->count() }}</span>
                    </div>
                    
                    <div class="space-y-3">
                        @foreach($sprint->tasks as $task)
                            <div class="bg-gray-700 rounded-lg p-4 border border-gray-600 hover:border-blue-500/50 transition-all duration-200">
                                <div class="flex items-start justify-between mb-2">
                                    <h3 class="text-sm font-medium text-white flex-1 mr-2">{{ $task->title }}</h3>
                                    <div class="flex items-center space-x-1 flex-shrink-0">
                                        @if($task->story_points)
                                            <span class="bg-gray-600 text-gray-300 px-1.5 py-0.5 rounded text-xs font-medium">{{ $task->story_points }} pts</span>
                                        @endif
                                        <span class="bg-gray-600 text-gray-300 px-1.5 py-0.5 rounded text-xs font-medium">{{ ucfirst($task->priority) }}</span>
                                    </div>
                                </div>
                                
                                @if($task->description)
                                    <p class="text-xs text-gray-400 mb-3 line-clamp-2">{{ Str::limit($task->description, 80) }}</p>
                                @endif
                                
                                <div class="flex items-center justify-between mb-3">
                                    <div class="flex items-center space-x-2">
                                        @if($task->assignee)
                                            <div class="flex items-center">
                                                <span class="bg-blue-500 text-white px-1.5 py-0.5 rounded text-xs font-medium">{{ substr($task->assignee->name, 0, 1) }}</span>
                                                <span class="ml-1 text-xs text-gray-400">{{ $task->assignee->name }}</span>
                                            </div>
                                        @else
                                            <span class="text-xs text-gray-500">Non assigné</span>
                                        @endif
                                    </div>
                                    <span class="bg-gray-600 text-gray-300 px-1.5 py-0.5 rounded text-xs font-medium">{{ ucfirst(str_replace('_', ' ', $task->status)) }}</span>
                                </div>
                                
                                <!-- Actions pour déplacer la tâche -->
                                <div class="border-t border-gray-600 pt-3">
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs text-gray-400 font-medium">Actions :</span>
                                        <div class="flex items-center space-x-1">
                                            <button onclick="moveTaskToBacklog({{ $task->id }})" 
                                                    class="bg-gray-600 hover:bg-gray-700 text-white px-2 py-1 rounded text-xs font-medium transition-colors duration-200">
                                                Backlog
                                            </button>
                                            @if($sprints->count() > 1)
                                                <button onclick="showSprintSelector({{ $task->id }})" 
                                                        class="bg-blue-600 hover:bg-blue-700 text-white px-2 py-1 rounded text-xs font-medium transition-colors duration-200">
                                                    Autre sprint
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        
                        @if($sprint->tasks->count() == 0)
                            <div class="text-center py-6">
                                <svg class="mx-auto h-8 w-8 text-gray-500 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                <p class="text-gray-500 text-xs">Aucune tâche</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Modal de création de tâche -->
<div id="taskModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
        
        <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
            <div>
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Nouvelle tâche</h3>
                <form action="{{ route('projects.tasks.store', $project) }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700">Titre *</label>
                            <input type="text" name="title" id="title" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea name="description" id="description" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"></textarea>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700">Statut</label>
                                <select name="status" id="status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="todo">À faire</option>
                                    <option value="in_progress">En cours</option>
                                    <option value="review">En revue</option>
                                    <option value="done">Terminé</option>
                                </select>
                            </div>
                            
                            <div>
                                <label for="priority" class="block text-sm font-medium text-gray-700">Priorité</label>
                                <select name="priority" id="priority" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="low">Basse</option>
                                    <option value="medium" selected>Moyenne</option>
                                    <option value="high">Haute</option>
                                </select>
                            </div>
                        </div>
                        
                        <div>
                            <label for="story_points" class="block text-sm font-medium text-gray-700">Points d'histoire</label>
                            <input type="number" name="story_points" id="story_points" min="0" value="1" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
                    
                    <div class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:col-start-2 sm:text-sm">
                            Créer la tâche
                        </button>
                        <button type="button" onclick="closeTaskModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:col-start-1 sm:text-sm">
                            Annuler
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal de sélection de sprint amélioré -->
<div id="sprintSelectorModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
        
        <div class="inline-block align-bottom bg-gray-800 rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full sm:p-6 border border-gray-600">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg leading-6 font-medium text-white">Choisir un sprint</h3>
                    <button onclick="closeSprintSelector()" class="text-gray-400 hover:text-white transition-colors">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="space-y-2 max-h-60 overflow-y-auto" id="sprintOptions">
                    <!-- Les options de sprint seront ajoutées ici dynamiquement -->
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    console.log('Scripts chargés pour le backlog');
    
    // Gestion du modal de création de tâche
    function openTaskModal() {
        console.log('Ouverture du modal de tâche');
        document.getElementById('taskModal').classList.remove('hidden');
    }

    function closeTaskModal() {
        console.log('Fermeture du modal de tâche');
        document.getElementById('taskModal').classList.add('hidden');
    }

    // Gestion du modal de sélection de sprint amélioré
    function showSprintSelector(taskId) {
        console.log('Affichage du sélecteur de sprint pour la tâche:', taskId);
        const modal = document.getElementById('sprintSelectorModal');
        const optionsContainer = document.getElementById('sprintOptions');
        
        // Générer les options de sprint
        const sprints = @json($sprints);
        console.log('Sprints disponibles:', sprints);
        
        let optionsHtml = '';
        
        sprints.forEach(sprint => {
            const dates = sprint.start_date && sprint.end_date 
                ? `${sprint.start_date} - ${sprint.end_date}`
                : 'Dates non définies';
                
            optionsHtml += `
                <button onclick="moveTaskToSprint(${taskId}, ${sprint.id}); closeSprintSelector()" 
                        class="w-full text-left p-3 rounded-lg border border-gray-600 bg-gray-700 hover:bg-gray-600 hover:border-blue-500 transition-all duration-200">
                    <div class="font-medium text-white">${sprint.name}</div>
                    <div class="text-sm text-gray-400">${dates}</div>
                </button>
            `;
        });
        
        optionsContainer.innerHTML = optionsHtml;
        modal.classList.remove('hidden');
    }

    function closeSprintSelector() {
        console.log('Fermeture du sélecteur de sprint');
        document.getElementById('sprintSelectorModal').classList.add('hidden');
    }

    // Fonctions pour déplacer les tâches
    function moveTaskToSprint(taskId, sprintId) {
        console.log('Déplacement de la tâche', taskId, 'vers le sprint', sprintId);
        
        // Récupérer le token CSRF
        const token = document.querySelector('meta[name="csrf-token"]').content;
        console.log('Token CSRF:', token);
        
        fetch(`/api/tasks/${taskId}/move`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ sprint_id: sprintId })
        })
        .then(response => {
            console.log('Response status:', response.status);
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data);
            if (data.success) {
                console.log('Tâche déplacée avec succès, rechargement de la page');
                window.location.reload();
            } else {
                console.error('Erreur lors du déplacement:', data.message);
                alert('Erreur lors du déplacement de la tâche: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Erreur fetch:', error);
            alert('Erreur lors du déplacement de la tâche: ' + error.message);
        });
    }

    function moveTaskToBacklog(taskId) {
        console.log('Déplacement de la tâche', taskId, 'vers le backlog');
        
        // Récupérer le token CSRF
        const token = document.querySelector('meta[name="csrf-token"]').content;
        console.log('Token CSRF:', token);
        
        fetch(`/api/tasks/${taskId}/move`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ sprint_id: null })
        })
        .then(response => {
            console.log('Response status:', response.status);
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data);
            if (data.success) {
                console.log('Tâche déplacée vers le backlog avec succès, rechargement de la page');
                window.location.reload();
            } else {
                console.error('Erreur lors du déplacement:', data.message);
                alert('Erreur lors du déplacement de la tâche: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Erreur fetch:', error);
            alert('Erreur lors du déplacement de la tâche: ' + error.message);
        });
    }

    // Fermer les modals si on clique en dehors
    window.onclick = function(event) {
        const taskModal = document.getElementById('taskModal');
        const sprintModal = document.getElementById('sprintSelectorModal');
        
        if (event.target === taskModal) {
            closeTaskModal();
        }
        if (event.target === sprintModal) {
            closeSprintSelector();
        }
    }

    // Fermer avec la touche Échap
    document.onkeydown = function(evt) {
        evt = evt || window.event;
        if (evt.key === 'Escape') {
            closeTaskModal();
            closeSprintSelector();
        }
    };
    
    console.log('Toutes les fonctions JavaScript sont définies');
</script>
@endpush 