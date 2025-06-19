@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="bg-gradient-to-r from-gray-800 to-gray-900 rounded-lg shadow-lg p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-white mb-2">Tâches - {{ $project->name }}</h1>
                <p class="text-gray-300">Gérez et suivez toutes les tâches de ce projet</p>
            </div>
            <div class="flex items-center space-x-4">
                <a href="{{ route('projects.show', $project) }}" class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors">
                    ← Retour au projet
                </a>
                <div class="text-right">
                    <div class="text-2xl font-bold text-white">{{ $stats['total'] }}</div>
                    <div class="text-sm text-gray-300">Tâches totales</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques rapides -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
        <div class="bg-gray-800 rounded-lg shadow p-4 border border-gray-700">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-white">{{ $stats['my_tasks'] }}</div>
                    <div class="text-sm text-gray-400">Mes tâches</div>
                </div>
            </div>
        </div>

        <div class="bg-gray-800 rounded-lg shadow p-4 border border-gray-700">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-red-500 rounded-md p-3">
                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-white">{{ $stats['urgent'] }}</div>
                    <div class="text-sm text-gray-400">Urgentes</div>
                </div>
            </div>
        </div>

        <div class="bg-gray-800 rounded-lg shadow p-4 border border-gray-700">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-yellow-500 rounded-md p-3">
                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-white">{{ $stats['overdue'] }}</div>
                    <div class="text-sm text-gray-400">En retard</div>
                </div>
            </div>
        </div>

        <div class="bg-gray-800 rounded-lg shadow p-4 border border-gray-700">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-purple-500 rounded-md p-3">
                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-white">{{ $stats['backlog'] }}</div>
                    <div class="text-sm text-gray-400">Backlog</div>
                </div>
            </div>
        </div>

        <div class="bg-gray-800 rounded-lg shadow p-4 border border-gray-700">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-white">{{ $tasks->count() }}</div>
                    <div class="text-sm text-gray-400">Affichées</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="bg-gray-800 rounded-lg shadow-lg mb-6 border border-gray-700">
        <div class="p-6 border-b border-gray-700">
            <h3 class="text-lg font-semibold text-white mb-4">Filtres</h3>
            
            <form method="GET" action="{{ route('tasks.index', $project) }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4">
                <!-- Recherche -->
                <div class="lg:col-span-2">
                    <label class="block text-sm font-medium text-gray-300 mb-1">Recherche</label>
                    <input type="text" name="search" value="{{ $filters['search'] ?? '' }}" 
                           placeholder="Titre ou description..."
                           class="w-full rounded-md border-gray-600 bg-gray-700 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <!-- Sprint -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Sprint</label>
                    <select name="sprint" class="w-full rounded-md border-gray-600 bg-gray-700 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Tous les sprints</option>
                        <option value="backlog" {{ ($filters['sprint'] ?? '') == 'backlog' ? 'selected' : '' }}>Backlog</option>
                        @foreach($sprints as $sprint)
                            <option value="{{ $sprint->id }}" {{ ($filters['sprint'] ?? '') == $sprint->id ? 'selected' : '' }}>
                                {{ $sprint->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Assigné -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Assigné</label>
                    <select name="assignee" class="w-full rounded-md border-gray-600 bg-gray-700 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Tous</option>
                        <option value="me" {{ ($filters['assignee'] ?? '') == 'me' ? 'selected' : '' }}>Mes tâches</option>
                        <option value="unassigned" {{ ($filters['assignee'] ?? '') == 'unassigned' ? 'selected' : '' }}>Non assignées</option>
                        @foreach($project->members as $member)
                            <option value="{{ $member->id }}" {{ ($filters['assignee'] ?? '') == $member->id ? 'selected' : '' }}>
                                {{ $member->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Statut -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Statut</label>
                    <select name="status" class="w-full rounded-md border-gray-600 bg-gray-700 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Tous</option>
                        <option value="todo" {{ ($filters['status'] ?? '') == 'todo' ? 'selected' : '' }}>À faire</option>
                        <option value="in_progress" {{ ($filters['status'] ?? '') == 'in_progress' ? 'selected' : '' }}>En cours</option>
                        <option value="review" {{ ($filters['status'] ?? '') == 'review' ? 'selected' : '' }}>En révision</option>
                        <option value="done" {{ ($filters['status'] ?? '') == 'done' ? 'selected' : '' }}>Terminé</option>
                    </select>
                </div>

                <!-- Priorité -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Priorité</label>
                    <select name="priority" class="w-full rounded-md border-gray-600 bg-gray-700 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Toutes</option>
                        <option value="urgent" {{ ($filters['priority'] ?? '') == 'urgent' ? 'selected' : '' }}>Urgente</option>
                        <option value="high" {{ ($filters['priority'] ?? '') == 'high' ? 'selected' : '' }}>Haute</option>
                        <option value="medium" {{ ($filters['priority'] ?? '') == 'medium' ? 'selected' : '' }}>Moyenne</option>
                        <option value="low" {{ ($filters['priority'] ?? '') == 'low' ? 'selected' : '' }}>Basse</option>
                    </select>
                </div>

                <!-- Boutons -->
                <div class="lg:col-span-6 flex items-end space-x-4">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                        Filtrer
                    </button>
                    <a href="{{ route('tasks.index', $project) }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                        Réinitialiser
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Liste des tâches -->
    <div class="bg-gray-800 rounded-lg shadow-lg overflow-hidden border border-gray-700">
        <div class="px-6 py-4 border-b border-gray-700">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-white">Résultats ({{ $tasks->total() }})</h3>
                <div class="flex items-center space-x-2">
                    <span class="text-sm text-gray-400">Tri:</span>
                    <select onchange="window.location.href=this.value" class="text-sm border-gray-600 bg-gray-700 text-white rounded">
                        <option value="{{ request()->fullUrlWithQuery(['sort' => 'created_at', 'order' => 'desc']) }}" {{ request('sort') == 'created_at' && request('order') == 'desc' ? 'selected' : '' }}>Plus récentes</option>
                        <option value="{{ request()->fullUrlWithQuery(['sort' => 'created_at', 'order' => 'asc']) }}" {{ request('sort') == 'created_at' && request('order') == 'asc' ? 'selected' : '' }}>Plus anciennes</option>
                        <option value="{{ request()->fullUrlWithQuery(['sort' => 'priority', 'order' => 'desc']) }}" {{ request('sort') == 'priority' && request('order') == 'desc' ? 'selected' : '' }}>Priorité haute</option>
                        <option value="{{ request()->fullUrlWithQuery(['sort' => 'due_date', 'order' => 'asc']) }}" {{ request('sort') == 'due_date' && request('order') == 'asc' ? 'selected' : '' }}>Échéance proche</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="overflow-hidden">
            @forelse($tasks as $task)
                <div class="hover:bg-gray-700/50 transition-colors duration-150 ease-in-out border-b border-gray-700 last:border-b-0">
                    <div class="px-6 py-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div class="flex-shrink-0">
                                    <div class="h-10 w-10 rounded-full flex items-center justify-center text-white text-sm font-semibold
                                        @switch($task->priority)
                                            @case('urgent') bg-red-500
                                            @case('high') bg-orange-500
                                            @case('medium') bg-yellow-500
                                            @default bg-green-500
                                        @endswitch">
                                        {{ strtoupper(substr($task->title, 0, 1)) }}
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center space-x-2 mb-1">
                                        <a href="{{ route('tasks.comments.index', [$project, $task]) }}" class="text-lg font-medium text-white hover:text-blue-400 truncate">
                                            {{ $task->title }}
                                        </a>
                                        @if($task->comments()->count() > 0)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-900 text-blue-200">
                                                💬 {{ $task->comments()->count() }}
                                            </span>
                                        @endif
                                    </div>
                                    <div class="flex items-center space-x-4 text-sm text-gray-400">
                                        @if($task->assignee)
                                            <span class="flex items-center">
                                                <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                </svg>
                                                {{ $task->assignee->name }}
                                            </span>
                                        @else
                                            <span class="text-red-400">Non assigné</span>
                                        @endif
                                        @if($task->sprint)
                                            <span class="flex items-center">
                                                <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                                {{ $task->sprint->name }}
                                            </span>
                                        @else
                                            <span class="text-purple-400">Backlog</span>
                                        @endif
                                        @if($task->due_date)
                                            <span class="flex items-center {{ $task->due_date < now() ? 'text-red-400' : 'text-gray-400' }}">
                                                <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                {{ $task->due_date->format('d/m/Y') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center space-x-3">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @switch($task->status)
                                        @case('todo') bg-gray-700 text-gray-300
                                        @case('in_progress') bg-blue-900 text-blue-200
                                        @case('review') bg-yellow-900 text-yellow-200
                                        @default bg-green-900 text-green-200
                                    @endswitch">
                                    {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                </span>
                                <a href="{{ route('tasks.comments.index', [$project, $task]) }}" 
                                   class="text-blue-400 hover:text-blue-300">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-white">Aucune tâche trouvée</h3>
                    <p class="mt-1 text-sm text-gray-400">Essayez de modifier vos filtres ou créez une nouvelle tâche.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($tasks->hasPages())
            <div class="px-6 py-4 border-t border-gray-700">
                {{ $tasks->links() }}
            </div>
        @endif
    </div>
</div>
@endsection 