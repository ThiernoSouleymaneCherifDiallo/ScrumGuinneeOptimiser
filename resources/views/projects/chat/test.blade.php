<!DOCTYPE html>
<html>
<head>
    <title>Chat - {{ $project->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { 
            background: #1D2125; 
            color: white; 
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
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
            width: 6px;
        }
        #messages::-webkit-scrollbar-track {
            background: #1D2125;
        }
        #messages::-webkit-scrollbar-thumb {
            background: #3C3F42;
            border-radius: 3px;
        }
        #messages::-webkit-scrollbar-thumb:hover {
            background: #4C5154;
        }
        
        /* Animations */
        .message-enter {
            animation: slideIn 0.3s ease-out;
        }
        
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(10px);
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
    </style>
</head>
<body class="h-screen flex flex-col">
    <!-- Header -->
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
        </div>
    </div>

    <!-- Messages -->
    <div id="messages" class="flex-1 overflow-y-auto p-6 space-y-4">
        @forelse($messages->reverse() as $message)
            <div class="message-item group" data-message-id="{{ $message->id }}">
                <div class="flex items-start space-x-3 {{ $message->user_id === auth()->id() ? 'justify-end' : '' }}">
                    @if($message->user_id !== auth()->id())
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white text-sm font-semibold">
                                {{ substr($message->user->name, 0, 1) }}
                            </div>
                        </div>
                    @endif
                    
                    <div class="flex-1 max-w-2xl {{ $message->user_id === auth()->id() ? 'order-first' : '' }}">
                        <div class="bg-jira-card rounded-lg p-4 border border-jira hover:border-jira-hover transition-colors">
                            @if($message->user_id !== auth()->id())
                                <div class="flex items-center space-x-2 mb-2">
                                    <span class="font-semibold text-white">{{ $message->user->name }}</span>
                                    <span class="text-xs text-jira-gray">{{ $message->created_at->format('H:i') }}</span>
                                </div>
                            @else
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-xs text-jira-gray">{{ $message->created_at->format('H:i') }}</span>
                                </div>
                            @endif
                            
                            <div class="text-white leading-relaxed">
                                {!! nl2br(e($message->content)) !!}
                            </div>
                        </div>
                    </div>
                    
                    @if($message->user_id === auth()->id())
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
        <form id="chat-form" class="flex items-end space-x-3">
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
                        <button type="button" onclick="toggleEmoji()" class="text-jira-gray hover:text-white transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
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

    <script>
        console.log('=== CHAT OPTIMISÉ CHARGÉ ===');
        
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM chargé!');
            
            const form = document.getElementById('chat-form');
            const input = document.getElementById('message-input');
            const charCount = document.getElementById('char-count');
            
            console.log('Form:', form);
            console.log('Input:', input);
            
            // Auto-resize textarea
            input.addEventListener('input', function() {
                this.style.height = 'auto';
                this.style.height = Math.min(this.scrollHeight, 120) + 'px';
                charCount.textContent = this.value.length;
            });
            
            // Enter pour envoyer, Shift+Enter pour nouvelle ligne
            input.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    form.dispatchEvent(new Event('submit'));
                }
            });
            
            if (form && input) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    console.log('=== FORMULAIRE SOUMIS ===');
                    
                    const message = input.value.trim();
                    console.log('Message:', message);
                    
                    if (!message) return;
                    
                    const token = document.querySelector('input[name="_token"]').value;
                    console.log('Token:', token);
                    
                    const formData = new FormData();
                    formData.append('content', message);
                    formData.append('_token', token);
                    
                    // Désactiver le bouton
                    const submitBtn = form.querySelector('button[type="submit"]');
                    const originalText = submitBtn.textContent;
                    submitBtn.textContent = 'Envoi...';
                    submitBtn.disabled = true;
                    
                    console.log('Envoi vers:', '{{ route("projects.chat.store", $project) }}');
                    
                    fetch('{{ route("projects.chat.store", $project) }}', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.text())
                    .then(text => {
                        console.log('Réponse:', text);
                        try {
                            const data = JSON.parse(text);
                            if (data.success) {
                                input.value = '';
                                input.style.height = 'auto';
                                charCount.textContent = '0';
                                
                                const messages = document.getElementById('messages');
                                const messageHtml = `
                                    <div class="message-item group message-enter" data-message-id="${data.message.id}">
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
                                messages.insertAdjacentHTML('beforeend', messageHtml);
                                
                                // Scroll en bas avec animation
                                messages.scrollTo({
                                    top: messages.scrollHeight,
                                    behavior: 'smooth'
                                });
                                
                                // Notification subtile
                                showNotification('Message envoyé !', 'success');
                            } else {
                                showNotification('Erreur: ' + (data.error || 'Erreur inconnue'), 'error');
                            }
                        } catch (e) {
                            console.error('Erreur JSON:', e);
                            showNotification('Erreur: ' + text, 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Erreur:', error);
                        showNotification('Erreur réseau', 'error');
                    })
                    .finally(() => {
                        // Restaurer le bouton
                        submitBtn.textContent = originalText;
                        submitBtn.disabled = false;
                    });
                });
                
                console.log('Événement ajouté!');
            }
            
            // Fonctions utilitaires
            function clearMessage() {
                input.value = '';
                input.style.height = 'auto';
                charCount.textContent = '0';
                input.focus();
            }
            
            function toggleEmoji() {
                // Placeholder pour emoji picker
                showNotification('Emoji picker à venir !', 'info');
            }
            
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
            const messages = document.getElementById('messages');
            messages.scrollTop = messages.scrollHeight;
        });
    </script>
</body>
</html> 