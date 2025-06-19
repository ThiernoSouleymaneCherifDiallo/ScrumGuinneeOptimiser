@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <!-- En-tête de la tâche -->
    <div class="bg-gray-800 rounded-lg shadow-lg overflow-hidden mb-6">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center space-x-4">
                    <div class="flex-shrink-0">
                        <div class="h-12 w-12 rounded-full flex items-center justify-center text-white text-lg font-semibold
                            @switch($task->priority)
                                @case('urgent') bg-red-500
                                @case('high') bg-orange-500
                                @case('medium') bg-yellow-500
                                @default bg-green-500
                            @endswitch">
                            {{ strtoupper(substr($task->title, 0, 1)) }}
                        </div>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-white">{{ $task->title }}</h1>
                        <p class="text-gray-400">Projet: {{ $project->name }}</p>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('projects.show', $project) }}" 
                       class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                        ← Retour au projet
                    </a>
                    <a href="{{ route('tasks.comments.index', [$project, $task]) }}" 
                       class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                        💬 Commentaires
                        @if($task->comments()->count() > 0)
                            <span class="bg-blue-800 text-white text-xs px-2 py-1 rounded-full ml-1">
                                {{ $task->comments()->count() }}
                            </span>
                        @endif
                    </a>
                </div>
            </div>

            <!-- Informations de la tâche -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-gray-700 rounded-lg p-4">
                    <h3 class="text-sm font-medium text-gray-300 mb-2">Statut</h3>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                        @switch($task->status)
                            @case('todo') bg-gray-700 text-gray-300
                            @case('in_progress') bg-blue-900 text-blue-200
                            @case('review') bg-yellow-900 text-yellow-200
                            @default bg-green-900 text-green-200
                        @endswitch">
                        @switch($task->status)
                            @case('todo') À faire
                            @case('in_progress') En cours
                            @case('review') En révision
                            @case('done') Terminé
                            @default {{ ucfirst($task->status) }}
                        @endswitch
                    </span>
                </div>

                <div class="bg-gray-700 rounded-lg p-4">
                    <h3 class="text-sm font-medium text-gray-300 mb-2">Priorité</h3>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                        @switch($task->priority)
                            @case('urgent') bg-red-900 text-red-200
                            @case('high') bg-orange-900 text-orange-200
                            @case('medium') bg-yellow-900 text-yellow-200
                            @default bg-green-900 text-green-200
                        @endswitch">
                        @switch($task->priority)
                            @case('urgent') Urgente
                            @case('high') Haute
                            @case('medium') Moyenne
                            @case('low') Basse
                            @default {{ ucfirst($task->priority) }}
                        @endswitch
                    </span>
                </div>

                <div class="bg-gray-700 rounded-lg p-4">
                    <h3 class="text-sm font-medium text-gray-300 mb-2">Type</h3>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-900 text-purple-200">
                        {{ ucfirst($task->type) }}
                    </span>
                </div>

                <div class="bg-gray-700 rounded-lg p-4">
                    <h3 class="text-sm font-medium text-gray-300 mb-2">Points</h3>
                    <span class="text-white font-semibold">
                        {{ $task->story_points ?? 'Non défini' }}
                    </span>
                </div>
            </div>

            <!-- Description -->
            @if($task->description)
            <div class="mt-6">
                <h3 class="text-lg font-medium text-white mb-3">Description</h3>
                <div class="bg-gray-700 rounded-lg p-4">
                    <p class="text-gray-300 leading-relaxed">{{ $task->description }}</p>
                </div>
            </div>
            @endif

            <!-- Informations supplémentaires -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                <div class="bg-gray-700 rounded-lg p-4">
                    <h3 class="text-sm font-medium text-gray-300 mb-2">Assigné à</h3>
                    @if($task->assignee)
                        <div class="flex items-center space-x-2">
                            <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white text-sm font-semibold">
                                {{ strtoupper(substr($task->assignee->name, 0, 1)) }}
                            </div>
                            <span class="text-white">{{ $task->assignee->name }}</span>
                        </div>
                    @else
                        <span class="text-gray-400">Non assigné</span>
                    @endif
                </div>

                <div class="bg-gray-700 rounded-lg p-4">
                    <h3 class="text-sm font-medium text-gray-300 mb-2">Rapporteur</h3>
                    @if($task->reporter)
                        <div class="flex items-center space-x-2">
                            <div class="w-8 h-8 bg-gradient-to-r from-green-500 to-blue-600 rounded-full flex items-center justify-center text-white text-sm font-semibold">
                                {{ strtoupper(substr($task->reporter->name, 0, 1)) }}
                            </div>
                            <span class="text-white">{{ $task->reporter->name }}</span>
                        </div>
                    @else
                        <span class="text-gray-400">Non défini</span>
                    @endif
                </div>

                <div class="bg-gray-700 rounded-lg p-4">
                    <h3 class="text-sm font-medium text-gray-300 mb-2">Sprint</h3>
                    @if($task->sprint)
                        <span class="text-white">{{ $task->sprint->name }}</span>
                    @else
                        <span class="text-purple-400">Backlog</span>
                    @endif
                </div>
            </div>

            <!-- Échéance -->
            @if($task->due_date)
            <div class="mt-6">
                <div class="bg-gray-700 rounded-lg p-4">
                    <h3 class="text-sm font-medium text-gray-300 mb-2">Échéance</h3>
                    <div class="flex items-center space-x-2">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="text-white {{ $task->due_date < now() ? 'text-red-400 font-semibold' : '' }}">
                            {{ $task->due_date->format('d/m/Y') }}
                            @if($task->due_date < now())
                                (En retard !)
                            @endif
                        </span>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Commentaires récents -->
    <div class="bg-gray-800 rounded-lg shadow-lg overflow-hidden">
        <div class="p-6 border-b border-gray-700">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold text-white">Commentaires récents</h2>
                <a href="{{ route('tasks.comments.index', [$project, $task]) }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                    Voir tous les commentaires
                </a>
            </div>
        </div>

        <div class="p-6">
            @forelse($task->comments()->with(['user', 'replies.user'])->latest()->take(3)->get() as $comment)
                <div class="bg-gray-700 rounded-lg p-4 mb-4 border border-gray-600">
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white text-sm font-semibold">
                                {{ strtoupper(substr($comment->user->name, 0, 1)) }}
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center space-x-2">
                                    <span class="font-medium text-white">{{ $comment->user->name }}</span>
                                    <span class="text-xs text-gray-400">{{ $comment->created_at->format('d/m/Y H:i') }}</span>
                                </div>
                            </div>
                            <div class="text-gray-300 leading-relaxed">
                                {{ Str::limit($comment->content, 200) }}
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-8">
                    <div class="text-gray-400 mb-4">
                        <svg class="mx-auto h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-white mb-2">Aucun commentaire</h3>
                    <p class="text-gray-400">Soyez le premier à commenter cette tâche !</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection 