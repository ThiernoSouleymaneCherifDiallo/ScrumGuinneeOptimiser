@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-900 text-white">
    <div class="container mx-auto px-4 py-8">
        <!-- En-tête -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-white mb-2">Équipe - {{ $project->name }}</h1>
                    <div class="flex items-center space-x-6 text-gray-400">
                        <span>{{ $members->count() }} membres</span>
                        <span>{{ $project->tasks->count() }} tâches</span>
                    </div>
                </div>
                
                <div class="flex space-x-3">
                    <button onclick="openAddMemberModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                        + Ajouter un membre
                    </button>
                    <a href="{{ route('projects.show', $project) }}" class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
                        Retour au projet
                    </a>
                </div>
            </div>
        </div>

        <!-- Liste des membres -->
        <div class="bg-gray-800 rounded-lg border border-gray-600">
            <div class="px-6 py-4 border-b border-gray-600">
                <h2 class="text-xl font-bold text-white">Membres de l'équipe</h2>
            </div>
            
            <div class="divide-y divide-gray-600">
                @forelse($members as $member)
                    <div class="px-6 py-4 hover:bg-gray-700 transition-colors duration-200">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div class="flex-shrink-0">
                                    <img class="h-12 w-12 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode($member->name) }}&background=random" alt="{{ $member->name }}">
                                </div>
                                
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center space-x-2">
                                        <h3 class="text-lg font-medium text-white">{{ $member->name }}</h3>
                                        @if($project->owner_id === $member->id)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-900 text-purple-300 border border-purple-700">
                                                Propriétaire
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                @if($member->pivot->role === 'admin') bg-red-900 text-red-300 border border-red-700
                                                @elseif($member->pivot->role === 'member') bg-blue-900 text-blue-300 border border-blue-700
                                                @else bg-gray-900 text-gray-300 border border-gray-700 @endif">
                                                {{ ucfirst($member->pivot->role) }}
                                            </span>
                                        @endif
                                    </div>
                                    <p class="text-sm text-gray-400">{{ $member->email }}</p>
                                </div>
                            </div>
                            
                            <!-- Actions -->
                            @if($project->owner_id !== $member->id)
                                <div class="flex items-center space-x-2">
                                    <button onclick="openEditRoleModal({{ $member->id }}, '{{ $member->pivot->role }}')" 
                                            class="bg-gray-600 hover:bg-gray-700 text-white px-3 py-1 rounded text-sm transition-colors">
                                        Modifier le rôle
                                    </button>
                                    <button onclick="confirmRemoveMember({{ $member->id }}, '{{ $member->name }}')" 
                                            class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-sm transition-colors">
                                        Supprimer
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-8 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-500 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                        </svg>
                        <p class="text-gray-500">Aucun membre dans l'équipe</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Modal d'ajout de membre -->
