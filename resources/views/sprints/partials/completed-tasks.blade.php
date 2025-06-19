@forelse($tasks as $task)
    <div class="bg-jira-dark rounded-lg p-3 border border-jira hover:bg-jira-card-hover transition-all duration-300">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <div class="w-2 h-2 rounded-full bg-green-500"></div>
                <span class="text-sm text-white font-medium">{{ $task->title }}</span>
            </div>
            <div class="flex items-center space-x-2">
                @if($task->assignee)
                    <div class="w-6 h-6 bg-blue-500 rounded-full flex items-center justify-center text-xs text-white">
                        {{ substr($task->assignee->name, 0, 1) }}
                    </div>
                @endif
                @if($task->story_points)
                    <span class="text-xs bg-gray-600 text-white px-2 py-1 rounded">{{ $task->story_points }} pts</span>
                @endif
            </div>
        </div>
    </div>
@empty
    <div class="text-center text-jira-gray text-sm py-4">
        Aucune tâche complétée
    </div>
@endforelse 