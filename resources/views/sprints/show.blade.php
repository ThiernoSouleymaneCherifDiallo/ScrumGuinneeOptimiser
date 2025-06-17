@extends('layouts.app')

@section('content')
@if(session('success'))
    <div class="mb-4 rounded-md bg-green-50 p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium text-green-800">
                    {{ session('success') }}
                </p>
            </div>
        </div>
    </div>
@endif

<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <nav class="flex" aria-label="Breadcrumb">
                    <ol class="flex items-center space-x-4">
                        <li>
                            <div>
                                <a href="{{ route('projects.show', $project) }}" class="text-gray-500 hover:text-gray-700">
                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                                    </svg>
                                </a>
                            </div>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="flex-shrink-0 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                </svg>
                                <a href="{{ route('projects.sprints.index', $project) }}" class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700">Sprints</a>
                            </div>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="flex-shrink-0 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                </svg>
                                <span class="ml-4 text-sm font-medium text-gray-500">{{ $sprint->name }}</span>
                            </div>
                        </li>
                    </ol>
                </nav>
                <div class="mt-2 md:flex md:items-center md:justify-between">
                    <div class="flex-1 min-w-0">
                        <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">{{ $sprint->name }}</h2>
                        <div class="mt-1 flex flex-col sm:flex-row sm:flex-wrap sm:mt-0 sm:space-x-6">
                            <div class="mt-2 flex items-center text-sm text-gray-500">
                                <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                                </svg>
                                @if($sprint->start_date && $sprint->end_date)
                                    {{ $sprint->start_date->format('d M Y') }} - {{ $sprint->end_date->format('d M Y') }}
                                    ({{ $sprint->start_date->diffInDays($sprint->end_date) + 1 }} jours)
                                @else
                                    Dates non définies
                                @endif
                            </div>
                            <div class="mt-2 flex items-center text-sm text-gray-500">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    @if($sprint->status === 'active') 
                                        bg-green-100 text-green-800 
                                    @elseif($sprint->status === 'completed')
                                        bg-gray-100 text-gray-800
                                    @else
                                        bg-blue-100 text-blue-800
                                    @endif">
                                    @if($sprint->status === 'active')
                                        EN COURS
                                    @elseif($sprint->status === 'completed')
                                        TERMINÉ
                                    @else
                                        PLANIFIÉ
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 flex-shrink-0 flex space-x-3 md:mt-0 md:ml-4">
                        <a href="{{ route('projects.sprints.edit', [$project, $sprint]) }}" 
                           class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                            </svg>
                            Modifier
                        </a>
                        <button type="button" 
                                onclick="openTaskModal()"
                                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                            </svg>
                            Nouvelle tâche
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Colonne de gauche - Détails du sprint -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Objectif du sprint -->
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        Objectif du sprint
                    </h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">
                        Ce que l'équipe prévoit d'accomplir pendant ce sprint.
                    </p>
                </div>
                <div class="border-t border-gray-200 px-4 py-5 sm:px-6">
                    @if($sprint->goal)
                        <p class="text-gray-700 whitespace-pre-line">{{ $sprint->goal }}</p>
                    @else
                        <p class="text-gray-500 italic">Aucun objectif défini pour ce sprint.</p>
                    @endif
                </div>
            </div>

            <!-- Tâches du sprint -->
            <div class="bg-gray-900 shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6 border-b border-gray-700">
                    <h3 class="text-lg leading-6 font-medium text-white">
                        Tâches du sprint
                    </h3>
                </div>
                <div class="bg-gray-800 overflow-hidden">
                    <ul class="divide-y divide-gray-700" id="tasks-list">
                        @forelse($sprint->tasks as $task)
                            <li class="px-4 py-4 sm:px-6 hover:bg-gray-750 transition-colors duration-150 task-item" data-task-id="{{ $task->id }}">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center">
                                            <!-- Menu déroulant pour le statut -->
                                            <div class="relative inline-block text-left mr-3" x-data="{ open: false }">
                                                <div>
                                                    <button type="button" 
                                                            @click="open = !open"
                                                            class="inline-flex justify-center w-full rounded-md px-3 py-1 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-blue-500
                                                            @if($task->status === 'todo') bg-gray-700 text-gray-300 hover:bg-gray-600
                                                            @elseif($task->status === 'in_progress') bg-blue-900 text-blue-100 hover:bg-blue-800
                                                            @elseif($task->status === 'review') bg-yellow-900 text-yellow-100 hover:bg-yellow-800
                                                            @else bg-green-900 text-green-100 hover:bg-green-800 @endif">
                                                        {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                                        <svg class="-mr-1 ml-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                        </svg>
                                                    </button>
                                                </div>
                                                <div x-show="open" 
                                                     @click.away="open = false"
                                                     x-transition:enter="transition ease-out duration-100"
                                                     x-transition:enter-start="transform opacity-0 scale-95"
                                                     x-transition:enter-end="transform opacity-100 scale-100"
                                                     x-transition:leave="transition ease-in duration-75"
                                                     x-transition:leave-start="transform opacity-100 scale-100"
                                                     x-transition:leave-end="transform opacity-0 scale-95"
                                                     class="origin-top-right absolute left-0 mt-2 w-40 rounded-md shadow-lg bg-gray-700 ring-1 ring-black ring-opacity-5 focus:outline-none z-10">
                                                    <div class="py-1">
                                                        @foreach(['todo', 'in_progress', 'review', 'done'] as $status)
                                                            <button type="button" 
                                                                    @click="updateTaskStatus({{ $task->id }}, '{{ $status }}'); open = false;"
                                                                    class="w-full text-left px-4 py-2 text-sm {{ $task->status === $status ? 'bg-gray-600 text-white' : 'text-gray-200 hover:bg-gray-600' }}">
                                                                {{ ucfirst(str_replace('_', ' ', $status)) }}
                                                            </button>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <p class="ml-2 text-sm font-medium text-white">
                                                {{ $task->title }}
                                            </p>
                                        </div>
                                        @if($task->description)
                                            <p class="mt-1 text-sm text-gray-400 truncate">
                                                {{ $task->description }}
                                            </p>
                                        @endif
                                    </div>
                                    
                                    <div class="flex items-center space-x-2">
                                        <!-- Menu déroulant pour l'assignation -->
                                        <div class="relative inline-block text-left" x-data="{ open: false }">
                                            <div>
                                                <button type="button" 
                                                        @click="open = !open"
                                                        class="flex items-center justify-center w-8 h-8 rounded-full bg-gray-700 text-gray-300 hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-blue-500">
                                                    @if($task->assignee)
                                                        <span class="inline-flex items-center justify-center h-6 w-6 rounded-full bg-blue-500 text-xs font-medium text-white">
                                                            {{ substr($task->assignee->name, 0, 1) }}
                                                        </span>
                                                    @else
                                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                            <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6z" />
                                                        </svg>
                                                    @endif
                                                </button>
                                            </div>
                                            <div x-show="open" 
                                                 @click.away="open = false"
                                                 x-transition:enter="transition ease-out duration-100"
                                                 x-transition:enter-start="transform opacity-0 scale-95"
                                                 x-transition:enter-end="transform opacity-100 scale-100"
                                                 x-transition:leave="transition ease-in duration-75"
                                                 x-transition:leave-start="transform opacity-100 scale-100"
                                                 x-transition:leave-end="transform opacity-0 scale-95"
                                                 class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-gray-700 ring-1 ring-black ring-opacity-5 focus:outline-none z-10">
                                                <div class="py-1">
                                                    <div class="px-4 py-2 text-xs text-gray-400 border-b border-gray-600">
                                                        Assigner à
                                                    </div>
                                                    @foreach($project->members as $member)
                                                        <button type="button" 
                                                                @click="assignTask({{ $task->id }}, {{ $member->id }}); open = false;"
                                                                class="w-full text-left px-4 py-2 text-sm flex items-center space-x-2 {{ $task->assignee_id === $member->id ? 'bg-gray-600 text-white' : 'text-gray-200 hover:bg-gray-600' }}">
                                                            <span class="inline-flex items-center justify-center h-6 w-6 rounded-full bg-blue-500 text-xs font-medium text-white">
                                                                {{ substr($member->name, 0, 1) }}
                                                            </span>
                                                            <span>{{ $member->name }}</span>
                                                        </button>
                                                    @endforeach
                                                    @if($task->assignee_id)
                                                        <div class="border-t border-gray-600">
                                                            <button type="button" 
                                                                    @click="assignTask({{ $task->id }}, null); open = false;"
                                                                    class="w-full text-left px-4 py-2 text-sm text-red-400 hover:bg-gray-600">
                                                                Ne pas assigner
                                                            </button>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        
                                        @if($task->story_points)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-700 text-gray-300">
                                                {{ $task->story_points }} pts
                                            </span>
                                        @endif
                                        
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            @if($task->priority === 'low') bg-green-900 text-green-100
                                            @elseif($task->priority === 'medium') bg-yellow-900 text-yellow-100
                                            @elseif($task->priority === 'high') bg-red-900 text-red-100
                                            @else bg-purple-900 text-purple-100 @endif">
                                            {{ ucfirst($task->priority) }}
                                        </span>
                                    </div>
                                </div>
                            </li>
                        @empty
                            <li class="px-4 py-4 sm:px-6 text-center text-gray-400">
                                Aucune tâche pour ce sprint.
                            </li>
                        @endforelse
                    </ul>
                </div>
                @if(auth()->user()->can('create', [\App\Models\Task::class, $project]))
                    <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                        <button type="button" @click="openTaskModal()" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                            </svg>
                            Nouvelle tâche
                        </button>
                    </div>
                @endif
            </div>
        </div>
        </div>

        <!-- Colonne de droite - Métriques et actions -->
        <div class="space-y-6">
            <!-- Métriques du sprint -->
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        Métriques du sprint
                    </h3>
                </div>
                <div class="border-t border-gray-200 px-4 py-5 sm:px-6">
                    <dl class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                        <div class="px-4 py-5 bg-gray-50 rounded-lg overflow-hidden shadow">
                            <dt class="text-sm font-medium text-gray-500 truncate">Vélocité</dt>
                            <dd class="mt-1 text-3xl font-semibold text-gray-900">{{ $sprint->tasks->sum('story_points') }} pts</dd>
                        </div>
                        <div class="px-4 py-5 bg-gray-50 rounded-lg overflow-hidden shadow">
                            <dt class="text-sm font-medium text-gray-500 truncate">Tâches complétées</dt>
                            <dd class="mt-1 text-3xl font-semibold text-gray-900">
                                {{ $sprint->tasks->where('completed', true)->count() }} / {{ $sprint->tasks->count() }}
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Équipe -->
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        Équipe
                    </h3>
                </div>
                <div class="border-t border-gray-200 px-4 py-5 sm:px-6">
                    <ul class="space-y-3">
                        @forelse($project->members as $member)
                            <li class="flex items-center">
                                <img class="h-8 w-8 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode($member->name) }}&color=7F9CF5&background=EBF4FF" alt="">
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">{{ $member->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $member->pivot->role }}</p>
                                </div>
                            </li>
                        @empty
                            <li class="text-sm text-gray-500">Aucun membre dans l'équipe</li>
                        @endforelse
                    </ul>
                </div>
            </div>

            <!-- Actions rapides -->
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        Actions rapides
                    </h3>
                </div>
                <div class="border-t border-gray-200 px-4 py-5 sm:px-6">
                    <div class="space-y-4">
                        <button type="button" class="w-full flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            Commencer le sprint
                        </button>
                        <button type="button" class="w-full flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                            Générer un rapport
                        </button>
                        <button type="button" class="w-full flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                            Exporter les données
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de création de tâche -->
<div id="taskModal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Fond gris -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

        <!-- Contenu du modal -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
            <div>
                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                        Nouvelle tâche
                    </h3>
                    <div class="mt-2">
                        <form action="{{ route('projects.sprints.tasks.store', [$project, $sprint]) }}" method="POST">
                            @csrf
                            <div class="space-y-4">
                                <div>
                                    <label for="title" class="block text-sm font-medium text-gray-700">Titre <span class="text-red-500">*</span></label>
                                    <input type="text" name="title" id="title" required
                                           class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                </div>
                                
                                <div>
                                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                                    <textarea name="description" id="description" rows="3"
                                              class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"></textarea>
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="status" class="block text-sm font-medium text-gray-700">Statut</label>
                                        <select id="status" name="status" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                            <option value="todo">À faire</option>
                                            <option value="in_progress">En cours</option>
                                            <option value="review">En revue</option>
                                            <option value="done">Terminé</option>
                                        </select>
                                    </div>
                                    
                                    <div>
                                        <label for="priority" class="block text-sm font-medium text-gray-700">Priorité</label>
                                        <select id="priority" name="priority" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                            <option value="low">Basse</option>
                                            <option value="medium" selected>Moyenne</option>
                                            <option value="high">Haute</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div>
                                    <label for="story_points" class="block text-sm font-medium text-gray-700">Points d'histoire</label>
                                    <input type="number" name="story_points" id="story_points" min="0" value="1"
                                           class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                </div>
                            </div>
                            
                            <div class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense">
                                <button type="submit"
                                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:col-start-2 sm:text-sm">
                                    Créer la tâche
                                </button>
                                <button type="button" onclick="closeTaskModal()"
                                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:col-start-1 sm:text-sm">
                                    Annuler
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    // Gestion du modal
    function openTaskModal() {
        document.getElementById('taskModal').classList.remove('hidden');
    }

    function closeTaskModal() {
        document.getElementById('taskModal').classList.add('hidden');
    }

    // Fermer le modal si on clique en dehors
    window.onclick = function(event) {
        const modal = document.getElementById('taskModal');
        if (event.target === modal) {
            closeTaskModal();
        }
    }

    // Fermer avec la touche Échap
    document.onkeydown = function(evt) {
        evt = evt || window.event;
        if (evt.key === 'Escape') {
            closeTaskModal();
        }
    };

    // Mettre à jour le statut d'une tâche
    window.updateTaskStatus = function(taskId, newStatus) {
        var url = '/projects/{{ $project->id }}/sprints/{{ $sprint->id }}/tasks/' + taskId + '/status';
        
        axios.put(url, {
            status: newStatus,
            _token: '{{ csrf_token() }}',
            _method: 'PUT'
        })
        .then(function(response) {
            // Mettre à jour l'interface utilisateur
            var taskItem = document.querySelector('.task-item[data-task-id="' + taskId + '"]');
            if (taskItem) {
                var statusButton = taskItem.querySelector('[x-data] button');
                if (statusButton) {
                    // Mettre à jour le texte du bouton
                    statusButton.textContent = newStatus.replace('_', ' ').replace(/\b\w/g, function(l) { 
                        return l.toUpperCase(); 
                    });
                    
                    // Mettre à jour les classes en fonction du nouveau statut
                    statusButton.className = 'inline-flex justify-center w-full rounded-md px-3 py-1 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-blue-500 ';
                    
                    if (newStatus === 'todo') {
                        statusButton.classList.add('bg-gray-700', 'text-gray-300', 'hover:bg-gray-600');
                    } else if (newStatus === 'in_progress') {
                        statusButton.classList.add('bg-blue-900', 'text-blue-100', 'hover:bg-blue-800');
                    } else if (newStatus === 'review') {
                        statusButton.classList.add('bg-yellow-900', 'text-yellow-100', 'hover:bg-yellow-800');
                    } else if (newStatus === 'done') {
                        statusButton.classList.add('bg-green-900', 'text-green-100', 'hover:bg-green-800');
                    }
                }
            }
            
            // Afficher une notification de succès
            window.showNotification('Statut mis à jour avec succès', 'success');
        })
        .catch(function(error) {
            console.error('Erreur lors de la mise à jour du statut:', error);
            window.showNotification('Erreur lors de la mise à jour du statut', 'error');
        });
    };

    // Assigner une tâche à un utilisateur
    window.assignTask = function(taskId, userId) {
        var url = '/projects/{{ $project->id }}/sprints/{{ $sprint->id }}/tasks/' + taskId + '/assign';
        
        // Préparer les données à envoyer
        var formData = new FormData();
        formData.append('assignee_id', userId);
        formData.append('_token', '{{ csrf_token() }}');
        formData.append('_method', 'PUT');
        
        // Envoyer la requête
        axios.post(url, formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        })
        .then(function(response) {
            // Mettre à jour l'interface utilisateur
            var taskItem = document.querySelector('.task-item[data-task-id="' + taskId + '"]');
            if (taskItem) {
                var assigneeButton = taskItem.querySelector('[x-data] button');
                var assigneeSpan = taskItem.querySelector('.assignee-avatar');
                
                if (assigneeButton) {
                    if (userId) {
                        // Trouver le membre dans la liste des membres du projet
                        var members = '@json($project->members)';
                        var member = null;
                        for (var i = 0; i < members.length; i++) {
                            if (members[i].id == userId) { // Utilisation de == au lieu de === pour la comparaison
                                member = members[i];
                                break;
                            }
                        }
                        
                        if (member) {
                            if (assigneeSpan) {
                                assigneeSpan.textContent = member.name.charAt(0).toUpperCase();
                            } else {
                                // Créer le span s'il n'existe pas
                                var newSpan = document.createElement('span');
                                newSpan.className = 'inline-flex items-center justify-center h-6 w-6 rounded-full bg-blue-500 text-xs font-medium text-white';
                                newSpan.textContent = member.name.charAt(0).toUpperCase();
                                assigneeButton.innerHTML = '';
                                assigneeButton.appendChild(newSpan);
                            }
                        }
                    } else {
                        // Si aucun utilisateur n'est assigné, afficher l'icône par défaut
                        assigneeButton.innerHTML = '\
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">\
                                <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6z" />\
                            </svg>\
                        ';
                    }
                }
            }
            
            // Afficher une notification de succès
            window.showNotification('Tâche assignée avec succès', 'success');
        })
        .catch(function(error) {
            console.error('Erreur lors de l\'assignation de la tâche:', error);
            window.showNotification('Erreur lors de l\'assignation de la tâche', 'error');
        });
    };

    // Afficher une notification
    window.showNotification = function(message, type) {
        type = type || 'success';
        var notification = document.createElement('div');
        var bgColor = type === 'success' ? 'bg-green-500' : 'bg-red-500';
        notification.className = 'fixed top-4 right-4 px-6 py-3 rounded-md shadow-lg text-white ' + bgColor;
        notification.textContent = message;
        
        document.body.appendChild(notification);
        
        // Supprimer la notification après 3 secondes
        window.setTimeout(function() {
            notification.classList.add('opacity-0');
            notification.classList.add('transition-opacity');
            notification.classList.add('duration-500');
            
            window.setTimeout(function() {
                if (notification && notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 500);
        }, 3000);
    };
</script>
@endpush
