@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 bg-gray-900 min-h-screen">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-bold text-white">Sprints - {{ $project->name }}</h1>
            <p class="mt-1 text-sm text-gray-400">Gérez les sprints de votre projet</p>
        </div>
        <a href="{{ route('projects.sprints.create', $project) }}" 
           class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
            </svg>
            Nouveau sprint
        </a>
    </div>

    <!-- Filtres et recherche -->
    <div class="mb-6 bg-gray-800 shadow rounded-lg p-4 border border-gray-700">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                    </svg>
                </div>
                <input type="text" id="search" placeholder="Rechercher des sprints..." 
                       class="block w-full pl-10 pr-3 py-2 border border-gray-600 rounded-md leading-5 bg-gray-700 text-white placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
            </div>
            <div class="flex items-center space-x-4">
                <select id="status-filter" class="block pl-3 pr-10 py-2 text-base border-gray-600 bg-gray-700 text-white focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                    <option value="all">Tous les statuts</option>
                    <option value="planned">Planifiés</option>
                    <option value="active">Actifs</option>
                    <option value="completed">Terminés</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Liste des sprints -->
    <div class="bg-gray-800 shadow overflow-hidden sm:rounded-md border border-gray-700">
        <ul class="divide-y divide-gray-700" id="sprints-list">
            @forelse($sprints as $sprint)
                <li class="hover:bg-gray-700 transition-colors">
                    <a href="{{ route('projects.sprints.show', [$project, $sprint]) }}" class="block">
                        <div class="px-4 py-4 sm:px-6">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <p class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        @if($sprint->status === 'active') 
                                            bg-green-900 text-green-200 
                                        @elseif($sprint->status === 'completed')
                                            bg-gray-700 text-gray-300
                                        @else
                                            bg-blue-900 text-blue-200
                                        @endif">
                                        @if($sprint->status === 'active')
                                            EN COURS
                                        @elseif($sprint->status === 'completed')
                                            TERMINÉ
                                        @else
                                            PLANIFIÉ
                                        @endif
                                    </p>
                                    <p class="ml-2 text-sm font-medium text-blue-400 truncate">{{ $sprint->name }}</p>
                                </div>
                                <div class="ml-2 flex-shrink-0 flex">
                                    <p class="px-2 inline-flex text-xs leading-5 font-semibold text-gray-400">
                                        {{ $sprint->tasks_count ?? 0 }} tâches
                                    </p>
                                </div>
                            </div>
                            <div class="mt-2 sm:flex sm:justify-between">
                                <div class="sm:flex">
                                    <p class="flex items-center text-sm text-gray-400">
                                        <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                                        </svg>
                                        @if($sprint->start_date && $sprint->end_date)
                                            {{ $sprint->start_date->format('d M Y') }} - {{ $sprint->end_date->format('d M Y') }}
                                        @else
                                            Dates non définies
                                        @endif
                                    </p>
                                </div>
                                <div class="mt-2 flex items-center text-sm text-gray-400 sm:mt-0">
                                    <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                    </svg>
                                    @if($sprint->start_date && $sprint->end_date)
                                        {{ $sprint->start_date->diffInDays($sprint->end_date) + 1 }} jours
                                    @else
                                        Durée non définie
                                    @endif
                                </div>
                            </div>
                            @if($sprint->goal)
                                <div class="mt-2">
                                    <p class="text-sm text-gray-300 truncate">
                                        {{ $sprint->goal }}
                                    </p>
                                </div>
                            @endif
                        </div>
                    </a>
                </li>
            @empty
                <li class="px-4 py-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-white">Aucun sprint</h3>
                    <p class="mt-1 text-sm text-gray-400">Commencez par créer un nouveau sprint.</p>
                    <div class="mt-6">
                        <a href="{{ route('projects.sprints.create', $project) }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                            </svg>
                            Nouveau sprint
                        </a>
                    </div>
                </li>
            @endforelse
        </ul>
    </div>

    <!-- Pagination -->
    @if($sprints->hasPages())
        <div class="mt-4">
            {{ $sprints->links() }}
        </div>
    @endif
</div>

@push('scripts')
<script>
    // Filtrage côté client pour une meilleure réactivité
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('search');
        const statusFilter = document.getElementById('status-filter');
        const sprintItems = document.querySelectorAll('#sprints-list > li');

        function filterSprints() {
            const searchTerm = searchInput.value.toLowerCase();
            const statusValue = statusFilter.value;

            sprintItems.forEach(item => {
                const name = item.querySelector('.text-blue-400').textContent.toLowerCase();
                const status = item.querySelector('p:first-child > span').textContent.trim().toLowerCase();
                const matchesSearch = name.includes(searchTerm);
                const matchesStatus = statusValue === 'all' || 
                    (statusValue === 'planned' && status === 'planifié') ||
                    (statusValue === 'active' && status === 'en cours') ||
                    (statusValue === 'completed' && status === 'terminé');

                if (matchesSearch && matchesStatus) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
        }


        searchInput.addEventListener('input', filterSprints);
        statusFilter.addEventListener('change', filterSprints);
    });
</script>
@endpush
@endsection