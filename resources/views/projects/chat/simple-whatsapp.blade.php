<!DOCTYPE html>
<html>
<head>
    <title>Chat WhatsApp Style - {{ $project->name }}</title>
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
        
        /* Styles pour les réponses WhatsApp */
        .reply-preview {
            background: linear-gradient(135deg, #2a2e33 0%, #3a3f44 100%);
            border-left: 4px solid #85B8FF;
        }
        
        .message-reply {
            background: rgba(133, 184, 255, 0.1);
            border-left: 3px solid #85B8FF;
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
    </style>
</head>
<body class="h-screen flex flex-col">
    <!-- Header -->
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
                    <p class="text-sm text-jira-gray">Chat WhatsApp Style</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Messages -->
    <div id="messages" class="flex-1 overflow-y-auto p-6 space-y-4">
        @forelse($messages as $message)
            <div class="message-item group" data-message-id="{{ $message->id }}">
                <div class="flex items-start space-x-3 {{ $message->user_id === auth()->id() ? 'justify-end' : '' }}">
                    @if($message->user_id !== auth()->id())
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center text-white text-sm font-semibold bg-gradient-to-r from-blue-500 to-purple-600 shadow-lg">
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
                                    <button onclick="replyToMessage({{ $message->id }}, '{{ $message->user->name }}', '{{ $message->content }}')" class="text-xs text-jira-gray hover:text-white transition-colors bg-gray-800 px-2 py-1 rounded">
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
                <h3 class="text-xl font-semibold text-white mb-3">Aucun message</h3>
                <p class="text-jira-gray text-sm">Soyez le premier à envoyer un message !</p>
            </div>
        @endforelse
    </div>

    <!-- Zone de saisie -->
    <div class="bg-gradient-to-r from-jira-card to-gray-800 border-t border-jira p-4 shadow-lg">
        <!-- Zone de citation (cachée par défaut) -->
        <div id="reply-preview" class="hidden mb-3 p-3 reply-preview rounded-lg">
            <div class="flex items-center justify-between">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center space-x-2 mb-1">
                        <span class="text-xs text-blue-400 font-medium">Répondre à</span>
                        <span class="text-xs text-white font-semibold" id="reply-user-name"></span>
                    </div>
                    <p class="text-xs text-gray-300 truncate" id="reply-content"></p>
                </div>
                <button onclick="cancelReply()" class="text-gray-400 hover:text-white transition-colors ml-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
        
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
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('chat-form');
            const input = document.getElementById('message-input');
            const charCount = document.getElementById('char-count');
            
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
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const message = input.value.trim();
                    if (!message) return;
                    
                    const token = document.querySelector('input[name="_token"]').value;
                    const formData = new FormData();
                    formData.append('content', message);
                    formData.append('_token', token);
                    
                    // Ajouter les informations de réponse si on répond à un message
                    if (window.replyingTo) {
                        formData.append('reply_to_message_id', window.replyingTo.messageId);
                        formData.append('reply_to_user', window.replyingTo.userName);
                        formData.append('reply_to_content', window.replyingTo.content);
                    }
                    
                    const submitBtn = form.querySelector('button[type="submit"]');
                    const originalText = submitBtn.textContent;
                    submitBtn.textContent = 'Envoi...';
                    submitBtn.disabled = true;
                    
                    fetch('{{ route("projects.chat.store", $project) }}', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(function(response) {
                        if (!response.ok) {
                            throw new Error('Erreur HTTP: ' + response.status);
                        }
                        return response.text();
                    })
                    .then(function(text) {
                        try {
                            const data = JSON.parse(text);
                            
                            if (data.success) {
                                input.value = '';
                                input.style.height = 'auto';
                                if (charCount) charCount.textContent = '0';
                                
                                // Nettoyer la réponse
                                cancelReply();
                                
                                // Ajouter le message à l'interface
                                const messages = document.getElementById('messages');
                                let replyHtml = '';
                                
                                if (data.message.reply_to) {
                                    replyHtml = `
                                        <div class="mb-3 p-2 message-reply rounded-lg">
                                            <div class="flex items-center space-x-2 mb-1">
                                                <span class="text-xs text-blue-400 font-medium">Répondre à</span>
                                                <span class="text-xs text-white font-semibold">${data.message.reply_to_user}</span>
                                            </div>
                                            <p class="text-xs text-gray-300">${data.message.reply_to_content}</p>
                                        </div>
                                    `;
                                }
                                
                                const messageHtml = `
                                    <div class="message-item group message-enter" data-message-id="${data.message.id}">
                                        <div class="flex items-start space-x-3 justify-end">
                                            <div class="flex-1 max-w-2xl order-first">
                                                <div class="message-bubble bg-jira-card rounded-2xl p-4 border border-jira hover:border-jira-hover transition-all duration-300 shadow-lg">
                                                    <div class="flex items-center justify-end mb-3">
                                                        <span class="text-xs text-jira-gray bg-gray-800 px-2 py-1 rounded-full">À l'instant</span>
                                                    </div>
                                                    ${replyHtml}
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
                                
                                // Scroll en bas
                                messages.scrollTo({
                                    top: messages.scrollHeight,
                                    behavior: 'smooth'
                                });
                                
                                showNotification('Message envoyé !', 'success');
                            } else {
                                showNotification('Erreur: ' + (data.error || 'Erreur inconnue'), 'error');
                            }
                        } catch (e) {
                            console.error('Erreur parsing JSON:', e);
                            showNotification('Erreur: Réponse non-JSON reçue', 'error');
                        }
                    })
                    .catch(function(error) {
                        console.error('Erreur fetch:', error);
                        showNotification('Erreur réseau: ' + error.message, 'error');
                    })
                    .finally(function() {
                        submitBtn.textContent = originalText;
                        submitBtn.disabled = false;
                    });
                });
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
            
            window.replyToMessage = function(messageId, userName, content) {
                // Stocker les informations de réponse
                window.replyingTo = {
                    messageId: messageId,
                    userName: userName,
                    content: content
                };
                
                // Afficher la zone de citation
                const replyPreview = document.getElementById('reply-preview');
                const replyUserName = document.getElementById('reply-user-name');
                const replyContent = document.getElementById('reply-content');
                
                replyUserName.textContent = userName;
                replyContent.textContent = content.length > 50 ? content.substring(0, 50) + '...' : content;
                replyPreview.classList.remove('hidden');
                
                // Focus sur le textarea
                input.focus();
                
                showNotification(`Réponse à ${userName}`, 'info');
            };
            
            window.cancelReply = function() {
                const replyPreview = document.getElementById('reply-preview');
                replyPreview.classList.add('hidden');
                window.replyingTo = null;
                input.focus();
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