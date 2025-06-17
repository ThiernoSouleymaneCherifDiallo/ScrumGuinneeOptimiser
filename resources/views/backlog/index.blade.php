@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-2xl font-bold">Backlog - {{ $project->name }}</h1>
        <div class="space-x-2">
            <a href="{{ route('sprints.create', $project) }}" 
               class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                Nouveau Sprint
            </a>
        </div>
    </div>

    <!-- Backlog et Sprints -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6" id="board">
        <!-- Colonne Backlog -->
        <div class="bg-gray-100 rounded-lg p-4">
            <h2 class="font-semibold text-lg mb-4">Backlog</h2>
            <div class="space-y-3" id="backlog">
                @forelse($backlogTasks as $task)
                    @include('tasks.partials.task-card', ['task' => $task])
                @empty
                    <p class="text-gray-500 text-sm">Aucune tâche dans le backlog</p>
                @endforelse
            </div>
        </div>

        <!-- Colonnes des Sprints -->
        @foreach($sprints as $sprint)
            <div class="bg-gray-50 rounded-lg p-4 border" data-sprint-id="{{ $sprint->id }}">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-semibold">{{ $sprint->name }}</h3>
                    <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded">
                        {{ $sprint->tasks->count() }} tâches
                    </span>
                </div>
                <div class="space-y-3 sprint-tasks" id="sprint-{{ $sprint->id }}">
                    @foreach($sprint->tasks as $task)
                        @include('tasks.partials.task-card', ['task' => $task])
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.14.0/Sortable.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Activer le drag & drop pour le backlog et chaque sprint
        new Sortable(document.getElementById('backlog'), {
            group: 'tasks',
            animation: 150,
            onEnd: function(evt) {
                updateTaskSprint(evt.item.dataset.taskId, evt.to.dataset.sprintId || null);
            }
        });

        document.querySelectorAll('.sprint-tasks').forEach(function(element) {
            new Sortable(element, {
                group: 'tasks',
                animation: 150,
                onEnd: function(evt) {
                    const sprintId = evt.to.closest('[data-sprint-id]').dataset.sprintId;
                    updateTaskSprint(evt.item.dataset.taskId, sprintId || null);
                }
            });
        });

        function updateTaskSprint(taskId, sprintId) {
            fetch(`/api/tasks/${taskId}/move`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ sprint_id: sprintId })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erreur réseau');
                }
                return response.json();
            })
            .then(data => {
                if (!data.success) {
                    console.error('Erreur lors du déplacement de la tâche:', data.message);
                    // Recharger la page pour récupérer l'état correct
                    window.location.reload();
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                window.location.reload();
            });
        }
    });
</script>
@endpush
@endsection
