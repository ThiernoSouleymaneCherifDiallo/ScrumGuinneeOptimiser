@extends('layouts.app')

@section('content')
@if(session('success'))
    <div class="mb-6 rounded-lg bg-emerald-900/50 border border-emerald-700/50 p-4 backdrop-blur-sm">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-emerald-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium text-emerald-200">
                    {{ session('success') }}
                </p>
            </div>
        </div>
    </div>
@endif

<div class="min-h-screen bg-[#1c1c1e] text-slate-100">
    <div class="container mx-auto px-4 py-8">
        <!-- En-tête avec navigation -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <!-- Breadcrumb -->
                    <nav class="flex mb-4" aria-label="Breadcrumb">
                        <ol class="flex items-center space-x-3">
                            <li>
                                <a href="{{ route('projects.show', $project) }}" class="flex items-center text-slate-400 hover:text-slate-200 transition-colors duration-200">
                                    <svg class="h-4 w-4 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                                    </svg>
                                    Projet
                                </a>
                            </li>
                            <li>
                                <div class="flex items-center">
                                    <svg class="flex-shrink-0 h-4 w-4 text-slate-500 mx-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                    </svg>
                                    <a href="{{ route('projects.sprints.index', $project) }}" class="text-slate-400 hover:text-slate-200 transition-colors duration-200">Sprints</a>
                                </div>
                            </li>
                            <li>
                                <div class="flex items-center">
                                    <svg class="flex-shrink-0 h-4 w-4 text-slate-500 mx-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                    </svg>
                                    <span class="text-slate-300 font-medium">{{ $sprint->name }}</span>
                                </div>
                            </li>
                        </ol>
                    </nav>
                    
                    <!-- Titre et métadonnées -->
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                        <div class="flex-1 min-w-0">
                            <h1 class="text-3xl font-bold text-white mb-3">{{ $sprint->name }}</h1>
                            <div class="flex flex-wrap items-center gap-6">
                                <div class="flex items-center text-slate-300">
                                    <svg class="flex-shrink-0 mr-2 h-4 w-4 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                                    </svg>
                                    @if($sprint->start_date && $sprint->end_date)
                                        {{ $sprint->start_date->format('d M Y') }} - {{ $sprint->end_date->format('d M Y') }}
                                        <span class="ml-2 text-slate-400">({{ $sprint->start_date->diffInDays($sprint->end_date) + 1 }} jours)</span>
                                    @else
                                        Dates non définies
                                    @endif
                                </div>
                                <div class="flex items-center">
                                    <span class="px-3 py-1 inline-flex text-xs font-semibold rounded-full 
                                        @if($sprint->status === 'active')
                                            bg-emerald-900/50 text-emerald-300 border border-emerald-700/50
                                        @elseif($sprint->status === 'completed')
                                            bg-slate-700/50 text-slate-300 border border-slate-600/50
                                        @else
                                            bg-blue-900/50 text-blue-300 border border-blue-700/50
                                        @endif">
                                        @if($sprint->status === 'active')
                                            🔄 EN COURS
                                        @elseif($sprint->status === 'completed')
                                            ✅ TERMINÉ
                                        @else
                                            📅 PLANIFIÉ
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Boutons d'action -->
                        <div class="mt-6 lg:mt-0 flex flex-wrap gap-3">
                            <a href="{{ route('projects.sprints.summary', [$project, $sprint]) }}"
                               class="inline-flex items-center px-4 py-2 bg-purple-600 border border-purple-500 rounded-lg text-sm font-medium text-white hover:bg-purple-700 hover:border-purple-600 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 focus:ring-offset-slate-900 shadow-lg shadow-purple-500/25">
                                <svg class="mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z" />
                                    <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z" />
                                </svg>
                                Résumé
                            </a>
                            <a href="{{ route('projects.sprints.edit', [$project, $sprint]) }}"
                               class="inline-flex items-center px-4 py-2 bg-slate-800 border border-slate-700 rounded-lg text-sm font-medium text-slate-200 hover:bg-slate-700 hover:border-slate-600 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-slate-900">
                                <svg class="mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                </svg>
                                Modifier
                            </a>
                            <button type="button" 
                                    onclick="openTaskModal()"
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-blue-500 rounded-lg text-sm font-medium text-white hover:bg-blue-700 hover:border-blue-600 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-slate-900 shadow-lg shadow-blue-500/25">
                                <svg class="mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                                </svg>
                                Nouvelle tâche
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Colonne principale -->
            <div class="lg:col-span-2 flex flex-col gap-8">
                <!-- Objectif du sprint -->
                <div class="bg-[#23272f] rounded-2xl shadow-lg border border-[#23272f] p-8">
                    <h3 class="text-2xl font-bold text-[#2684ff] mb-2">Objectif du sprint</h3>
                    <p class="text-base text-slate-400 mb-6">Ce que l'équipe prévoit d'accomplir pendant ce sprint.</p>
                    <div>
                        @if($sprint->goal)
                            <p class="text-lg text-slate-200 whitespace-pre-line">{{ $sprint->goal }}</p>
                        @else
                            <p class="text-slate-500 italic">Aucun objectif défini pour ce sprint.</p>
                        @endif
                    </div>
                </div>
                <!-- Tâches du sprint -->
                <div class="bg-[#23272f] rounded-2xl shadow-lg border border-[#23272f] p-6">
                    <h3 class="text-xl font-bold text-[#2684ff] mb-4">Tâches du sprint</h3>
                    <div class="bg-[#23272f]">
                        <ul class="divide-y divide-[#31343b]" id="tasks-list">
                            @forelse($sprint->tasks as $task)
                                <li class="px-4 py-4 hover:bg-[#232b36] transition-colors duration-150 task-item rounded-xl mb-2" data-task-id="{{ $task->id }}">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center">
                                                <!-- Menu déroulant pour le statut -->
                                                <div class="relative inline-block text-left mr-3" x-data="{ open: false }">
                                                    <div>
                                                        <button type="button" 
                                                                @click="open = !open"
                                                                class="inline-flex justify-center w-full rounded-md px-3 py-1 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-[#23272f] focus:ring-blue-500
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
                                                         class="origin-top-right absolute left-0 mt-2 w-40 rounded-md shadow-lg bg-[#31343b] ring-1 ring-black ring-opacity-5 focus:outline-none z-10">
                                                        <div class="py-1">
                                                            @foreach(['todo', 'in_progress', 'review', 'done'] as $status)
                                                                <button type="button" 
                                                                        @click="updateTaskStatus({{ $task->id }}, '{{ $status }}'); open = false;"
                                                                        class="w-full text-left px-4 py-2 text-sm {{ $task->status === $status ? 'bg-[#232b36] text-white' : 'text-slate-200 hover:bg-[#232b36]' }}">
                                                                    {{ ucfirst(str_replace('_', ' ', $status)) }}
                                                                </button>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <span class="ml-2 text-base font-medium text-slate-100">{{ $task->title }}</span>
                                            </div>
                                            @if($task->description)
                                                <p class="mt-1 text-sm text-slate-400 truncate">{{ $task->description }}</p>
                                            @endif
                                        </div>
                                        
                                        <div class="flex items-center space-x-2">
                                            <!-- Menu déroulant pour l'assignation -->
                                            <div class="relative inline-block text-left" x-data="{ open: false }">
                                                <div>
                                                    <button type="button" 
                                                            @click="open = !open"
                                                            class="assignee-button flex items-center justify-center w-8 h-8 rounded-full bg-[#31343b] text-slate-300 hover:bg-[#232b36] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-[#23272f] focus:ring-blue-500">
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
                                                     class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-[#31343b] ring-1 ring-black ring-opacity-5 focus:outline-none z-10">
                                                    <div class="py-1">
                                                        <div class="px-4 py-2 text-xs text-slate-400 border-b border-[#232b36]">
                                                            Assigner à
                                                        </div>
                                                        @foreach($project->members as $member)
                                                            <button type="button" 
                                                                    @click="assignTask({{ $task->id }}, {{ $member->id }}); open = false;"
                                                                    class="w-full text-left px-4 py-2 text-sm flex items-center space-x-2 {{ $task->assignee_id === $member->id ? 'bg-[#232b36] text-white' : 'text-slate-200 hover:bg-[#232b36]' }}">
                                                                <span class="inline-flex items-center justify-center h-6 w-6 rounded-full bg-blue-500 text-xs font-medium text-white">
                                                                    {{ substr($member->name, 0, 1) }}
                                                                </span>
                                                                <span>{{ $member->name }}</span>
                                                            </button>
                                                        @endforeach
                                                        @if($task->assignee_id)
                                                            <div class="border-t border-[#232b36]">
                                                                <button type="button"
                                                                        @click="assignTask({{ $task->id }}, null); open = false;"
                                                                        class="w-full text-left px-4 py-2 text-sm text-red-400 hover:bg-[#232b36]">
                                                                    Ne pas assigner
                                                                </button>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            @if($task->story_points)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-700 text-gray-300">{{ $task->story_points }} pts</span>
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
                                <li class="px-4 py-4 text-center text-slate-500">Aucune tâche pour ce sprint.</li>
                            @endforelse
                        </ul>
                    </div>
                    @if(auth()->user()->can('create', [\App\Models\Task::class, $project]))
                        <div class="px-4 py-3 bg-[#232b36] text-right sm:px-6 mt-4 rounded-lg">
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
            <!-- Colonne latérale -->
            <div class="flex flex-col gap-8">
                <!-- Métriques du sprint -->
                <div class="bg-[#23272f] rounded-2xl shadow-lg border border-[#23272f] p-6">
                    <h3 class="text-lg font-bold text-[#2684ff] mb-4">Métriques du sprint</h3>
                    
                    <!-- Progression générale -->
                    <div class="mb-6">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-medium text-slate-300">Progression</span>
                            <span class="text-sm text-slate-400">{{ $sprint->tasks->where('status', 'done')->count() }}/{{ $sprint->tasks->count() }} tâches</span>
                        </div>
                        <div class="w-full bg-[#31343b] rounded-full h-2">
                            @php
                                $progressPercentage = $sprint->tasks->count() > 0 ? ($sprint->tasks->where('status', 'done')->count() / $sprint->tasks->count()) * 100 : 0;
                            @endphp
                            <div class="bg-gradient-to-r from-blue-500 to-green-500 h-2 rounded-full transition-all duration-300" style="width: {{ $progressPercentage }}%"></div>
                        </div>
                        <div class="text-xs text-slate-400 mt-1">{{ number_format($progressPercentage, 1) }}% complété</div>
                    </div>

                    <!-- Métriques principales -->
                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div class="bg-[#232b36] rounded-lg p-4">
                            <div class="flex items-center">
                                <div class="p-2 bg-blue-900/50 rounded-lg">
                                    <svg class="w-4 h-4 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-xs font-medium text-slate-400">Vélocité</p>
                                    <p class="text-lg font-bold text-slate-100">{{ $sprint->tasks->sum('story_points') }} pts</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-[#232b36] rounded-lg p-4">
                            <div class="flex items-center">
                                <div class="p-2 bg-green-900/50 rounded-lg">
                                    <svg class="w-4 h-4 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-xs font-medium text-slate-400">Terminées</p>
                                    <p class="text-lg font-bold text-slate-100">{{ $sprint->tasks->where('status', 'done')->count() }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Répartition par statut -->
                    <div class="mb-6">
                        <h4 class="text-sm font-medium text-slate-300 mb-3">Répartition par statut</h4>
                        <div class="space-y-2">
                            @php
                                $statuses = [
                                    'todo' => ['label' => 'À faire', 'color' => 'bg-gray-500', 'text' => 'text-gray-300'],
                                    'in_progress' => ['label' => 'En cours', 'color' => 'bg-blue-500', 'text' => 'text-blue-300'],
                                    'review' => ['label' => 'En revue', 'color' => 'bg-yellow-500', 'text' => 'text-yellow-300'],
                                    'done' => ['label' => 'Terminé', 'color' => 'bg-green-500', 'text' => 'text-green-300']
                                ];
                            @endphp
                            
                            @foreach($statuses as $status => $config)
                                @php
                                    $count = $sprint->tasks->where('status', $status)->count();
                                    $percentage = $sprint->tasks->count() > 0 ? ($count / $sprint->tasks->count()) * 100 : 0;
                                @endphp
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="w-3 h-3 {{ $config['color'] }} rounded-full mr-2"></div>
                                        <span class="text-xs {{ $config['text'] }}">{{ $config['label'] }}</span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <span class="text-xs text-slate-400">{{ $count }}</span>
                                        <span class="text-xs text-slate-500">({{ number_format($percentage, 1) }}%)</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Temps restant -->
                    @if($sprint->start_date && $sprint->end_date)
                        @php
                            $now = now();
                            $totalDays = $sprint->start_date->diffInDays($sprint->end_date) + 1;
                            $elapsedDays = $sprint->start_date->diffInDays($now) + 1;
                            $remainingDays = max(0, $totalDays - $elapsedDays);
                            $elapsedPercentage = min(100, max(0, ($elapsedDays / $totalDays) * 100));
                        @endphp
                        <div class="border-t border-[#31343b] pt-4">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-medium text-slate-300">Temps restant</span>
                                <span class="text-sm text-slate-400">{{ $remainingDays }} jours</span>
                            </div>
                            <div class="w-full bg-[#31343b] rounded-full h-2">
                                <div class="bg-gradient-to-r from-orange-500 to-red-500 h-2 rounded-full transition-all duration-300" style="width: {{ $elapsedPercentage }}%"></div>
                            </div>
                            <div class="text-xs text-slate-400 mt-1">
                                {{ $elapsedDays }}/{{ $totalDays }} jours écoulés
                            </div>
                        </div>
                    @endif
                </div>
                <!-- Équipe -->
                <div class="bg-[#23272f] rounded-2xl shadow-lg border border-[#23272f] p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-[#2684ff]">Équipe</h3>
                        <a href="{{ route('projects.members.create', $project) }}" 
                           class="inline-flex items-center p-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-[#23272f] transition-colors duration-200">
                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    </div>
                    <ul class="space-y-3">
                        @forelse($project->members as $member)
                            <li class="flex items-center">
                                <img class="h-8 w-8 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode($member->name) }}&color=7F9CF5&background=23272f" alt="">
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-slate-100">{{ $member->name }}</p>
                                    <p class="text-xs text-slate-400">{{ $member->pivot->role }}</p>
                                </div>
                            </li>
                        @empty
                            <li class="text-sm text-slate-500">Aucun membre dans l'équipe</li>
                        @endforelse
                    </ul>
                </div>
                <!-- Actions rapides -->
                <div class="bg-[#23272f] rounded-2xl shadow-lg border border-[#23272f] p-6 flex flex-col gap-4">
                    <h3 class="text-lg font-bold text-[#2684ff] mb-4">Actions rapides</h3>
                    <button type="button" class="w-full flex items-center justify-center px-4 py-2 rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition">Commencer le sprint</button>
                    <button type="button" class="w-full flex items-center justify-center px-4 py-2 rounded-md shadow-sm text-sm font-medium text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition">Générer un rapport</button>
                    <button type="button" class="w-full flex items-center justify-center px-4 py-2 rounded-md shadow-sm text-sm font-medium text-slate-100 bg-[#232b36] hover:bg-[#31343b] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition">Exporter les données</button>
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
<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
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
            _token: '{{ csrf_token() }}'
        })
        .then(function(response) {
            // Mettre à jour l'interface utilisateur
            var taskItem = document.querySelector('.task-item[data-task-id="' + taskId + '"]');
            if (taskItem) {
                var statusButton = taskItem.querySelector('button');
                if (statusButton) {
                    // Mettre à jour le texte du bouton
                    var statusText = statusButton.querySelector('span') || statusButton;
                    statusText.textContent = newStatus.replace('_', ' ').replace(/\b\w/g, function(l) { 
                        return l.toUpperCase(); 
                    });
                    
                    // Mettre à jour les classes en fonction du nouveau statut
                    statusButton.className = 'inline-flex justify-center w-full rounded-md px-3 py-1 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-[#23272f] focus:ring-blue-500 ';
                    
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
        
        axios.put(url, {
            assignee_id: userId,
            _token: '{{ csrf_token() }}'
        })
        .then(function(response) {
            // Mettre à jour l'interface utilisateur
            var taskItem = document.querySelector('.task-item[data-task-id="' + taskId + '"]');
            if (taskItem) {
                // Cibler spécifiquement le bouton d'assignation avec sa classe
                var assigneeButton = taskItem.querySelector('.assignee-button');
                
                if (assigneeButton) {
                    if (userId) {
                        // Trouver le membre dans la liste des membres du projet
                        var members = @json($project->members);
                        var member = null;
                        for (var i = 0; i < members.length; i++) {
                            if (members[i].id == userId) {
                                member = members[i];
                                break;
                            }
                        }
                        
                        if (member) {
                            assigneeButton.innerHTML = '<span class="inline-flex items-center justify-center h-6 w-6 rounded-full bg-blue-500 text-xs font-medium text-white">' + member.name.charAt(0).toUpperCase() + '</span>';
                        }
                    } else {
                        // Si aucun utilisateur n'est assigné, afficher l'icône par défaut
                        assigneeButton.innerHTML = '<svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6z" /></svg>';
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
        notification.className = 'fixed top-4 right-4 px-6 py-3 rounded-md shadow-lg text-white ' + bgColor + ' z-50';
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
