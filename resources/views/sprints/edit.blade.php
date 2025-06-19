@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 bg-gray-900 min-h-screen">
    <div class="max-w-4xl mx-auto">
        <div class="bg-gray-800 rounded-lg shadow-md p-6 border border-gray-700">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-semibold text-white">Modifier le sprint</h1>
                <div class="flex space-x-2">
                    <a href="{{ route('projects.sprints.index', $project) }}" 
                       class="px-4 py-2 border border-gray-600 rounded-md text-sm font-medium text-gray-300 hover:bg-gray-700">
                        Annuler
                    </a>
                    <button type="submit" form="sprintForm" 
                            class="px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Enregistrer
                    </button>
                </div>
            </div>

            <form id="sprintForm" action="{{ route('projects.sprints.update', [$project, $sprint]) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Colonne de gauche -->
                    <div class="space-y-6">
                        <!-- Nom du sprint -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-300">Nom du sprint</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $sprint->name) }}"
                                   class="mt-1 block w-full border border-gray-600 rounded-md shadow-sm py-2 px-3 bg-gray-700 text-white focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                   required>
                            @error('name')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Objectif du sprint -->
                        <div>
                            <label for="goal" class="block text-sm font-medium text-gray-300">Objectif du sprint</label>
                            <textarea name="goal" id="goal" rows="3"
                                      class="mt-1 block w-full border border-gray-600 rounded-md shadow-sm py-2 px-3 bg-gray-700 text-white focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">{{ old('goal', $sprint->goal) }}</textarea>
                            <p class="mt-1 text-xs text-gray-400">Décrivez l'objectif de ce sprint</p>
                            @error('goal')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Colonne de droite -->
                    <div class="space-y-6">
                        <!-- Dates du sprint -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="start_date" class="block text-sm font-medium text-gray-300">Date de début</label>
                                <input type="date" name="start_date" id="start_date" 
                                       value="{{ old('start_date', $sprint->start_date ? $sprint->start_date->format('Y-m-d') : '') }}"
                                       class="mt-1 block w-full border border-gray-600 rounded-md shadow-sm py-2 px-3 bg-gray-700 text-white focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                @error('start_date')
                                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="end_date" class="block text-sm font-medium text-gray-300">Date de fin</label>
                                <input type="date" name="end_date" id="end_date"
                                       value="{{ old('end_date', $sprint->end_date ? $sprint->end_date->format('Y-m-d') : '') }}"
                                       class="mt-1 block w-full border border-gray-600 rounded-md shadow-sm py-2 px-3 bg-gray-700 text-white focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                @error('end_date')
                                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Statut du sprint -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-300">Statut</label>
                            <select id="status" name="status" 
                                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-600 bg-gray-700 text-white focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                <option value="planned" {{ old('status', $sprint->status) === 'planned' ? 'selected' : '' }}>Planifié</option>
                                <option value="active" {{ old('status', $sprint->status) === 'active' ? 'selected' : '' }}>Actif</option>
                                <option value="completed" {{ old('status', $sprint->status) === 'completed' ? 'selected' : '' }}>Terminé</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </form>

            <!-- Section de suppression -->
            <div class="mt-8 pt-6 border-t border-gray-700">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-medium text-red-400">Zone dangereuse</h3>
                        <p class="text-sm text-gray-400">La suppression est irréversible. Soyez certain de votre action.</p>
                    </div>
                    <form action="{{ route('projects.sprints.destroy', [$project, $sprint]) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce sprint ? Cette action est irréversible.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            Supprimer le sprint
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Validation des dates
    document.addEventListener('DOMContentLoaded', function() {
        const startDateInput = document.getElementById('start_date');
        const endDateInput = document.getElementById('end_date');

        if (startDateInput && endDateInput) {
            startDateInput.addEventListener('change', function() {
                endDateInput.min = this.value;
            });
        }
    });
</script>
@endpush
@endsection 