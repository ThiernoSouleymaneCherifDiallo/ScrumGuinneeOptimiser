@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <div class="bg-gray-800 rounded-lg shadow-lg overflow-hidden">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-semibold text-white">Liste des tâches</h2>
                <a href="{{ route('projects.tasks.create', $project) }}"
                   class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                    Nouvelle tâche
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-700">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 bg-gray-700 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Titre</th>
                            <th class="px-6 py-3 bg-gray-700 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-3 bg-gray-700 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Priorité</th>
                            <th class="px-6 py-3 bg-gray-700 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Statut</th>
                            <th class="px-6 py-3 bg-gray-700 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Assigné à</th>
                            <th class="px-6 py-3 bg-gray-700 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Date d'échéance</th>
                            <th class="px-6 py-3 bg-gray-700 text-right text-xs font-medium text-gray-300 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-gray-800 divide-y divide-gray-700">
                        @forelse($tasks as $task)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                    {{ $task->title }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        @switch($task->type)
                                            @case('bug')
                                                bg-red-800 text-red-100
                                                @break
                                            @case('feature')
                                                bg-blue-800 text-blue-100
                                                @break
                                            @case('improvement')
                                                bg-green-800 text-green-100
                                                @break
                                            @default
                                                bg-gray-700 text-gray-100
                                        @endswitch">
                                        {{ ucfirst($task->type) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        @switch($task->priority)
                                            @case('urgent')
                                                bg-red-800 text-red-100
                                                @break
                                            @case('high')
                                                bg-orange-800 text-orange-100
                                                @break
                                            @case('medium')
                                                bg-yellow-800 text-yellow-100
                                                @break
                                            @default
                                                bg-green-800 text-green-100
                                        @endswitch">
                                        {{ ucfirst($task->priority) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        @switch($task->status)
                                            @case('open')
                                                bg-gray-700 text-gray-100
                                                @break
                                            @case('in_progress')
                                                bg-blue-800 text-blue-100
                                                @break
                                            @case('done')
                                                bg-green-800 text-green-100
                                                @break
                                            @default
                                                bg-red-800 text-red-100
                                        @endswitch">
                                        @switch($task->status)
                                            @case('open')
                                                Ouvert
                                                @break
                                            @case('in_progress')
                                                En cours
                                                @break
                                            @case('done')
                                                Terminé
                                                @break
                                            @default
                                                Fermé
                                        @endswitch
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                    {{ $task->assignee ? $task->assignee->name : 'Non assigné' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                    {{ $task->due_date ? $task->due_date->format('d/m/Y') : 'Non définie' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('projects.tasks.edit', [$project, $task]) }}"
                                       class="text-indigo-400 hover:text-indigo-300 mr-4">Modifier</a>
                                    <form action="{{ route('projects.tasks.destroy', [$project, $task]) }}" method="POST"
                                          class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-400 hover:text-red-300"
                                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette tâche ?')">
                                            Supprimer
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-4 whitespace-nowrap text-sm text-gray-400 text-center">
                                    Aucune tâche trouvée
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection