@extends('layouts.app')

@section('header')
    {{ $project->name }}
@endsection

@section('content')
<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Informations principales du projet -->
        <div class="lg:col-span-3">
            <div class="bg-gray-800 rounded-lg shadow-lg overflow-hidden">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <h1 class="text-2xl font-bold text-white flex items-center">
                                {{ $project->name }}
                                <span class="ml-3 px-2 py-1 text-xs font-semibold bg-gray-700 text-gray-300 rounded">
                                    {{ $project->key }}
                                </span>
                            </h1>
                            <p class="mt-2 text-gray-400">
                                {{ $project->description }}
                            </p>
                        </div>
                        @can('update', $project)
                        <div class="flex space-x-2">
                            <a href="{{ route('projects.edit', $project) }}" 
                               class="px-4 py-2 bg-gray-700 text-white rounded-md hover:bg-gray-600 transition-colors">
                                Modifier
                            </a>
                        </div>
                        @endcan
                    </div>

                    <!-- Statistiques rapides -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                        <div class="bg-gray-700 p-4 rounded-lg">
                            <h3 class="text-sm font-medium text-gray-400">Tâches totales</h3>
                            <p class="text-2xl font-semibold text-white">0</p>
                        </div>
                        <div class="bg-gray-700 p-4 rounded-lg">
                            <h3 class="text-sm font-medium text-gray-400">En cours</h3>
                            <p class="text-2xl font-semibold text-white">0</p>
                        </div>
                        <div class="bg-gray-700 p-4 rounded-lg">
                            <h3 class="text-sm font-medium text-gray-400">Terminées</h3>
                            <p class="text-2xl font-semibold text-white">0</p>
                        </div>
                        <div class="bg-gray-700 p-4 rounded-lg">
                            <h3 class="text-sm font-medium text-gray-400">Membres</h3>
                            <p class="text-2xl font-semibold text-white">{{ $project->members->count() }}</p>
                        </div>
                    </div>

                    <!-- Actions rapides -->
                    <div class="flex space-x-4">
                        <button class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition-colors">
                            Nouvelle tâche
                        </button>
                        <button class="px-4 py-2 bg-gray-700 text-white rounded-md hover:bg-gray-600 transition-colors">
                            Nouveau sprint
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <!-- Informations du projet -->
            <div class="bg-gray-800 rounded-lg shadow-lg overflow-hidden mb-6">
                <div class="p-4">
                    <h2 class="text-lg font-semibold text-white mb-4">Détails</h2>
                    <dl class="space-y-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-400">Propriétaire</dt>
                            <dd class="mt-1 text-sm text-white">{{ $project->owner->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-400">Créé le</dt>
                            <dd class="mt-1 text-sm text-white">{{ $project->created_at->format('d/m/Y') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-400">Statut</dt>
                            <dd class="mt-1">
                                <span class="px-2 py-1 text-xs font-semibold bg-green-500 bg-opacity-20 text-green-400 rounded-full">
                                    {{ ucfirst($project->status) }}
                                </span>
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Membres du projet -->
            <div class="bg-gray-800 rounded-lg shadow-lg overflow-hidden">
                <div class="p-4">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-lg font-semibold text-white">Membres</h2>
                        @can('update', $project)
                        <button class="text-sm text-indigo-400 hover:text-indigo-300">
                            Ajouter
                        </button>
                        @endcan
                    </div>
                    <ul class="space-y-3">
                        @foreach($project->members as $member)
                        <li class="flex items-center justify-between">
                            <div class="flex items-center">
                                <span class="inline-block h-8 w-8 rounded-full bg-gray-700 text-white text-center leading-8">
                                    {{ substr($member->name, 0, 1) }}
                                </span>
                                <span class="ml-3 text-sm text-white">{{ $member->name }}</span>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection