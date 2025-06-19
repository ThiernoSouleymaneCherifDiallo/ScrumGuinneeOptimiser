@extends('layouts.app')

@section('header')
    Chat - {{ $project->name }}
@endsection

@section('content')
<div class="min-h-screen bg-jira-dark text-white p-6">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="bg-jira-card rounded-lg p-4 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold">{{ $project->name }}</h1>
                    <p class="text-jira-gray">Canal de discussion</p>
                </div>
                <a href="{{ route('projects.show', $project) }}" class="bg-gray-600 hover:bg-gray-700 px-4 py-2 rounded">
                    Retour
                </a>
            </div>
        </div>

        <!-- Messages -->
        <div id="messages-container" class="bg-jira-card rounded-lg p-4 mb-6 h-96 overflow-y-auto">
            @forelse($messages->reverse() as $message)
                <div class="mb-4 {{ $message->user_id === auth()->id() ? 'text-right' : 'text-left' }}">
                    <div class="inline-block max-w-xs lg:max-w-md px-4 py-2 rounded-lg {{ $message->user_id === auth()->id() ? 'bg-blue-600' : 'bg-gray-600' }}">
                        <div class="text-sm font-semibold">{{ $message->user->name }}</div>
                        <div>{{ $message->content }}</div>
                        <div class="text-xs opacity-75 mt-1">{{ $message->created_at->format('H:i') }}</div>
                    </div>
                </div>
            @empty
                <div class="text-center text-jira-gray">
                    <p>Aucun message</p>
                </div>
            @endforelse
        </div>

        <!-- Formulaire -->
        <div class="bg-jira-card rounded-lg p-4">
            <form id="message-form">
                @csrf
                <div class="flex space-x-4">
                    <input 
                        type="text" 
                        id="message-input" 
                        name="content" 
                        placeholder="Tapez votre message..." 
                        class="flex-1 bg-jira-input border border-jira rounded px-3 py-2 text-white"
                        required
                    >
                    <button 
                        type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 px-6 py-2 rounded font-medium"
                    >
                        Envoyer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
console.log('=== CHAT SIMPLE CHARGÉ ===');

document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM chargé!');
    
    const form = document.getElementById('message-form');
    const input = document.getElementById('message-input');
    const button = document.querySelector('#message-form button[type="submit"]');
    
    console.log('Form:', form);
    console.log('Input:', input);
    console.log('Button:', button);
    
    if (form && input && button) {
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
            
            // Changer le bouton
            button.textContent = 'Envoi...';
            button.disabled = true;
            
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
                        
                        // Ajouter le message à l'interface
                        const container = document.getElementById('messages-container');
                        const messageHtml = `
                            <div class="mb-4 text-right">
                                <div class="inline-block max-w-xs lg:max-w-md px-4 py-2 rounded-lg bg-blue-600">
                                    <div class="text-sm font-semibold">{{ auth()->user()->name }}</div>
                                    <div>${message}</div>
                                    <div class="text-xs opacity-75 mt-1">À l'instant</div>
                                </div>
                            </div>
                        `;
                        container.insertAdjacentHTML('beforeend', messageHtml);
                        
                        // Scroll en bas
                        container.scrollTop = container.scrollHeight;
                        
                        alert('Message envoyé avec succès!');
                    } else {
                        console.error('Erreur:', data);
                        alert('Erreur: ' + (data.error || 'Erreur inconnue'));
                    }
                } catch (e) {
                    console.error('Erreur parsing JSON:', e);
                    console.log('Réponse non-JSON:', text);
                    alert('Erreur: Réponse non-JSON reçue');
                }
            })
            .catch(function(error) {
                console.error('Erreur fetch:', error);
                alert('Erreur réseau: ' + error.message);
            })
            .finally(function() {
                // Restaurer le bouton
                button.textContent = 'Envoyer';
                button.disabled = false;
            });
        });
        
        console.log('Événement submit ajouté!');
    } else {
        console.error('Éléments manquants!');
    }
});

console.log('=== SCRIPT TERMINÉ ===');
</script>
@endsection 