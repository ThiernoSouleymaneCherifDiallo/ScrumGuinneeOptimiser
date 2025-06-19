@extends('layouts.app')

@section('header')
    Chat - {{ $project->name }}
@endsection

@section('content')
<div class="h-screen bg-jira-dark flex flex-col">
    <!-- Header du chat -->
    <div class="bg-jira-card border-b border-jira px-6 py-4 flex items-center justify-between">
        <div class="flex items-center space-x-4">
            <a href="{{ route('projects.show', $project) }}" class="text-jira-gray hover:text-white transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            
            <div>
                <h1 class="text-xl font-bold text-white">{{ $project->name }}</h1>
                <p class="text-sm text-jira-gray">Canal de discussion</p>
            </div>
        </div>
        
        <div class="flex items-center space-x-3">
            <div class="flex items-center space-x-2">
                <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                <span class="text-sm text-jira-gray">{{ $project->members->count() }} membres en ligne</span>
            </div>
            
            <button onclick="toggleMembers()" class="text-jira-gray hover:text-white transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </button>
        </div>
    </div>

    <div class="flex-1 flex overflow-hidden">
        <!-- Zone principale du chat -->
        <div class="flex-1 flex flex-col">
            <!-- Zone des messages -->
            <div id="messages-container" class="flex-1 overflow-y-auto p-6 space-y-4">
                @forelse($messages->reverse() as $message)
                    <div class="message-item group" data-message-id="{{ $message->id }}">
                        <div class="flex items-start space-x-3 {{ $message->is_own_message ? 'justify-end' : '' }}">
                            @if(!$message->is_own_message)
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white text-sm font-semibold">
                                        {{ substr($message->user->name, 0, 1) }}
                                    </div>
                                </div>
                            @endif
                            
                            <div class="flex-1 max-w-2xl {{ $message->is_own_message ? 'order-first' : '' }}">
                                <div class="bg-jira-card rounded-lg p-4 border border-jira hover:border-jira-hover transition-colors">
                                    @if(!$message->is_own_message)
                                        <div class="flex items-center space-x-2 mb-2">
                                            <span class="font-semibold text-white">{{ $message->user->name }}</span>
                                            <span class="text-xs text-jira-gray">{{ $message->formatted_time }}</span>
                                            @if($message->is_edited)
                                                <span class="text-xs text-jira-gray">(modifié)</span>
                                            @endif
                                        </div>
                                    @else
                                        <div class="flex items-center justify-between mb-2">
                                            <span class="text-xs text-jira-gray">{{ $message->formatted_time }}</span>
                                            @if($message->is_edited)
                                                <span class="text-xs text-jira-gray">(modifié)</span>
                                            @endif
                                        </div>
                                    @endif
                                    
                                    <div class="text-white leading-relaxed">
                                        {!! nl2br(e($message->content)) !!}
                                    </div>
                                    
                                    <!-- Actions du message -->
                                    <div class="flex items-center space-x-2 mt-3 opacity-0 group-hover:opacity-100 transition-opacity">
                                        @if($message->is_own_message)
                                            <button onclick="editMessage({{ $message->id }})" class="text-xs text-jira-gray hover:text-white transition-colors">
                                                Modifier
                                            </button>
                                            <button onclick="deleteMessage({{ $message->id }})" class="text-xs text-red-400 hover:text-red-300 transition-colors">
                                                Supprimer
                                            </button>
                                        @else
                                            <button onclick="replyToMessage({{ $message->id }})" class="text-xs text-jira-gray hover:text-white transition-colors">
                                                Répondre
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            @if($message->is_own_message)
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center text-white text-sm font-semibold">
                                        {{ substr($message->user->name, 0, 1) }}
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12">
                        <div class="w-16 h-16 bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-white mb-2">Aucun message</h3>
                        <p class="text-jira-gray">Soyez le premier à envoyer un message dans ce canal !</p>
                    </div>
                @endforelse
            </div>

            <!-- Zone de saisie -->
            <div class="bg-jira-card border-t border-jira p-4">
                <form id="message-form" class="flex items-end space-x-3">
                    @csrf
                    <div class="flex-1">
                        <div class="relative">
                            <textarea 
                                id="message-input" 
                                name="content" 
                                rows="1"
                                placeholder="Tapez votre message..."
                                class="w-full bg-jira-input border border-jira rounded-lg px-4 py-3 text-white placeholder-jira-gray resize-none focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                                maxlength="2000"
                            ></textarea>
                            
                            <div class="absolute bottom-2 right-2 flex items-center space-x-2">
                                <button type="button" onclick="toggleEmojiPicker()" class="text-jira-gray hover:text-white transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </button>
                                
                                <button type="button" onclick="toggleFileUpload()" class="text-jira-gray hover:text-white transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-between mt-2">
                            <div class="text-xs text-jira-gray">
                                <span id="char-count">0</span>/2000 caractères
                            </div>
                            
                            <div class="flex items-center space-x-2">
                                <button type="button" onclick="clearMessage()" class="text-xs text-jira-gray hover:text-white transition-colors">
                                    Effacer
                                </button>
                                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                                    Envoyer
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Sidebar des membres (cachée par défaut) -->
        <div id="members-sidebar" class="w-64 bg-jira-card border-l border-jira hidden">
            <div class="p-4 border-b border-jira">
                <h3 class="font-semibold text-white">Membres du projet</h3>
            </div>
            
            <div class="p-4 space-y-3">
                @foreach($project->members as $member)
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white text-sm font-semibold">
                            {{ substr($member->name, 0, 1) }}
                        </div>
                        <div>
                            <p class="text-sm font-medium text-white">{{ $member->name }}</p>
                            <p class="text-xs text-jira-gray">{{ $member->pivot->role ?? 'member' }}</p>
                        </div>
                        <div class="w-2 h-2 bg-green-500 rounded-full ml-auto"></div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Modal d'édition de message -->
<div id="edit-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
    <div class="bg-jira-card rounded-lg p-6 max-w-md mx-4 border border-jira">
        <h3 class="text-lg font-semibold text-white mb-4">Modifier le message</h3>
        <form id="edit-form">
            @csrf
            @method('PUT')
            <textarea 
                id="edit-message-input" 
                name="content" 
                rows="3"
                class="w-full bg-jira-input border border-jira rounded-lg px-3 py-2 text-white placeholder-jira-gray resize-none focus:outline-none focus:border-blue-500 mb-4"
                maxlength="2000"
            ></textarea>
            
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="closeEditModal()" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                    Annuler
                </button>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                    Modifier
                </button>
            </div>
        </form>
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
    .border-jira-hover:hover {
        border-color: #4C5154;
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
    
    /* Scrollbar personnalisée */
    #messages-container::-webkit-scrollbar {
        width: 6px;
    }
    #messages-container::-webkit-scrollbar-track {
        background: #1D2125;
    }
    #messages-container::-webkit-scrollbar-thumb {
        background: #3C3F42;
        border-radius: 3px;
    }
    #messages-container::-webkit-scrollbar-thumb:hover {
        background: #4C5154;
    }
</style>
@endsection

@section('scripts')
<script>
console.log('=== CHAT PRINCIPAL CHARGÉ ===');

document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM chargé!');
    
    const form = document.getElementById('message-form');
    const input = document.getElementById('message-input');
    const charCount = document.getElementById('char-count');
    
    console.log('Form:', form);
    console.log('Input:', input);
    
    // Auto-resize textarea
    if (input) {
        input.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = Math.min(this.scrollHeight, 120) + 'px';
            if (charCount) charCount.textContent = this.value.length;
        });
        
        // Enter pour envoyer, Shift+Enter pour nouvelle ligne
        input.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                form.dispatchEvent(new Event('submit'));
            }
        });
    }
    
    if (form && input) {
        console.log('Tous les éléments trouvés!');
        
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            console.log('=== FORMULAIRE SOUMIS ===');
            
            const message = input.value.trim();
            console.log('Message:', message);
            
            if (!message) {
                console.log('Message vide!');
                return;
            }
            
            // Récupérer le token
            const token = document.querySelector('input[name="_token"]').value;
            console.log('Token:', token);
            
            // Créer les données
            const formData = new FormData();
            formData.append('content', message);
            formData.append('_token', token);
            
            // Désactiver le bouton
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;
            submitBtn.textContent = 'Envoi...';
            submitBtn.disabled = true;
            
            console.log('Envoi vers:', '{{ route("projects.chat.store", $project) }}');
            
            // Envoyer
            fetch('{{ route("projects.chat.store", $project) }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(function(response) {
                console.log('Réponse reçue:', response);
                return response.text();
            })
            .then(function(text) {
                console.log('Texte reçu:', text);
                
                try {
                    const data = JSON.parse(text);
                    console.log('JSON parsé:', data);
                    
                    if (data.success) {
                        console.log('SUCCÈS! Message ajouté');
                        input.value = '';
                        input.style.height = 'auto';
                        if (charCount) charCount.textContent = '0';
                        
                        // Ajouter le message à l'interface
                        const container = document.getElementById('messages-container');
                        const messageHtml = `
                            <div class="message-item group" data-message-id="${data.message.id}">
                                <div class="flex items-start space-x-3 justify-end">
                                    <div class="flex-1 max-w-2xl order-first">
                                        <div class="bg-jira-card rounded-lg p-4 border border-jira">
                                            <div class="flex items-center justify-between mb-2">
                                                <span class="text-xs text-jira-gray">À l'instant</span>
                                            </div>
                                            <div class="text-white leading-relaxed">
                                                ${data.message.content}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center text-white text-sm font-semibold">
                                            {{ substr(auth()->user()->name, 0, 1) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                        container.insertAdjacentHTML('beforeend', messageHtml);
                        
                        // Scroll en bas avec animation
                        container.scrollTo({
                            top: container.scrollHeight,
                            behavior: 'smooth'
                        });
                        
                        // Notification subtile
                        showNotification('Message envoyé !', 'success');
                    } else {
                        console.error('Erreur:', data);
                        showNotification('Erreur: ' + (data.error || 'Erreur inconnue'), 'error');
                    }
                } catch (e) {
                    console.error('Erreur parsing JSON:', e);
                    console.log('Réponse non-JSON:', text);
                    showNotification('Erreur: Réponse non-JSON reçue', 'error');
                }
            })
            .catch(function(error) {
                console.error('Erreur fetch:', error);
                showNotification('Erreur réseau: ' + error.message, 'error');
            })
            .finally(function() {
                // Restaurer le bouton
                submitBtn.textContent = originalText;
                submitBtn.disabled = false;
            });
        });
        
        console.log('Événement submit ajouté!');
    } else {
        console.error('Éléments manquants!');
    }
    
    // Fonctions utilitaires
    window.clearMessage = function() {
        if (input) {
            input.value = '';
            input.style.height = 'auto';
            if (charCount) charCount.textContent = '0';
            input.focus();
        }
    };
    
    window.toggleEmojiPicker = function() {
        showNotification('Emoji picker à venir !', 'info');
    };
    
    window.toggleFileUpload = function() {
        showNotification('Upload de fichiers à venir !', 'info');
    };
    
    window.toggleMembers = function() {
        const sidebar = document.getElementById('members-sidebar');
        if (sidebar) {
            sidebar.classList.toggle('hidden');
        }
    };
    
    function showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 px-4 py-2 rounded-lg text-white text-sm z-50 transition-all duration-300 ${
            type === 'success' ? 'bg-green-600' : 
            type === 'error' ? 'bg-red-600' : 'bg-blue-600'
        }`;
        notification.textContent = message;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.style.opacity = '0';
            setTimeout(() => notification.remove(), 300);
        }, 2000);
    }
    
    // Scroll en bas au chargement
    const messages = document.getElementById('messages-container');
    if (messages) {
        messages.scrollTop = messages.scrollHeight;
    }
});

console.log('=== SCRIPT TERMINÉ ===');
</script>
@endsection 