@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="bg-gradient-to-r from-gray-800 to-gray-900 rounded-lg shadow-lg p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-white mb-2">{{ $task->title }}</h1>
                <p class="text-gray-300 text-sm">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                        @switch($task->priority)
                            @case('urgent') bg-red-800 text-red-100
                            @case('high') bg-orange-800 text-orange-100
                            @case('medium') bg-yellow-800 text-yellow-100
                            @default bg-green-800 text-green-100
                        @endswitch">
                        {{ ucfirst($task->priority) }}
                    </span>
                    <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                        @switch($task->status)
                            @case('todo') bg-gray-700 text-gray-100
                            @case('in_progress') bg-blue-800 text-blue-100
                            @case('review') bg-yellow-800 text-yellow-100
                            @default bg-green-800 text-green-100
                        @endswitch">
                        {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                    </span>
                </p>
            </div>
            <a href="{{ route('projects.show', $project) }}" 
               class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors">
                ← Retour au projet
            </a>
        </div>
    </div>

    <!-- Section Commentaires -->
    <div class="bg-gray-800 rounded-lg shadow-lg overflow-hidden">
        <div class="p-6 border-b border-gray-700">
            <h2 class="text-xl font-semibold text-white mb-4">Commentaires</h2>
            
            <!-- Formulaire d'ajout de commentaire -->
            <form id="comment-form" class="mb-6">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label for="comment-content" class="block text-sm font-medium text-gray-300 mb-2">
                            Ajouter un commentaire
                        </label>
                        <textarea 
                            id="comment-content" 
                            name="content" 
                            rows="3"
                            placeholder="Tapez votre commentaire..."
                            class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-3 text-white placeholder-gray-400 resize-none focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500 transition-all duration-300"
                            maxlength="2000"
                        ></textarea>
                        <div class="flex justify-between items-center mt-2">
                            <span class="text-xs text-gray-400">
                                <span id="char-count">0</span>/2000 caractères
                            </span>
                            <button type="submit" 
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                                Publier
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Liste des commentaires -->
        <div id="comments-container" class="p-6 space-y-6">
            @forelse($comments as $comment)
                @include('tasks.comments.partials.comment', ['comment' => $comment, 'level' => 0])
            @empty
                <div class="text-center py-12">
                    <div class="text-gray-400 mb-4">
                        <svg class="mx-auto h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-white mb-2">Aucun commentaire</h3>
                    <p class="text-gray-400">Soyez le premier à commenter cette tâche !</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Modal d'édition -->
<div id="edit-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-gray-800 rounded-lg shadow-xl max-w-md w-full p-6">
            <h3 class="text-lg font-semibold text-white mb-4">Modifier le commentaire</h3>
            <form id="edit-form">
                @csrf
                @method('PUT')
                <textarea 
                    id="edit-content" 
                    name="content" 
                    rows="3"
                    class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-3 text-white placeholder-gray-400 resize-none focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500 mb-4"
                    maxlength="2000"
                ></textarea>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeEditModal()" 
                            class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                        Annuler
                    </button>
                    <button type="submit" 
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                        Modifier
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const commentForm = document.getElementById('comment-form');
    const commentContent = document.getElementById('comment-content');
    const charCount = document.getElementById('char-count');
    const commentsContainer = document.getElementById('comments-container');
    
    // Compteur de caractères
    if (commentContent) {
        commentContent.addEventListener('input', function() {
            charCount.textContent = this.value.length;
        });
    }
    
    // Soumission du formulaire principal
    if (commentForm) {
        commentForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const content = commentContent.value.trim();
            if (!content) return;
            
            const formData = new FormData();
            formData.append('content', content);
            formData.append('_token', document.querySelector('input[name="_token"]').value);
            
            fetch('{{ route("tasks.comments.store", [$project, $task]) }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Ajouter le commentaire à l'interface
                    const commentHtml = generateCommentHtml(data.comment, 0);
                    commentsContainer.insertAdjacentHTML('beforeend', commentHtml);
                    
                    // Vider le formulaire
                    commentContent.value = '';
                    charCount.textContent = '0';
                    
                    // Supprimer le message "aucun commentaire" s'il existe
                    const emptyMessage = commentsContainer.querySelector('.text-center');
                    if (emptyMessage) {
                        emptyMessage.remove();
                    }
                    
                    showNotification('Commentaire ajouté !', 'success');
                } else {
                    showNotification('Erreur: ' + (data.error || 'Erreur inconnue'), 'error');
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                showNotification('Erreur réseau', 'error');
            });
        });
    }
    
    // Fonctions globales
    window.replyToComment = function(commentId, userName) {
        const replyForm = document.getElementById(`reply-form-${commentId}`);
        if (replyForm) {
            replyForm.classList.toggle('hidden');
            const textarea = replyForm.querySelector('textarea');
            if (textarea) {
                textarea.focus();
            }
        }
    };
    
    window.submitReply = function(commentId) {
        const replyForm = document.getElementById(`reply-form-${commentId}`);
        const textarea = replyForm.querySelector('textarea');
        const content = textarea.value.trim();
        
        if (!content) return;
        
        const formData = new FormData();
        formData.append('content', content);
        formData.append('parent_id', commentId);
        formData.append('_token', document.querySelector('input[name="_token"]').value);
        
        fetch('{{ route("tasks.comments.store", [$project, $task]) }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Ajouter la réponse à l'interface
                const replyHtml = generateCommentHtml(data.comment, 1);
                const commentElement = document.querySelector(`[data-comment-id="${commentId}"]`);
                const repliesContainer = commentElement.querySelector('.replies-container');
                
                if (repliesContainer) {
                    repliesContainer.insertAdjacentHTML('beforeend', replyHtml);
                }
                
                // Vider le formulaire et le cacher
                textarea.value = '';
                replyForm.classList.add('hidden');
                
                showNotification('Réponse ajoutée !', 'success');
            } else {
                showNotification('Erreur: ' + (data.error || 'Erreur inconnue'), 'error');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            showNotification('Erreur réseau', 'error');
        });
    };
    
    window.editComment = function(commentId, currentContent) {
        const modal = document.getElementById('edit-modal');
        const editContent = document.getElementById('edit-content');
        const editForm = document.getElementById('edit-form');
        
        editContent.value = currentContent;
        modal.classList.remove('hidden');
        
        // Gérer la soumission du formulaire d'édition
        editForm.onsubmit = function(e) {
            e.preventDefault();
            
            const newContent = editContent.value.trim();
            if (!newContent) return;
            
            const formData = new FormData();
            formData.append('content', newContent);
            formData.append('_token', document.querySelector('input[name="_token"]').value);
            formData.append('_method', 'PUT');
            
            fetch(`{{ route("tasks.comments.update", ["project" => $project, "task" => $task, "comment" => ":commentId"]) }}`.replace(':commentId', commentId), {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Mettre à jour le contenu du commentaire
                    const commentElement = document.querySelector(`[data-comment-id="${commentId}"]`);
                    const contentElement = commentElement.querySelector('.comment-content');
                    contentElement.innerHTML = newContent.replace(/\n/g, '<br>');
                    
                    // Ajouter l'indicateur "(modifié)"
                    const timeElement = commentElement.querySelector('.comment-time');
                    if (timeElement && !timeElement.textContent.includes('(modifié)')) {
                        timeElement.textContent = timeElement.textContent + ' (modifié)';
                    }
                    
                    closeEditModal();
                    showNotification('Commentaire modifié !', 'success');
                } else {
                    showNotification('Erreur: ' + (data.error || 'Erreur inconnue'), 'error');
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                showNotification('Erreur réseau', 'error');
            });
        };
    };
    
    window.deleteComment = function(commentId) {
        if (!confirm('Êtes-vous sûr de vouloir supprimer ce commentaire ?')) {
            return;
        }
        
        const formData = new FormData();
        formData.append('_token', document.querySelector('input[name="_token"]').value);
        formData.append('_method', 'DELETE');
        
        fetch(`{{ route("tasks.comments.destroy", ["project" => $project, "task" => $task, "comment" => ":commentId"]) }}`.replace(':commentId', commentId), {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Supprimer l'élément du DOM
                const commentElement = document.querySelector(`[data-comment-id="${commentId}"]`);
                commentElement.style.opacity = '0';
                commentElement.style.transform = 'translateX(100px)';
                setTimeout(() => {
                    commentElement.remove();
                    
                    // Vérifier s'il reste des commentaires
                    if (commentsContainer.children.length === 0) {
                        commentsContainer.innerHTML = `
                            <div class="text-center py-12">
                                <div class="text-gray-400 mb-4">
                                    <svg class="mx-auto h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-medium text-white mb-2">Aucun commentaire</h3>
                                <p class="text-gray-400">Soyez le premier à commenter cette tâche !</p>
                            </div>
                        `;
                    }
                }, 300);
                
                showNotification('Commentaire supprimé !', 'success');
            } else {
                showNotification('Erreur: ' + (data.error || 'Erreur inconnue'), 'error');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            showNotification('Erreur réseau', 'error');
        });
    };
    
    window.closeEditModal = function() {
        const modal = document.getElementById('edit-modal');
        modal.classList.add('hidden');
    };
    
    function generateCommentHtml(comment, level) {
        const marginLeft = level * 20;
        return `
            <div class="comment-item" data-comment-id="${comment.id}" style="margin-left: ${marginLeft}px;">
                <div class="bg-gray-700 rounded-lg p-4 border border-gray-600 hover:border-gray-500 transition-all duration-300">
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white text-sm font-semibold">
                                ${comment.user.name.charAt(0).toUpperCase()}
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center space-x-2">
                                    <span class="font-medium text-white">${comment.user.name}</span>
                                    <span class="text-xs text-gray-400 comment-time">${comment.formatted_time}</span>
                                    ${comment.is_edited ? '<span class="text-xs text-gray-500">(modifié)</span>' : ''}
                                </div>
                                <div class="flex items-center space-x-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                    ${comment.is_own_comment ? `
                                        <button onclick="editComment(${comment.id}, '${comment.content.replace(/'/g, "\\'")}')" 
                                                class="text-xs text-gray-400 hover:text-white transition-colors">
                                            ✏️ Modifier
                                        </button>
                                        <button onclick="deleteComment(${comment.id})" 
                                                class="text-xs text-red-400 hover:text-red-300 transition-colors">
                                            🗑️ Supprimer
                                        </button>
                                    ` : `
                                        <button onclick="replyToComment(${comment.id}, '${comment.user.name}')" 
                                                class="text-xs text-gray-400 hover:text-white transition-colors">
                                            💬 Répondre
                                        </button>
                                    `}
                                </div>
                            </div>
                            <div class="comment-content text-gray-300 leading-relaxed">
                                ${comment.content.replace(/\n/g, '<br>')}
                            </div>
                            
                            <!-- Formulaire de réponse (caché par défaut) -->
                            <div id="reply-form-${comment.id}" class="hidden mt-4">
                                <textarea 
                                    placeholder="Répondre à ${comment.user.name}..."
                                    class="w-full bg-gray-600 border border-gray-500 rounded-lg px-3 py-2 text-white placeholder-gray-400 resize-none focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500"
                                    rows="2"
                                    maxlength="2000"
                                ></textarea>
                                <div class="flex justify-end space-x-2 mt-2">
                                    <button onclick="document.getElementById('reply-form-${comment.id}').classList.add('hidden')" 
                                            class="text-xs text-gray-400 hover:text-white transition-colors">
                                        Annuler
                                    </button>
                                    <button onclick="submitReply(${comment.id})" 
                                            class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-xs font-medium transition-colors">
                                        Répondre
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Conteneur pour les réponses -->
                    <div class="replies-container mt-4 space-y-4">
                        ${comment.replies ? comment.replies.map(reply => generateCommentHtml(reply, level + 1)).join('') : ''}
                    </div>
                </div>
            </div>
        `;
    }
    
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
        }, 3000);
    }
});
</script>
@endsection 