<!DOCTYPE html>
<html>
<head>
    <title>Chat - {{ $project->name }}</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { 
            background: #1D2125; 
            color: white; 
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            margin: 0;
            padding: 0;
        }
        .bg-jira-dark { background: #1D2125; }
        .bg-jira-card { 
            background: #22272B; 
            border: 1px solid #3C3F42; 
        }
        .bg-jira-input { 
            background: #22272B; 
            border: 1px solid #3C3F42; 
        }
        .bg-jira-input:focus { 
            border-color: #85B8FF; 
            box-shadow: 0 0 0 1px #85B8FF; 
        }
        .text-jira-gray { color: #9FADBC; }
        .border-jira { border-color: #3C3F42; }
        
        /* Scrollbar personnalisée */
        #messages::-webkit-scrollbar {
            width: 8px;
        }
        #messages::-webkit-scrollbar-track {
            background: #1D2125;
            border-radius: 4px;
        }
        #messages::-webkit-scrollbar-thumb {
            background: #4C5154;
            border-radius: 4px;
        }
        #messages::-webkit-scrollbar-thumb:hover {
            background: #5A5F62;
        }
        
        /* Animations */
        .message-enter {
            animation: slideInUp 0.4s ease-out;
        }
        
        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .typing-indicator {
            animation: pulse 1.5s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 0.5; }
            50% { opacity: 1; }
        }
        
        /* Effets de survol */
        .message-bubble:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        }
        
        /* Gradient pour les avatars */
        .avatar-gradient-1 { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .avatar-gradient-2 { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
        .avatar-gradient-3 { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
        .avatar-gradient-4 { background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); }
        .avatar-gradient-5 { background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); }
        
        /* Responsive */
        @media (max-width: 768px) {
            .chat-container {
                height: 100vh;
            }
        }
    </style>
</head>
<body class="h-screen flex flex-col">
    <!-- Header amélioré -->
    <div class="bg-gradient-to-r from-jira-card to-gray-800 border-b border-jira px-6 py-4 flex items-center justify-between shadow-lg">
        <div class="flex items-center space-x-4">
            <button onclick="window.close()" class="text-jira-gray hover:text-white transition-colors p-2 rounded-lg hover:bg-gray-700">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
            
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-white">{{ $project->name }}</h1>
                    <p class="text-sm text-jira-gray">Canal de discussion</p>
                </div>
            </div>
        </div>
        
        <div class="flex items-center space-x-4">
            <div class="flex items-center space-x-2 bg-gray-800 px-3 py-2 rounded-lg">
                <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                <span class="text-sm text-jira-gray">{{ $project->members->count() }} membres en ligne</span>
            </div>
            
            <div class="flex items-center space-x-2">
                @foreach($project->members->take(3) as $member)
                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-sm font-semibold avatar-gradient-{{ $loop->index % 5 + 1 }}">
                        {{ substr($member->name, 0, 1) }}
                    </div>
                @endforeach
                @if($project->members->count() > 3)
                    <div class="w-8 h-8 bg-gray-600 rounded-full flex items-center justify-center text-white text-xs">
                        +{{ $project->members->count() - 3 }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Messages avec affichage correct (plus récents en bas) -->
    <div id="messages" class="flex-1 overflow-y-auto p-6 space-y-4">
        @forelse($messages as $message)
            <div class="message-item group" data-message-id="{{ $message->id }}">
                <div class="flex items-start space-x-3 {{ $message->user_id === auth()->id() ? 'justify-end' : '' }}">
                    @if($message->user_id !== auth()->id())
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center text-white text-sm font-semibold avatar-gradient-{{ $loop->index % 5 + 1 }} shadow-lg">
                                {{ substr($message->user->name, 0, 1) }}
                            </div>
                        </div>
                    @endif
                    
                    <div class="flex-1 max-w-2xl {{ $message->user_id === auth()->id() ? 'order-first' : '' }}">
                        <div class="message-bubble bg-jira-card rounded-2xl p-4 border border-jira hover:border-jira-hover transition-all duration-300 shadow-lg">
                            @if($message->user_id !== auth()->id())
                                <div class="flex items-center space-x-2 mb-3">
                                    <span class="font-semibold text-white">{{ $message->user->name }}</span>
                                    <span class="text-xs text-jira-gray bg-gray-800 px-2 py-1 rounded-full">{{ $message->created_at->format('H:i') }}</span>
                                </div>
                            @else
                                <div class="flex items-center justify-end mb-3">
                                    <span class="text-xs text-jira-gray bg-gray-800 px-2 py-1 rounded-full">{{ $message->created_at->format('H:i') }}</span>
                                </div>
                            @endif
                            
                            <div class="text-white leading-relaxed text-sm">
                                {!! nl2br(e($message->content)) !!}
                            </div>
                            
                            <!-- Actions du message -->
                            <div class="flex items-center space-x-2 mt-3 opacity-0 group-hover:opacity-100 transition-opacity">
                                @if($message->user_id === auth()->id())
                                    <button onclick="editMessage({{ $message->id }})" class="text-xs text-jira-gray hover:text-white transition-colors bg-gray-800 px-2 py-1 rounded">
                                        ✏️ Modifier
                                    </button>
                                    <button onclick="deleteMessage({{ $message->id }})" class="text-xs text-red-400 hover:text-red-300 transition-colors bg-gray-800 px-2 py-1 rounded">
                                        🗑️ Supprimer
                                    </button>
                                @else
                                    <button onclick="replyToMessage({{ $message->id }})" class="text-xs text-jira-gray hover:text-white transition-colors bg-gray-800 px-2 py-1 rounded">
                                        💬 Répondre
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    @if($message->user_id === auth()->id())
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-gradient-to-r from-green-500 to-emerald-600 rounded-full flex items-center justify-center text-white text-sm font-semibold shadow-lg">
                                {{ substr($message->user->name, 0, 1) }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="text-center py-12">
                <div class="w-20 h-20 bg-gradient-to-r from-gray-700 to-gray-800 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg">
                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-white mb-3">Aucun message</h3>
                <p class="text-jira-gray text-sm">Soyez le premier à envoyer un message dans ce canal !</p>
            </div>
        @endforelse
    </div>

    <!-- Zone de saisie améliorée -->
    <div class="bg-gradient-to-r from-jira-card to-gray-800 border-t border-jira p-4 shadow-lg">
        <form id="chat-form" class="flex items-end space-x-3">
            @csrf
            <div class="flex-1">
                <div class="relative">
                    <textarea 
                        id="message-input" 
                        name="content" 
                        rows="1"
                        placeholder="Tapez votre message..."
                        class="w-full bg-jira-input border border-jira rounded-2xl px-4 py-3 text-white placeholder-jira-gray resize-none focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500 transition-all duration-300"
                        maxlength="2000"
                    ></textarea>
                    
                    <div class="absolute bottom-2 right-2 flex items-center space-x-2">
                        <button type="button" onclick="toggleEmoji()" class="text-jira-gray hover:text-white transition-colors p-1 rounded hover:bg-gray-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </button>
                        
                        <button type="button" onclick="toggleFileUpload()" class="text-jira-gray hover:text-white transition-colors p-1 rounded hover:bg-gray-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                
                <div class="flex items-center justify-between mt-3">
                    <div class="text-xs text-jira-gray bg-gray-800 px-3 py-1 rounded-full">
                        <span id="char-count">0</span>/2000 caractères
                    </div>
                    
                    <div class="flex items-center space-x-2">
                        <button type="button" onclick="clearMessage()" class="text-xs text-jira-gray hover:text-white transition-colors bg-gray-800 px-3 py-1 rounded-full hover:bg-gray-700">
                            Effacer
                        </button>
                        <button type="submit" class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white px-6 py-2 rounded-full text-sm font-medium transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
                            Envoyer
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        console.log('=== CHAT DÉTACHÉ AMÉLIORÉ CHARGÉ ===');
        
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM chargé!');
            
            const form = document.getElementById('chat-form');
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
                                
                                // Ajouter le message à l'interface (en bas)
                                const messages = document.getElementById('messages');
                                const messageHtml = `
                                    <div class="message-item group message-enter" data-message-id="${data.message.id}">
                                        <div class="flex items-start space-x-3 justify-end">
                                            <div class="flex-1 max-w-2xl order-first">
                                                <div class="message-bubble bg-jira-card rounded-2xl p-4 border border-jira hover:border-jira-hover transition-all duration-300 shadow-lg">
                                                    <div class="flex items-center justify-end mb-3">
                                                        <span class="text-xs text-jira-gray bg-gray-800 px-2 py-1 rounded-full">À l'instant</span>
                                                    </div>
                                                    <div class="text-white leading-relaxed text-sm">
                                                        ${data.message.content}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <div class="w-10 h-10 bg-gradient-to-r from-green-500 to-emerald-600 rounded-full flex items-center justify-center text-white text-sm font-semibold shadow-lg">
                                                    {{ substr(auth()->user()->name, 0, 1) }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                `;
                                messages.insertAdjacentHTML('beforeend', messageHtml);
                                
                                // Scroll en bas avec animation
                                messages.scrollTo({
                                    top: messages.scrollHeight,
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
            
            window.toggleEmoji = function() {
                showNotification('Emoji picker à venir !', 'info');
            };
            
            window.toggleFileUpload = function() {
                showNotification('Upload de fichiers à venir !', 'info');
            };
            
            // Fonctions pour modifier et supprimer les messages
            window.editMessage = function(messageId) {
                const messageElement = document.querySelector(`[data-message-id="${messageId}"]`);
                const contentElement = messageElement.querySelector('.text-white.leading-relaxed');
                const currentContent = contentElement.textContent.trim();
                
                // Créer un modal d'édition
                const modal = document.createElement('div');
                modal.className = 'fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center';
                modal.innerHTML = `
                    <div class="bg-jira-card rounded-2xl p-6 max-w-md mx-4 border border-jira shadow-2xl">
                        <h3 class="text-lg font-semibold text-white mb-4">Modifier le message</h3>
                        <form id="edit-form">
                            @csrf
                            @method('PUT')
                            <textarea 
                                id="edit-message-input" 
                                name="content" 
                                rows="3"
                                class="w-full bg-jira-input border border-jira rounded-lg px-3 py-2 text-white placeholder-jira-gray resize-none focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500 mb-4"
                                maxlength="2000"
                            >${currentContent}</textarea>
                            
                            <div class="flex justify-end space-x-3">
                                <button type="button" onclick="closeEditModal()" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                                    Annuler
                                </button>
                                <button type="submit" class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                                    Modifier
                                </button>
                            </div>
                        </form>
                    </div>
                `;
                
                document.body.appendChild(modal);
                
                // Focus sur le textarea
                const editInput = modal.querySelector('#edit-message-input');
                editInput.focus();
                editInput.setSelectionRange(editInput.value.length, editInput.value.length);
                
                // Gérer la soumission du formulaire
                const editForm = modal.querySelector('#edit-form');
                editForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const newContent = editInput.value.trim();
                    if (!newContent) {
                        showNotification('Le message ne peut pas être vide', 'error');
                        return;
                    }
                    
                    const formData = new FormData();
                    formData.append('content', newContent);
                    formData.append('_token', document.querySelector('input[name="_token"]').value);
                    formData.append('_method', 'PUT');
                    
                    fetch('{{ route("projects.chat.update", ["project" => $project, "message" => ":messageId"]) }}'.replace(':messageId', messageId), {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Mettre à jour le contenu du message
                            contentElement.innerHTML = newContent.replace(/\n/g, '<br>');
                            
                            // Ajouter l'indicateur "(modifié)"
                            const timeElement = messageElement.querySelector('.text-xs.text-jira-gray');
                            if (timeElement && !timeElement.textContent.includes('(modifié)')) {
                                timeElement.textContent = timeElement.textContent + ' (modifié)';
                            }
                            
                            closeEditModal();
                            showNotification('Message modifié !', 'success');
                        } else {
                            showNotification('Erreur: ' + (data.error || 'Erreur inconnue'), 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Erreur:', error);
                        showNotification('Erreur réseau', 'error');
                    });
                });
            };
            
            window.deleteMessage = function(messageId) {
                if (!confirm('Êtes-vous sûr de vouloir supprimer ce message ?')) {
                    return;
                }
                
                const messageElement = document.querySelector(`[data-message-id="${messageId}"]`);
                
                const formData = new FormData();
                formData.append('_token', document.querySelector('input[name="_token"]').value);
                formData.append('_method', 'DELETE');
                
                fetch('{{ route("projects.chat.destroy", ["project" => $project, "message" => ":messageId"]) }}'.replace(':messageId', messageId), {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Supprimer l'élément du DOM avec animation
                        messageElement.style.opacity = '0';
                        messageElement.style.transform = 'translateX(100px)';
                        setTimeout(() => {
                            messageElement.remove();
                        }, 300);
                        
                        showNotification('Message supprimé !', 'success');
                    } else {
                        showNotification('Erreur: ' + (data.error || 'Erreur inconnue'), 'error');
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    showNotification('Erreur réseau', 'error');
                });
            };
            
            window.replyToMessage = function(messageId) {
                const messageElement = document.querySelector(`[data-message-id="${messageId}"]`);
                const userName = messageElement.querySelector('.font-semibold.text-white')?.textContent || 'ce message';
                
                // Ajouter le nom de l'utilisateur dans le textarea
                input.value = `@${userName} `;
                input.focus();
                input.setSelectionRange(input.value.length, input.value.length);
                
                showNotification(`Réponse à ${userName}`, 'info');
            };
            
            window.closeEditModal = function() {
                const modal = document.querySelector('.fixed.inset-0.bg-black.bg-opacity-50.z-50');
                if (modal) {
                    modal.remove();
                }
            };
            
            function showNotification(message, type = 'info') {
                const notification = document.createElement('div');
                notification.className = `fixed top-4 right-4 px-4 py-2 rounded-lg text-white text-sm z-50 transition-all duration-300 shadow-lg ${
                    type === 'success' ? 'bg-gradient-to-r from-green-600 to-green-700' : 
                    type === 'error' ? 'bg-gradient-to-r from-red-600 to-red-700' : 'bg-gradient-to-r from-blue-600 to-blue-700'
                }`;
                notification.textContent = message;
                
                document.body.appendChild(notification);
                
                setTimeout(() => {
                    notification.style.opacity = '0';
                    setTimeout(() => notification.remove(), 300);
                }, 2000);
            }
            
            // Scroll en bas au chargement
            const messages = document.getElementById('messages');
            if (messages) {
                messages.scrollTop = messages.scrollHeight;
            }
        });
    </script>
</body>
</html> 