<div id="addMemberModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
        
        <div class="inline-block align-bottom bg-gray-800 rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6 border border-gray-600">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg leading-6 font-medium text-white">Ajouter un membre</h3>
                    <button onclick="closeAddMemberModal()" class="text-gray-400 hover:text-white transition-colors">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                <form action="{{ route('projects.team.add-member', $project) }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label for="search" class="block text-sm font-medium text-gray-300 mb-2">Rechercher un utilisateur</label>
                            <input type="text" id="search" placeholder="Nom ou email..." 
                                   class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <div id="searchResults" class="mt-2 space-y-1 max-h-40 overflow-y-auto"></div>
                        </div>
                        
                        <div>
                            <label for="role" class="block text-sm font-medium text-gray-300 mb-2">Rôle</label>
                            <select name="role" id="role" required class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="member">Membre</option>
                                <option value="admin">Admin</option>
                                <option value="viewer">Lecteur</option>
                            </select>
                        </div>
                        
                        <input type="hidden" name="user_id" id="selectedUserId" required>
                    </div>
                    
                    <div class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:col-start-2 sm:text-sm">
                            Ajouter
                        </button>
                        <button type="button" onclick="closeAddMemberModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-600 shadow-sm px-4 py-2 bg-gray-700 text-base font-medium text-white hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:col-start-1 sm:text-sm">
                            Annuler
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal de modification de rôle -->
<div id="editRoleModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
        
        <div class="inline-block align-bottom bg-gray-800 rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full sm:p-6 border border-gray-600">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg leading-6 font-medium text-white">Modifier le rôle</h3>
                    <button onclick="closeEditRoleModal()" class="text-gray-400 hover:text-white transition-colors">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                <form id="editRoleForm" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="space-y-4">
                        <div>
                            <label for="editRole" class="block text-sm font-medium text-gray-300 mb-2">Nouveau rôle</label>
                            <select name="role" id="editRole" required class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="member">Membre</option>
                                <option value="admin">Admin</option>
                                <option value="viewer">Lecteur</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:col-start-2 sm:text-sm">
                            Mettre à jour
                        </button>
                        <button type="button" onclick="closeEditRoleModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-600 shadow-sm px-4 py-2 bg-gray-700 text-base font-medium text-white hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:col-start-1 sm:text-sm">
                            Annuler
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Gestion des modals
    function openAddMemberModal() {
        document.getElementById('addMemberModal').classList.remove('hidden');
    }

    function closeAddMemberModal() {
        document.getElementById('addMemberModal').classList.add('hidden');
        document.getElementById('search').value = '';
        document.getElementById('searchResults').innerHTML = '';
        document.getElementById('selectedUserId').value = '';
    }

    // Recherche d'utilisateurs
    let searchTimeout;
    document.getElementById('search').addEventListener('input', function() {
        clearTimeout(searchTimeout);
        const query = this.value;
        
        if (query.length < 2) {
            document.getElementById('searchResults').innerHTML = '';
            return;
        }
        
        searchTimeout = setTimeout(() => {
            fetch(`{{ route('projects.team.search', $project) }}?query=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(users => {
                    const resultsContainer = document.getElementById('searchResults');
                    resultsContainer.innerHTML = '';
                    
                    users.forEach(user => {
                        const div = document.createElement('div');
                        div.className = 'p-2 bg-gray-700 rounded cursor-pointer hover:bg-gray-600 transition-colors';
                        div.innerHTML = `
                            <div class="text-white font-medium">${user.name}</div>
                            <div class="text-gray-400 text-sm">${user.email}</div>
                        `;
                        div.onclick = () => selectUser(user);
                        resultsContainer.appendChild(div);
                    });
                });
        }, 300);
    });

    function selectUser(user) {
        document.getElementById('search').value = user.name;
        document.getElementById('selectedUserId').value = user.id;
        document.getElementById('searchResults').innerHTML = '';
    }

    // Confirmation de suppression
    function confirmRemoveMember(userId, userName) {
        if (confirm(`Êtes-vous sûr de vouloir supprimer ${userName} de l'équipe ?`)) {
            const form = document.createElement('form');
            form.method = 'POST';
            let action = @json(route('projects.team.remove-member', ['project' => $project, 'user' => 'USER_ID_PLACEHOLDER']));
            action = action.replace('USER_ID_PLACEHOLDER', userId);
            form.action = action;
            
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = document.querySelector('meta[name="csrf-token"]').content;
            
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

    // Fermer les modals si on clique en dehors
    window.onclick = function(event) {
        const addModal = document.getElementById('addMemberModal');
        if (event.target === addModal) {
            closeAddMemberModal();
        }
    }

    // Fermer avec la touche Échap
    document.onkeydown = function(evt) {
        evt = evt || window.event;
        if (evt.key === 'Escape') {
            closeAddMemberModal();
        }
    };

    function openEditRoleModal(userId, currentRole) {
        document.getElementById('editRole').value = currentRole;
        let action = @json(route('projects.team.update-role', ['project' => $project, 'user' => 'USER_ID_PLACEHOLDER']));
        action = action.replace('USER_ID_PLACEHOLDER', userId);
        document.getElementById('editRoleForm').action = action;
        document.getElementById('editRoleModal').classList.remove('hidden');
    }

    function closeEditRoleModal() {
        document.getElementById('editRoleModal').classList.add('hidden');
    }
</script>
@endpush
