<div class="comment-item group" data-comment-id="{{ $comment->id }}" style="margin-left: {{ $level * 20 }}px;">
    <div class="bg-gray-700 rounded-lg p-4 border border-gray-600 hover:border-gray-500 transition-all duration-300">
        <div class="flex items-start space-x-3">
            <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white text-sm font-semibold">
                    {{ substr($comment->user->name, 0, 1) }}
                </div>
            </div>
            <div class="flex-1 min-w-0">
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center space-x-2">
                        <span class="font-medium text-white">{{ $comment->user->name }}</span>
                        <span class="text-xs text-gray-400 comment-time">{{ $comment->formatted_time }}</span>
                        @if($comment->is_edited)
                            <span class="text-xs text-gray-500">(modifié)</span>
                        @endif
                    </div>
                    <div class="flex items-center space-x-2 opacity-0 group-hover:opacity-100 transition-opacity">
                        @if($comment->is_own_comment)
                            <button onclick="editComment({{ $comment->id }}, '{{ addslashes($comment->content) }}')" 
                                    class="text-xs text-gray-400 hover:text-white transition-colors">
                                ✏️ Modifier
                            </button>
                            <button onclick="deleteComment({{ $comment->id }})" 
                                    class="text-xs text-red-400 hover:text-red-300 transition-colors">
                                🗑️ Supprimer
                            </button>
                        @else
                            <button onclick="replyToComment({{ $comment->id }}, '{{ $comment->user->name }}')" 
                                    class="text-xs text-gray-400 hover:text-white transition-colors">
                                💬 Répondre
                            </button>
                        @endif
                    </div>
                </div>
                <div class="comment-content text-gray-300 leading-relaxed">
                    {!! nl2br(e($comment->content)) !!}
                </div>
                
                <!-- Formulaire de réponse (caché par défaut) -->
                <div id="reply-form-{{ $comment->id }}" class="hidden mt-4">
                    <textarea 
                        placeholder="Répondre à {{ $comment->user->name }}..."
                        class="w-full bg-gray-600 border border-gray-500 rounded-lg px-3 py-2 text-white placeholder-gray-400 resize-none focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500"
                        rows="2"
                        maxlength="2000"
                    ></textarea>
                    <div class="flex justify-end space-x-2 mt-2">
                        <button onclick="document.getElementById('reply-form-{{ $comment->id }}').classList.add('hidden')" 
                                class="text-xs text-gray-400 hover:text-white transition-colors">
                            Annuler
                        </button>
                        <button onclick="submitReply({{ $comment->id }})" 
                                class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-xs font-medium transition-colors">
                            Répondre
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Conteneur pour les réponses -->
        <div class="replies-container mt-4 space-y-4">
            @foreach($comment->replies as $reply)
                @include('tasks.comments.partials.comment', ['comment' => $reply, 'level' => $level + 1])
            @endforeach
        </div>
    </div>
</div> 