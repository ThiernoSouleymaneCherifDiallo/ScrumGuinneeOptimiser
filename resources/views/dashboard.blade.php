@extends('layouts.app')

@section('content')
<div class="min-h-screen">
    <!-- En-tête du tableau de bord -->
    <header class="bg-white dark:bg-gray-800 shadow-sm mb-6">
        <div class="py-4 px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Tableau de bord</h1>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('projects.create') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Créer un projet
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Contenu principal -->
    <div class="py-6">
        <!-- Statistiques rapides -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Projets actifs</dt>
                                <dd class="flex items-baseline">
                                    <div class="text-2xl font-semibold text-gray-900 dark:text-white">{{ auth()->user()?->projects?->where('status', 'active')->count() ?? 0 }}</div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Tâches en cours</dt>
                                <dd class="flex items-baseline">
                                    <div class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $stats['my_tasks_count'] ?? 0 }}</div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-yellow-500 rounded-md p-3">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Membres</dt>
                                <dd class="flex items-baseline">
                                    <div class="text-2xl font-semibold text-gray-900 dark:text-white">0</div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-purple-500 rounded-md p-3">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Tâches terminées</dt>
                                <dd class="flex items-baseline">
                                    <div class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $stats['total_active_tasks'] ?? 0 }}</div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Liste des projets -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Vos Projets</h3>
            </div>

    <div class="overflow-hidden">
        @forelse(auth()->user()?->projects ?? [] as $project)
            <div class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-150 ease-in-out">
                <div class="px-4 py-4 sm:px-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0">
                                <div class="h-10 w-10 rounded bg-blue-600 flex items-center justify-center text-white font-semibold text-lg">
                                    {{ strtoupper(substr($project->name, 0, 2)) }}
                                </div>
                            </div>
                            <div>
                                <a href="{{ route('projects.show', $project) }}" class="text-sm font-medium text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 hover:underline">{{ $project->name }}</a>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $project->key }}</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $project->status === 'active' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' }}">
                                {{ $project->status === 'active' ? 'Actif' : 'Inactif' }}
                            </span>
                            <div class="flex -space-x-2">
                                <!-- Placeholder pour les avatars des membres -->
                                <div class="h-8 w-8 rounded-full bg-gray-200 dark:bg-gray-600 border-2 border-white dark:border-gray-800 flex items-center justify-center text-xs text-gray-500 dark:text-gray-400">+3</div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-2">
                        <p class="text-sm text-gray-600 dark:text-gray-300 line-clamp-2">
                            {{ $project->description ?? 'Aucune description fournie' }}
                        </p>
                    </div>
                    <div class="mt-4">
                        <div class="flex items-center justify-between text-sm text-gray-500 dark:text-gray-400">
                            <div class="flex items-center space-x-6">
                                <div class="flex items-center">
                                    <svg class="h-4 w-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                    </svg>
                                    0 tâches
                                </div>
                                <div class="flex items-center">
                                    <svg class="h-4 w-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    Créé le {{ $project->created_at->format('d/m/Y') }}
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <button class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Aucun projet</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Commencez par créer un nouveau projet pour organiser votre travail.</p>
                <div class="mt-6">
                    <a href="{{ route('projects.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Créer votre premier projet
                    </a>
                </div>
            </div>
        @endforelse
    </div>
        </div>

        <!-- Section Tâches en cours -->
        @if($myTasks->count() > 0 || $allActiveTasks->count() > 0)
        <div class="mt-8">
            <!-- Mes tâches -->
            @if($myTasks->count() > 0)
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden mb-6">
                <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Mes Tâches en Cours</h3>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                            {{ $myTasks->count() }} tâche(s)
                        </span>
                    </div>
                </div>
                <div class="overflow-hidden">
                    @foreach($myTasks as $task)
                    <div class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-150 ease-in-out border-b border-gray-200 dark:border-gray-700 last:border-b-0">
                        <div class="px-4 py-4 sm:px-6">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="flex-shrink-0">
                                        <div class="h-8 w-8 rounded-full flex items-center justify-center text-white text-sm font-semibold
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
                                        <div class="flex items-center space-x-2">
                                            <a href="{{ route('projects.show', $task->project) }}" class="text-sm font-medium text-gray-900 dark:text-white hover:text-blue-600 dark:hover:text-blue-400 truncate">
                                                {{ $task->title }}
                                            </a>
                                            @if($task->comments()->count() > 0)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                                    💬 {{ $task->comments()->count() }}
                                                </span>
                                            @endif
                                        </div>
                                        <div class="flex items-center space-x-4 mt-1">
                                            <span class="text-xs text-gray-500 dark:text-gray-400">{{ $task->project->name }}</span>
                                            @if($task->sprint)
                                                <span class="text-xs text-gray-500 dark:text-gray-400">Sprint: {{ $task->sprint->name }}</span>
                                            @endif
                                            @if($task->due_date)
                                                <span class="text-xs {{ $task->due_date < now() ? 'text-red-500' : 'text-gray-500 dark:text-gray-400' }}">
                                                    Échéance: {{ $task->due_date->format('d/m/Y') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @switch($task->status)
                                            @case('todo') bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300
                                            @case('in_progress') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                            @case('review') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                            @default bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                        @endswitch">
                                        {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                    </span>
                                    @if($task->comments()->count() > 0)
                                        <a href="{{ route('projects.show', $task->project) }}" 
                                           class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300"
                                           title="Voir le projet">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Toutes les tâches actives -->
            @if($allActiveTasks->count() > 0)
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
                <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Toutes les Tâches Actives</h3>
                        <div class="flex items-center space-x-2">
                            @if($stats['urgent_tasks_count'] > 0)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                    ⚠️ {{ $stats['urgent_tasks_count'] }} urgentes
                                </span>
                            @endif
                            @if($stats['overdue_tasks_count'] > 0)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                    ⏰ {{ $stats['overdue_tasks_count'] }} en retard
                                </span>
                            @endif
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                {{ $allActiveTasks->count() }} tâche(s)
                            </span>
                        </div>
                    </div>
                </div>
                <div class="overflow-hidden">
                    @foreach($allActiveTasks as $task)
                    <div class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-150 ease-in-out border-b border-gray-200 dark:border-gray-700 last:border-b-0">
                        <div class="px-4 py-4 sm:px-6">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="flex-shrink-0">
                                        <div class="h-8 w-8 rounded-full flex items-center justify-center text-white text-sm font-semibold
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
                                        <div class="flex items-center space-x-2">
                                            <a href="{{ route('projects.show', $task->project) }}" class="text-sm font-medium text-gray-900 dark:text-white hover:text-blue-600 dark:hover:text-blue-400 truncate">
                                                {{ $task->title }}
                                            </a>
                                            @if($task->comments()->count() > 0)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                                    💬 {{ $task->comments()->count() }}
                                                </span>
                                            @endif
                                        </div>
                                        <div class="flex items-center space-x-4 mt-1">
                                            <span class="text-xs text-gray-500 dark:text-gray-400">{{ $task->project->name }}</span>
                                            @if($task->assignee)
                                                <span class="text-xs text-gray-500 dark:text-gray-400">Assigné à: {{ $task->assignee->name }}</span>
                                            @else
                                                <span class="text-xs text-gray-500 dark:text-gray-400">Non assigné</span>
                                            @endif
                                            @if($task->sprint)
                                                <span class="text-xs text-gray-500 dark:text-gray-400">Sprint: {{ $task->sprint->name }}</span>
                                            @endif
                                            @if($task->due_date)
                                                <span class="text-xs {{ $task->due_date < now() ? 'text-red-500' : 'text-gray-500 dark:text-gray-400' }}">
                                                    Échéance: {{ $task->due_date->format('d/m/Y') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @switch($task->status)
                                            @case('todo') bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300
                                            @case('in_progress') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                            @case('review') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                            @default bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                        @endswitch">
                                        {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                    </span>
                                    @if($task->comments()->count() > 0)
                                        <a href="{{ route('projects.show', $task->project) }}" 
                                           class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300"
                                           title="Voir le projet">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
        @endif
    </div>
</div>
@endsection
