@extends('layouts.app')

@section('header')
    Créer un nouveau projet
@endsection

@section('content')
<div class="max-w-2xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <div class="bg-gray-800 shadow-lg rounded-lg overflow-hidden">
        <div class="p-6">
            <h2 class="text-xl font-semibold text-white mb-6">Nouveau Projet</h2>
            
            <form action="{{ route('projects.store') }}" method="POST" class="space-y-6">
                @csrf
                
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-300">Nom du projet</label>
                    <input type="text" name="name" id="name" 
                           class="mt-1 block w-full rounded-md border-gray-600 bg-gray-700 text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                           value="{{ old('name') }}" required>
                    @error('name')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="key" class="block text-sm font-medium text-gray-300">Clé du projet</label>
                    <div class="mt-1 flex rounded-md shadow-sm">
                        <input type="text" name="key" id="key" 
                               class="block w-full rounded-md border-gray-600 bg-gray-700 text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 uppercase"
                               value="{{ old('key') }}" required
                               placeholder="PROJ">
                    </div>
                    <p class="mt-1 text-sm text-gray-400">La clé doit être unique (ex: PROJ, SCRUM)</p>
                    @error('key')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-300">Description</label>
                    <textarea name="description" id="description" rows="4"
                              class="mt-1 block w-full rounded-md border-gray-600 bg-gray-700 text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end space-x-3">
                    <a href="{{ route('projects.index') }}" 
                       class="px-4 py-2 border border-gray-600 text-gray-300 rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-indigo-500">
                        Annuler
                    </a>
                    <button type="submit" 
                            class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-indigo-500">
                        Créer le projet
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection