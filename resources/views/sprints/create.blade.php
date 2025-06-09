@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-semibold text-gray-800">Nouveau sprint</h1>
                <a href="{{ route('projects.sprints.index', $project) }}" 
                   class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                    Annuler
                </a>
            </div>

            <form action="{{ route('projects.sprints.store', $project) }}" method="POST" class="space-y-6">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Colonne de gauche -->
                    <div class="space-y-6">
                        <!-- Nom du sprint -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Nom du sprint <span class="text-red-500">*</span></label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" 
                                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                   placeholder="Ex: Sprint 1 - Mise en place" required>
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Objectif du sprint -->
                        <div>
                            <label for="goal" class="block text-sm font-medium text-gray-700">Objectif du sprint</label>
                            <textarea name="goal" id="goal" rows="4"
                                      class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                      placeholder="Décrivez l'objectif principal de ce sprint">{{ old('goal') }}</textarea>
                            <p class="mt-1 text-xs text-gray-500">Quel est l'objectif principal de ce sprint ?</p>
                            @error('goal')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Colonne de droite -->
                    <div class="space-y-6">
                        <!-- Dates du sprint -->
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <label for="start_date" class="block text-sm font-medium text-gray-700">Date de début</label>
                                <input type="date" name="start_date" id="start_date" 
                                       value="{{ old('start_date') }}"
                                       class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                @error('start_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="end_date" class="block text-sm font-medium text-gray-700">Date de fin</label>
                                <input type="date" name="end_date" id="end_date"
                                       value="{{ old('end_date') }}"
                                       class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                @error('end_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Durée du sprint -->
                        <div class="p-4 bg-blue-50 rounded-md">
                            <p class="text-sm text-blue-700">
                                <span id="sprint-duration">0</span> jours
                                <span id="sprint-dates" class="text-blue-600 ml-2"></span>
                            </p>
                        </div>

                        <!-- Statut du sprint -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700">Statut</label>
                            <select id="status" name="status" 
                                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                <option value="planned" {{ old('status', 'planned') === 'planned' ? 'selected' : '' }}>Planifié</option>
                                <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Actif</option>
                                <option value="completed" {{ old('status') === 'completed' ? 'selected' : '' }}>Terminé</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="pt-6 border-t border-gray-200">
                    <div class="flex justify-end">
                        <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Créer le sprint
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const startDateInput = document.getElementById('start_date');
        const endDateInput = document.getElementById('end_date');
        const durationSpan = document.getElementById('sprint-duration');
        const datesSpan = document.getElementById('sprint-dates');

        function updateDuration() {
            if (startDateInput.value && endDateInput.value) {
                const startDate = new Date(startDateInput.value);
                const endDate = new Date(endDateInput.value);
                
                // Calcul du nombre de jours (inclusif)
                const diffTime = Math.abs(endDate - startDate);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
                
                durationSpan.textContent = diffDays;
                
                // Formatage des dates pour l'affichage
                const options = { year: 'numeric', month: 'short', day: 'numeric' };
                const startFormatted = startDate.toLocaleDateString('fr-FR', options);
                const endFormatted = endDate.toLocaleDateString('fr-FR', options);
                
                datesSpan.textContent = `(${startFormatted} - ${endFormatted})`;
            } else {
                durationSpan.textContent = '0';
                datesSpan.textContent = '';
            }
        }

        startDateInput.addEventListener('change', function() {
            if (endDateInput.value && new Date(endDateInput.value) < new Date(this.value)) {
                endDateInput.value = '';
            }
            endDateInput.min = this.value;
            updateDuration();
        });

        endDateInput.addEventListener('change', function() {
            if (startDateInput.value && new Date(this.value) < new Date(startDateInput.value)) {
                this.value = '';
            }
            updateDuration();
        });

        // Initialiser la date minimale de fin si une date de début est déjà définie
        if (startDateInput.value) {
            endDateInput.min = startDateInput.value;
        }
        
        // Mettre à jour l'affichage initial
        updateDuration();
    });
</script>
@endpush
@endsection
