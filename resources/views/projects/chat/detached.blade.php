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
                            
                            <!-- Affichage des fichiers -->
                            @if($message->has_file)
                                <div class="mt-3 p-3 bg-gray-800 rounded-lg border border-gray-700">
                                    @if($message->isImage())
                                        <div class="space-y-2">
                                            <div class="flex items-center space-x-2 text-sm text-jira-gray">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                                <span>{{ $message->original_name }}</span>
                                                <span class="text-xs">({{ $message->formatted_file_size }})</span>
                                            </div>
                                            <div class="relative group">
                                                <img src="{{ $message->file_url }}" alt="{{ $message->original_name }}" 
                                                     class="max-w-full max-h-64 rounded-lg cursor-pointer hover:opacity-90 transition-opacity"
                                                     onclick="openImageModal('{{ $message->file_url }}', '{{ $message->original_name }}')">
                                                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all duration-200 rounded-lg flex items-center justify-center">
                                                    <div class="opacity-0 group-hover:opacity-100 transition-opacity">
                                                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path>
                                                        </svg>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @elseif($message->isPdf())
                                        <div class="flex items-center space-x-3 p-3 bg-gray-900 rounded-lg border border-gray-600 hover:border-gray-500 transition-colors">
                                            <div class="flex-shrink-0">
                                                <svg class="w-10 h-10 text-red-500" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"/>
                                                </svg>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-white truncate">{{ $message->original_name }}</p>
                                                <p class="text-xs text-jira-gray">{{ $message->formatted_file_size }}</p>
                                            </div>
                                            <div class="flex-shrink-0 flex space-x-2">
                                                <a href="{{ route('projects.chat.download', ['project' => $project, 'message' => $message]) }}" 
                                                   class="text-blue-400 hover:text-blue-300 transition-colors p-2 rounded hover:bg-gray-700"
                                                   title="Télécharger">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                    </svg>
                                                </a>
                                                <a href="{{ $message->file_url }}" target="_blank" 
                                                   class="text-green-400 hover:text-green-300 transition-colors p-2 rounded hover:bg-gray-700"
                                                   title="Ouvrir">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                                    </svg>
                                                </a>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endif
                            
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
        <form id="chat-form" class="flex items-end space-x-3" enctype="multipart/form-data">
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
                        
                        <button type="button" onclick="document.getElementById('file-input').click()" class="text-jira-gray hover:text-white transition-colors p-1 rounded hover:bg-gray-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                
                <!-- Input file caché -->
                <input type="file" id="file-input" name="file" accept=".jpg,.jpeg,.png,.gif,.pdf" class="hidden" onchange="handleFileSelect(this)">
                
                <!-- Zone d'affichage du fichier sélectionné -->
                <div id="file-preview" class="hidden mt-3 p-3 bg-gray-800 rounded-lg border border-gray-700">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div id="file-icon" class="flex-shrink-0"></div>
                            <div>
                                <p id="file-name" class="text-sm font-medium text-white"></p>
                                <p id="file-size" class="text-xs text-jira-gray"></p>
                            </div>
                        </div>
                        <button type="button" onclick="removeFile()" class="text-red-400 hover:text-red-300 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
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
                    
                    // Ajouter le fichier s'il y en a un
                    const fileInput = document.getElementById('file-input');
                    if (fileInput.files[0]) {
                        formData.append('file', fileInput.files[0]);
                    }
                    
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
                                
                                // Nettoyer le fichier sélectionné
                                removeFile();
                                
                                // Ajouter le message à l'interface (en bas)
                                const messages = document.getElementById('messages');
                                let fileHtml = '';
                                
                                // Ajouter l'affichage du fichier s'il y en a un
                                if (data.message.has_file) {
                                    if (data.message.is_image) {
                                        fileHtml = `
                                            <div class="mt-3 p-3 bg-gray-800 rounded-lg border border-gray-700">
                                                <div class="space-y-2">
                                                    <div class="flex items-center space-x-2 text-sm text-jira-gray">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                        </svg>
                                                        <span>${data.message.original_name}</span>
                                                        <span class="text-xs">(${data.message.formatted_file_size})</span>
                                                    </div>
                                                    <div class="relative group">
                                                        <img src="${data.message.file_url}" alt="${data.message.original_name}" 
                                                             class="max-w-full max-h-64 rounded-lg cursor-pointer hover:opacity-90 transition-opacity"
                                                             onclick="openImageModal('${data.message.file_url}', '${data.message.original_name}')">
                                                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all duration-200 rounded-lg flex items-center justify-center">
                                                            <div class="opacity-0 group-hover:opacity-100 transition-opacity">
                                                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path>
                                                                </svg>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        `;
                                    } else {
                                        fileHtml = `
                                            <div class="mt-3 p-3 bg-gray-800 rounded-lg border border-gray-700">
                                                <div class="flex items-center space-x-3 p-3 bg-gray-900 rounded-lg border border-gray-600 hover:border-gray-500 transition-colors">
                                                    <div class="flex-shrink-0">
                                                        <svg class="w-10 h-10 text-red-500" fill="currentColor" viewBox="0 0 24 24">
                                                            <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"/>
                                                        </svg>
                                                    </div>
                                                    <div class="flex-1 min-w-0">
                                                        <p class="text-sm font-medium text-white truncate">${data.message.original_name}</p>
                                                        <p class="text-xs text-jira-gray">${data.message.formatted_file_size}</p>
                                                    </div>
                                                    <div class="flex-shrink-0 flex space-x-2">
                                                        <a href="/projects/{{ $project->id }}/chat/${data.message.id}/download" 
                                                           class="text-blue-400 hover:text-blue-300 transition-colors p-2 rounded hover:bg-gray-700"
                                                           title="Télécharger">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                            </svg>
                                                        </a>
                                                        <a href="${data.message.file_url}" target="_blank" 
                                                           class="text-green-400 hover:text-green-300 transition-colors p-2 rounded hover:bg-gray-700"
                                                           title="Ouvrir">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                                            </svg>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        `;
                                    }
                                }
                                
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
                                                    ${fileHtml}
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
            
            // Fonctions pour la gestion des fichiers
            window.handleFileSelect = function(input) {
                const file = input.files[0];
                if (!file) return;
                
                // Vérifier la taille (5MB max)
                const maxSize = 5 * 1024 * 1024; // 5MB en bytes
                if (file.size > maxSize) {
                    showNotification('Le fichier est trop volumineux. Taille maximum : 5MB', 'error');
                    input.value = '';
                    return;
                }
                
                // Vérifier le type de fichier
                const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'application/pdf'];
                if (!allowedTypes.includes(file.type)) {
                    showNotification('Type de fichier non autorisé. Seuls les images (JPG, PNG, GIF) et PDF sont acceptés.', 'error');
                    input.value = '';
                    return;
                }
                
                // Afficher la prévisualisation
                const preview = document.getElementById('file-preview');
                const fileName = document.getElementById('file-name');
                const fileSize = document.getElementById('file-size');
                const fileIcon = document.getElementById('file-icon');
                
                fileName.textContent = file.name;
                fileSize.textContent = formatFileSize(file.size);
                
                // Icône selon le type
                if (file.type.startsWith('image/')) {
                    fileIcon.innerHTML = `
                        <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    `;
                } else {
                    fileIcon.innerHTML = `
                        <svg class="w-8 h-8 text-red-500" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"/>
                        </svg>
                    `;
                }
                
                preview.classList.remove('hidden');
                showNotification('Fichier sélectionné : ' + file.name, 'success');
            };
            
            window.removeFile = function() {
                document.getElementById('file-input').value = '';
                document.getElementById('file-preview').classList.add('hidden');
                showNotification('Fichier supprimé', 'info');
            };
            
            window.formatFileSize = function(bytes) {
                if (bytes === 0) return '0 B';
                const k = 1024;
                const sizes = ['B', 'KB', 'MB', 'GB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
            };
            
            window.openImageModal = function(imageUrl, imageName) {
                const modal = document.createElement('div');
                modal.className = 'fixed inset-0 bg-black bg-opacity-90 z-50 flex items-center justify-center p-4';
                modal.innerHTML = `
                    <div class="relative max-w-4xl max-h-full">
                        <div class="absolute top-4 right-4 flex space-x-2">
                            <a href="${imageUrl}" download="${imageName}" 
                               class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                                Télécharger
                            </a>
                            <button onclick="closeImageModal()" 
                                    class="bg-gray-600 hover:bg-gray-700 text-white px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                                Fermer
                            </button>
                        </div>
                        <img src="${imageUrl}" alt="${imageName}" class="max-w-full max-h-full object-contain rounded-lg shadow-2xl">
                        <div class="absolute bottom-4 left-4 bg-black bg-opacity-75 text-white px-3 py-2 rounded-lg text-sm">
                            ${imageName}
                        </div>
                    </div>
                `;
                
                document.body.appendChild(modal);
                
                // Fermer avec Escape
                modal.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape') {
                        closeImageModal();
                    }
                });
                
                // Fermer en cliquant à l'extérieur
                modal.addEventListener('click', function(e) {
                    if (e.target === modal) {
                        closeImageModal();
                    }
                });
            };
            
            window.closeImageModal = function() {
                const modal = document.querySelector('.fixed.inset-0.bg-black.bg-opacity-90.z-50');
                if (modal) {
                    modal.remove();
                }
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