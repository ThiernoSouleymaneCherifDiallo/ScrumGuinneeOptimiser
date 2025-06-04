@extends('layouts.app')

@section('header')
    Projets
@endsection

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-white">Mes Projets</h1>
        <a href="{{ route('projects.create') }}" 
           class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
            Nouveau Projet
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse ($projects as $project)
            <div class="bg-gray-800 rounded-lg shadow-lg overflow-hidden">
                <div class="p-6">
                    <div class="flex justify-between items-start">
                        <h3 class="text-lg font-semibold text-white">
                            <a href="{{ route('projects.show', $project) }}" class="hover:text-indigo-400">
                                {{ $project->name }}
                            </a>
                        </h3>
                        <span class="px-2 py-1 text-xs font-semibold bg-gray-700 text-gray-300 rounded">
                            {{ $project->key }}
                        </span>
                    </div>
                    <p class="mt-2 text-gray-400">
                        {{ Str::limit($project->description, 100) }}
                    </p>
                    <div class="mt-4 flex items-center justify-between">
                        <div class="flex items-center text-sm text-gray-400">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            {{ $project->owner->name }}
                        </div>
                        <div class="flex space-x-2">
                            @can('update', $project)
                                <a href="{{ route('projects.edit', $project) }}" 
                                   class="text-gray-400 hover:text-white">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-3 bg-gray-800 rounded-lg p-8 text-center">
                <p class="text-gray-400">Vous n'avez pas encore de projets.</p>
                <a href="{{ route('projects.create') }}" 
                   class="mt-4 inline-block px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                    Créer mon premier projet
                </a>
            </div>
        @endforelse
    </div>
</div>
@endsection