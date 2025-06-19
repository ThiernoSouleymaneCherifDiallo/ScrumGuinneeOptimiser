@extends('layouts.app')

@section('header')
    Projets
@endsection

@section('content')
<div class="min-h-screen bg-jira-dark text-white">
    <!-- Header avec statistiques -->
    <div class="bg-jira-card border-b border-jira p-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
            <div class="flex-1">
                <h1 class="text-3xl font-bold text-white mb-2">Mes Projets</h1>
                <p class="text-jira-gray">Gérez et suivez tous vos projets Scrum</p>
            </div>
            
            <div class="mt-6 lg:mt-0 flex flex-wrap gap-3">
                <button onclick="toggleFilters()" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.207A1 1 0 013 6.5V4z"></path>
                    </svg>
                    <span>Filtres</span>
                </button>
                
                <a href="{{ route('projects.create') }}" 
                   class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg text-sm font-medium transition-colors flex items-center space-x-2 shadow-lg shadow-blue-500/25">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    <span>Nouveau Projet</span>
                </a>
            </div>
        </div>
        
        <!-- Statistiques rapides -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6">
            <div class="bg-jira-dark rounded-lg p-4 border border-jira">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-jira-gray">Total Projets</p>
                        <p class="text-2xl font-bold text-white">{{ $projects->count() }}</p>
                    </div>
                    <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                    </div>
                </div>
            </div>
            
            <div class="bg-jira-dark rounded-lg p-4 border border-jira">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-jira-gray">Projets Actifs</p>
                        <p class="text-2xl font-bold text-green-400">{{ $projects->where('status', 'active')->count() }}</p>
                    </div>
                    <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
            
            <div class="bg-jira-dark rounded-lg p-4 border border-jira">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-jira-gray">Sprints en cours</p>
                        <p class="text-2xl font-bold text-purple-400">{{ $activeSprintsCount ?? 0 }}</p>
                    </div>
                    <div class="w-10 h-10 bg-purple-500 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                </div>
            </div>
            
            <div class="bg-jira-dark rounded-lg p-4 border border-jira">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-jira-gray">Tâches ouvertes</p>
                        <p class="text-2xl font-bold text-orange-400">{{ $openTasksCount ?? 0 }}</p>
                    </div>
                    <div class="w-10 h-10 bg-orange-500 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="p-6">
        <!-- Barre de recherche et filtres -->
        <div id="filters-section" class="bg-jira-card rounded-lg p-6 border border-jira mb-6 hidden">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-jira-gray mb-2">Rechercher</label>
                    <input type="text" id="search-input" placeholder="Nom du projet..." 
                           class="w-full bg-jira-input border border-jira rounded-lg px-3 py-2 text-white placeholder-jira-gray focus:outline-none focus:border-blue-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-jira-gray mb-2">Statut</label>
                    <select id="status-filter" class="w-full bg-jira-input border border-jira rounded-lg px-3 py-2 text-white focus:outline-none focus:border-blue-500">
                        <option value="">Tous les statuts</option>
                        <option value="active">Actif</option>
                        <option value="completed">Terminé</option>
                        <option value="archived">Archivé</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-jira-gray mb-2">Tri</label>
                    <select id="sort-filter" class="w-full bg-jira-input border border-jira rounded-lg px-3 py-2 text-white focus:outline-none focus:border-blue-500">
                        <option value="latest">Plus récents</option>
                        <option value="oldest">Plus anciens</option>
                        <option value="name">Nom A-Z</option>
                        <option value="name-desc">Nom Z-A</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Grille des projets -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" id="projects-grid">
            @forelse ($projects as $project)
                <div class="project-card bg-jira-card rounded-xl border border-jira overflow-hidden hover:bg-jira-card-hover transition-all duration-300 hover:shadow-xl hover:shadow-black/20 group" data-project="{{ strtolower($project->name) }}" data-status="{{ $project->status ?? 'active' }}">
                    <!-- Header de la carte -->
                    <div class="p-6">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center space-x-2 mb-2">
                                    <div class="w-3 h-3 rounded-full {{ $project->status === 'active' ? 'bg-green-500' : ($project->status === 'completed' ? 'bg-blue-500' : 'bg-gray-500') }}"></div>
                                    <h3 class="text-lg font-bold text-white truncate group-hover:text-blue-400 transition-colors">
                                        <a href="{{ route('projects.show', $project) }}" class="block">
                                            {{ $project->name }}
                                        </a>
                                    </h3>
                                </div>
                                
                                <div class="flex items-center space-x-2 mb-3">
                                    <span class="px-2 py-1 text-xs font-semibold bg-gray-700 text-gray-300 rounded-md">
                                        {{ $project->key }}
                                    </span>
                                    <span class="px-2 py-1 text-xs font-semibold rounded-md
                                        {{ $project->status === 'active' ? 'bg-green-900 text-green-300' : 
                                           ($project->status === 'completed' ? 'bg-blue-900 text-blue-300' : 'bg-gray-700 text-gray-300') }}">
                                        {{ ucfirst($project->status ?? 'active') }}
                                    </span>
                                </div>
                            </div>
                            
                            <!-- Menu d'actions -->
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" class="text-jira-gray hover:text-white transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                                    </svg>
                                </button>
                                
                                <div x-show="open" @click.away="open = false" 
                                     x-transition:enter="transition ease-out duration-100"
                                     x-transition:enter-start="transform opacity-0 scale-95"
                                     x-transition:enter-end="transform opacity-100 scale-100"
                                     x-transition:leave="transition ease-in duration-75"
                                     x-transition:leave-start="transform opacity-100 scale-100"
                                     x-transition:leave-end="transform opacity-0 scale-95"
                                     class="absolute right-0 mt-2 w-48 bg-jira-dark rounded-lg shadow-lg border border-jira z-10">
                                    <div class="py-1">
                                        <a href="{{ route('projects.show', $project) }}" 
                                           class="flex items-center px-4 py-2 text-sm text-jira-gray hover:bg-jira-card-hover hover:text-white">
                                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            Voir le projet
                                        </a>
                                        
                                        @can('update', $project)
                                        <a href="{{ route('projects.edit', $project) }}" 
                                           class="flex items-center px-4 py-2 text-sm text-jira-gray hover:bg-jira-card-hover hover:text-white">
                                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                            Modifier
                                        </a>
                                        @endcan
                                        
                                        <a href="{{ route('projects.sprints.index', $project) }}" 
                                           class="flex items-center px-4 py-2 text-sm text-jira-gray hover:bg-jira-card-hover hover:text-white">
                                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                            </svg>
                                            Sprints
                                        </a>
                                        
                                        <a href="{{ route('projects.backlog.index', $project) }}" 
                                           class="flex items-center px-4 py-2 text-sm text-jira-gray hover:bg-jira-card-hover hover:text-white">
                                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                            </svg>
                                            Backlog
                                        </a>
                                        
                                        <a href="{{ route('projects.chat.index', $project) }}" 
                                           class="flex items-center px-4 py-2 text-sm text-jira-gray hover:bg-jira-card-hover hover:text-white">
                                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                            </svg>
                                            Chat
                                        </a>
                                        
                                        @can('delete', $project)
                                        <div class="border-t border-jira my-1"></div>
                                        <button onclick="deleteProject({{ $project->id }}, '{{ $project->name }}')" 
                                                class="w-full flex items-center px-4 py-2 text-sm text-red-400 hover:bg-red-900/20 hover:text-red-300">
                                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                            Supprimer
                                        </button>
                                        @endcan
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Description -->
                        <p class="text-jira-gray text-sm mb-4 line-clamp-2">
                            {{ $project->description ?: 'Aucune description disponible.' }}
                        </p>
                        
                        <!-- Métriques du projet -->
                        <div class="grid grid-cols-2 gap-3 mb-4">
                            <div class="bg-jira-dark rounded-lg p-3 border border-jira">
                                <div class="flex items-center space-x-2">
                                    <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                    <span class="text-xs text-jira-gray">Sprints</span>
                                </div>
                                <p class="text-lg font-semibold text-white">{{ $project->sprints_count ?? 0 }}</p>
                            </div>
                            
                            <div class="bg-jira-dark rounded-lg p-3 border border-jira">
                                <div class="flex items-center space-x-2">
                                    <svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                    <span class="text-xs text-jira-gray">Tâches</span>
                                </div>
                                <p class="text-lg font-semibold text-white">{{ $project->tasks_count ?? 0 }}</p>
                            </div>
                        </div>
                        
                        <!-- Footer -->
                        <div class="flex items-center justify-between pt-4 border-t border-jira">
                            <div class="flex items-center space-x-2">
                                <div class="w-6 h-6 bg-blue-500 rounded-full flex items-center justify-center text-xs font-semibold text-white">
                                    {{ substr($project->owner->name, 0, 1) }}
                                </div>
                                <span class="text-sm text-jira-gray">{{ $project->owner->name }}</span>
                            </div>
                            
                            <div class="text-xs text-jira-gray">
                                {{ $project->created_at->format('d/m/Y') }}
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <!-- État vide -->
                <div class="col-span-full bg-jira-card rounded-xl border border-jira p-12 text-center">
                    <div class="w-24 h-24 bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-white mb-2">Aucun projet trouvé</h3>
                    <p class="text-jira-gray mb-6">Commencez par créer votre premier projet pour organiser votre travail.</p>
                    <a href="{{ route('projects.create') }}" 
                       class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors shadow-lg shadow-blue-500/25">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Créer mon premier projet
                    </a>
                </div>
            @endforelse
        </div>
        
        <!-- Pagination (si nécessaire) -->
        @if($projects->hasPages())
        <div class="mt-8 flex justify-center">
            {{ $projects->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Modal de confirmation de suppression -->
<div id="delete-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
    <div class="bg-jira-card rounded-lg p-6 max-w-md mx-4 border border-jira">
        <div class="flex items-center space-x-3 mb-4">
            <div class="w-10 h-10 bg-red-500 rounded-full flex items-center justify-center">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-white">Confirmer la suppression</h3>
        </div>
        <p class="text-jira-gray mb-6">Êtes-vous sûr de vouloir supprimer le projet "<span id="project-name" class="font-semibold text-white"></span>" ? Cette action est irréversible.</p>
        <div class="flex justify-end space-x-3">
            <button onclick="closeDeleteModal()" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                Annuler
            </button>
            <button onclick="confirmDelete()" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                Supprimer
            </button>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .bg-jira-dark {
        background-color: #1D2125;
    }
    .bg-jira-card {
        background-color: #22272B;
        border: 1px solid #3C3F42;
    }
    .bg-jira-card-hover:hover {
        background-color: #2C333A;
    }
    .text-jira-gray {
        color: #9FADBC;
    }
    .border-jira {
        border-color: #3C3F42;
    }
    .bg-jira-input {
        background-color: #22272B;
        border-color: #3C3F42;
    }
    .bg-jira-input:focus {
        background-color: #22272B;
        border-color: #85B8FF;
        box-shadow: 0 0 0 1px #85B8FF;
    }
    
    /* Animations */
    .project-card {
        transform: translateY(0);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .project-card:hover {
        transform: translateY(-4px);
    }
    
    /* Line clamp pour la description */
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    /* Scrollbar personnalisée */
    ::-webkit-scrollbar {
        width: 6px;
    }
    ::-webkit-scrollbar-track {
        background: #1D2125;
    }
    ::-webkit-scrollbar-thumb {
        background: #3C3F42;
        border-radius: 3px;
    }
    ::-webkit-scrollbar-thumb:hover {
        background: #4C5154;
    }
</style>
@endsection

@section('scripts')
<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script>
let currentProjectToDelete = null;

document.addEventListener('DOMContentLoaded', function() {
    initializeFilters();
    initializeSearch();
});

function toggleFilters() {
    const filtersSection = document.getElementById('filters-section');
    filtersSection.classList.toggle('hidden');
}

function initializeFilters() {
    const statusFilter = document.getElementById('status-filter');
    const sortFilter = document.getElementById('sort-filter');
    
    if (statusFilter) {
        statusFilter.addEventListener('change', filterProjects);
    }
    
    if (sortFilter) {
        sortFilter.addEventListener('change', filterProjects);
    }
}

function initializeSearch() {
    const searchInput = document.getElementById('search-input');
    
    if (searchInput) {
        searchInput.addEventListener('input', debounce(filterProjects, 300));
    }
}

function filterProjects() {
    const searchTerm = document.getElementById('search-input')?.value.toLowerCase() || '';
    const statusFilter = document.getElementById('status-filter')?.value || '';
    const sortFilter = document.getElementById('sort-filter')?.value || 'latest';
    
    const projectCards = document.querySelectorAll('.project-card');
    
    projectCards.forEach(card => {
        const projectName = card.dataset.project;
        const projectStatus = card.dataset.status;
        
        let showCard = true;
        
        // Filtre par recherche
        if (searchTerm && !projectName.includes(searchTerm)) {
            showCard = false;
        }
        
        // Filtre par statut
        if (statusFilter && projectStatus !== statusFilter) {
            showCard = false;
        }
        
        // Afficher/masquer la carte
        if (showCard) {
            card.style.display = 'block';
            card.classList.add('fade-in');
        } else {
            card.style.display = 'none';
        }
    });
    
    // Tri des cartes
    sortProjectCards(sortFilter);
}

function sortProjectCards(sortType) {
    const grid = document.getElementById('projects-grid');
    const cards = Array.from(grid.children);
    
    cards.sort((a, b) => {
        const projectNameA = a.querySelector('h3').textContent.trim();
        const projectNameB = b.querySelector('h3').textContent.trim();
        
        switch (sortType) {
            case 'name':
                return projectNameA.localeCompare(projectNameB);
            case 'name-desc':
                return projectNameB.localeCompare(projectNameA);
            case 'oldest':
                return 0; // Les cartes sont déjà dans l'ordre de création
            case 'latest':
            default:
                return 0; // Les cartes sont déjà dans l'ordre de création
        }
    });
    
    // Réorganiser les cartes dans le DOM
    cards.forEach(card => grid.appendChild(card));
}

function deleteProject(projectId, projectName) {
    currentProjectToDelete = projectId;
    document.getElementById('project-name').textContent = projectName;
    document.getElementById('delete-modal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('delete-modal').classList.add('hidden');
    currentProjectToDelete = null;
}

function confirmDelete() {
    if (currentProjectToDelete) {
        // Créer un formulaire pour la suppression
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/projects/${currentProjectToDelete}`;
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';
        
        form.appendChild(csrfToken);
        form.appendChild(methodField);
        document.body.appendChild(form);
        form.submit();
    }
}

function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Animation d'entrée pour les cartes
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
};

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.style.opacity = '1';
            entry.target.style.transform = 'translateY(0)';
        }
    });
}, observerOptions);

document.querySelectorAll('.project-card').forEach(card => {
    card.style.opacity = '0';
    card.style.transform = 'translateY(20px)';
    card.style.transition = 'opacity 0.6s ease-out, transform 0.6s ease-out';
    observer.observe(card);
});
</script>
@endsection