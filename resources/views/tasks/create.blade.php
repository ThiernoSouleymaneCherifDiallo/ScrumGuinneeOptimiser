@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <div class="bg-gray-800 rounded-lg shadow-lg overflow-hidden">
        <div class="p-6">
            <h2 class="text-xl font-semibold text-white mb-6">Créer une nouvelle tâche</h2>
            
            <form action="{{ route('projects.tasks.store', $project) }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <label for="title" class="block text-sm font-medium text-gray-300">Titre</label>
                    <input type="text" name="title" id="title" required
                           class="mt-1 block w-full rounded-md border-gray-600 bg-gray-700 text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                           value="{{ old('title') }}">
                    @error('title')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-300">Description</label>
                    <textarea name="description" id="description" rows="3"
                              class="mt-1 block w-full rounded-md border-gray-600 bg-gray-700 text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-300">Type</label>
                        <select name="type" id="type" required
                                class="mt-1 block w-full rounded-md border-gray-600 bg-gray-700 text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="task" {{ old('type') == 'task' ? 'selected' : '' }}>Tâche</option>
                            <option value="bug" {{ old('type') == 'bug' ? 'selected' : '' }}>Bug</option>
                            <option value="feature" {{ old('type') == 'feature' ? 'selected' : '' }}>Fonctionnalité</option>
                            <option value="improvement" {{ old('type') == 'improvement' ? 'selected' : '' }}>Amélioration</option>
                        </select>
                        @error('type')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="priority" class="block text-sm font-medium text-gray-300">Priorité</label>
                        <select name="priority" id="priority" required
                                class="mt-1 block w-full rounded-md border-gray-600 bg-gray-700 text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Basse</option>
                            <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }}>Moyenne</option>
                            <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>Haute</option>
                            <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>Urgente</option>
                        </select>
                        @error('priority')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="assignee_id" class="block text-sm font-medium text-gray-300">Assigné à</label>
                        <select name="assignee_id" id="assignee_id"
                                class="mt-1 block w-full rounded-md border-gray-600 bg-gray-700 text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Non assigné</option>
                            @foreach($project->members as $member)
                                <option value="{{ $member->id }}" {{ old('assignee_id') == $member->id ? 'selected' : '' }}>
                                    {{ $member->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('assignee_id')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="due_date" class="block text-sm font-medium text-gray-300">Date d'échéance</label>
                        <input type="date" name="due_date" id="due_date"
                               class="mt-1 block w-full rounded-md border-gray-600 bg-gray-700 text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                               value="{{ old('due_date') }}">
                        @error('due_date')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex justify-end space-x-3">
                    <a href="{{ route('projects.show', $project) }}"
                       class="px-4 py-2 border border-gray-600 text-gray-300 rounded-md hover:bg-gray-700">
                        Annuler
                    </a>
                    <button type="submit"
                            class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                        Créer la tâche
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection