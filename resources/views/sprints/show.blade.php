@extends('layouts.app')

@section('content')
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
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            Tâches
                        </h3>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            {{ $sprint->tasks->count() }} tâches
                        </span>
                    </div>
                </div>
                @if($sprint->tasks->isNotEmpty())
                    <ul class="divide-y divide-gray-200">
                        @foreach($sprint->tasks as $task)
                            <li class="px-4 py-4 sm:px-6 hover:bg-gray-50">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <input type="checkbox" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" 
                                               {{ $task->completed ? 'checked' : '' }}>
                                        <p class="ml-3 text-sm font-medium text-gray-900">{{ $task->title }}</p>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <span class="px-2.5 py-0.5 text-xs font-medium rounded-full 
                                            {{ $task->priority === 'high' ? 'bg-red-100 text-red-800' : 
                                               ($task->priority === 'medium' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">
                                            {{ ucfirst($task->priority) }}
                                        </span>
                                        <span class="px-2.5 py-0.5 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                                            {{ $task->story_points ?? '0' }} pts
                                        </span>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <div class="px-4 py-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Aucune tâche</h3>
                        <p class="mt-1 text-sm text-gray-500">Commencez par ajouter des tâches à ce sprint.</p>
                        <div class="mt-6">
                            <button type="button" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                                </svg>
                                Nouvelle tâche
                            </button>
                        </div>
                    </div>
                @endif
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
@endsection