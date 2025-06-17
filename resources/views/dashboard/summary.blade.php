@extends('layouts.app')

@section('content')
<div class="min-h-screen py-6 ml-64 bg-gray-900 text-white">
    <header class="bg-gray-800 shadow-sm mb-6">
        <div class="py-4 px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold">Résumé - {{ $project->name }}</h1>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('projects.show', $project) }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">
                        Retour au projet
                    </a>
                </div>
            </div>
        </div>
    </header>

    <div class="px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Vue d'ensemble de l'état -->
            <div class="bg-gray-800 shadow rounded-lg p-6">
                <h4 class="text-lg font-medium">Vue d'ensemble de l'état</h4>
                <p class="mt-1 text-sm text-gray-400">Obtenez un instantané de l'état de vos tickets. <a href="{{ route('projects.show', $project) }}" class="text-blue-400 hover:text-blue-300">Afficher tous les tickets</a></p>
                <div class="mt-4 flex items-center justify-center">
                    <canvas id="taskChart" width="200" height="200"></canvas>
                </div>
                <div class="mt-4 text-center">
                    <p>Total: {{ $taskStats['total'] ?? 0 }} tickets</p>
                    <p class="text-pink-400">À faire: {{ $taskStats['to_do'] ?? 0 }}</p>
                    <p class="text-orange-400">En cours: {{ $taskStats['in_progress'] ?? 0 }}</p>
                    <p class="text-blue-400">Terminée: {{ $taskStats['done'] ?? 0 }}</p>
                </div>
            </div>

        <!-- Charge de travail de l'équipe -->
        <div class="bg-gray-800 shadow rounded-lg p-6">
            <h4 class="text-lg font-medium">Charge de travail de l'équipe</h4>
            <p class="mt-1 text-sm text-gray-400">Surveillez la capacité de votre équipe.</p>
            <div class="mt-4 space-y-2">
                @foreach ($assignedStats as $stat)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <span class="w-8 h-8 rounded-full bg-gray-600 flex items-center justify-center mr-2">
                                {{ $stat['name'] === 'Non assignée' ? 'N/A' : substr($stat['name'], 0, 2) }}
                            </span>
                            <span>{{ $stat['name'] }}</span>
                        </div>
                        <div class="w-1/2">
                            <div class="bg-gray-700 h-4 rounded-full">
                                <div class="bg-gray-500 h-4 rounded-full" style="width: {{ $stat['percentage'] }}%"></div>
                            </div>
                        </div>
                        <span class="ml-2">{{ $stat['percentage'] }}%</span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Epic : avancement -->
        <div class="bg-gray-800 shadow rounded-lg p-6 col-span-1 md:col-span-2">
            <h4 class="text-lg font-medium">Epic : avancement</h4>
            <p class="mt-1 text-sm text-gray-400">Découvrez l'avancement de vos epics. <a href="#" class="text-blue-400 hover:text-blue-300">Voir toutes les epics</a></p>
            <div class="mt-4 space-y-2">
                @forelse ($epics as $epic)
                    <div class="flex items-center justify-between">
                        <span>{{ $epic->name }}</span>
                        <div class="w-1/2">
                            <div class="bg-gray-700 h-4 rounded-full">
                                <div class="bg-green-500 h-4 rounded-full" style="width: {{ $epic->progress ?? 0 }}%"></div>
                            </div>
                        </div>
                        <span>{{ $epic->progress ?? 0 }}%</span>
                    </div>
                @empty
                    <p class="text-sm text-gray-500">Aucun epic pour le moment.</p>
                @endforelse
            </div>
        </div>
    </div>

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            const ctx = document.getElementById('taskChart').getContext('2d');
            const taskChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['À faire', 'En cours', 'Terminée'],
                    datasets: [{
                        data: [{{ $taskStats['to_do'] ?? 0 }}, {{ $taskStats['in_progress'] ?? 0 }}, {{ $taskStats['done'] ?? 0 }}],
                        backgroundColor: ['#f472b6', '#f59e0b', '#3b82f6'],
                        borderWidth: 0
                    }]
                },
                options: {
                    cutout: '70%',
                    plugins: {
                        legend: { display: false },
                        tooltip: { enabled: true }
                    }
                }
            });
        </script>
</div>
@endsection