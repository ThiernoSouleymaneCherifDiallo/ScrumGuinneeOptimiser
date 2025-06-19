<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouvelle tâche assignée : {{ $task->title }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        .header .project-name {
            font-size: 16px;
            opacity: 0.9;
            margin-top: 5px;
        }
        .content {
            padding: 30px;
        }
        .assignment-info {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 25px;
            text-align: center;
        }
        .assignment-info h2 {
            margin: 0 0 10px 0;
            font-size: 20px;
        }
        .assignment-info p {
            margin: 0;
            opacity: 0.9;
        }
        .task-info {
            background-color: #f8f9fa;
            border-left: 4px solid #667eea;
            padding: 20px;
            margin-bottom: 25px;
            border-radius: 0 8px 8px 0;
        }
        .task-title {
            font-size: 18px;
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 10px;
        }
        .task-description {
            color: #718096;
            margin-bottom: 15px;
            line-height: 1.6;
        }
        .task-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            font-size: 14px;
            color: #718096;
        }
        .meta-item {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .action-buttons {
            text-align: center;
            margin-top: 30px;
        }
        .action-button {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            padding: 12px 24px;
            border-radius: 6px;
            font-weight: 500;
            margin: 0 10px;
        }
        .secondary-button {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }
        .footer {
            background-color: #f8f9fa;
            padding: 20px 30px;
            text-align: center;
            font-size: 12px;
            color: #718096;
            border-top: 1px solid #e2e8f0;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 500;
            text-transform: uppercase;
        }
        .status-todo { background-color: #fed7d7; color: #c53030; }
        .status-in_progress { background-color: #bee3f8; color: #2b6cb0; }
        .status-review { background-color: #fef5e7; color: #d69e2e; }
        .status-done { background-color: #c6f6d5; color: #2f855a; }
        .priority-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 500;
        }
        .priority-urgent { background-color: #fed7d7; color: #c53030; }
        .priority-high { background-color: #feb2b2; color: #c53030; }
        .priority-medium { background-color: #fef5e7; color: #d69e2e; }
        .priority-low { background-color: #c6f6d5; color: #2f855a; }
        .urgency-indicator {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%);
            color: white;
            padding: 10px 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            text-align: center;
            font-weight: 500;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎯 Nouvelle tâche assignée</h1>
            <div class="project-name">{{ $project->name }}</div>
        </div>

        <div class="content">
            <div class="assignment-info">
                <h2>Félicitations {{ $assignedUser->name }} !</h2>
                <p>Vous avez été assigné(e) à une nouvelle tâche</p>
                @if($assignedBy)
                <p><small>Assigné par {{ $assignedBy->name }}</small></p>
                @endif
            </div>

            @if($task->priority === 'urgent' || $task->priority === 'high')
            <div class="urgency-indicator">
                ⚡ Tâche prioritaire - Action requise rapidement
            </div>
            @endif

            <div class="task-info">
                <div class="task-title">{{ $task->title }}</div>
                @if($task->description)
                <div class="task-description">
                    {{ Str::limit($task->description, 200) }}
                </div>
                @endif
                <div class="task-meta">
                    <div class="meta-item">
                        <strong>Statut:</strong>
                        <span class="status-badge status-{{ $task->status }}">
                            @switch($task->status)
                                @case('todo') À faire
                                @case('in_progress') En cours
                                @case('review') En révision
                                @case('done') Terminé
                                @default {{ ucfirst($task->status) }}
                            @endswitch
                        </span>
                    </div>
                    <div class="meta-item">
                        <strong>Priorité:</strong>
                        <span class="priority-badge priority-{{ $task->priority }}">
                            @switch($task->priority)
                                @case('urgent') Urgente
                                @case('high') Haute
                                @case('medium') Moyenne
                                @case('low') Basse
                                @default {{ ucfirst($task->priority) }}
                            @endswitch
                        </span>
                    </div>
                    @if($task->type)
                    <div class="meta-item">
                        <strong>Type:</strong> {{ ucfirst($task->type) }}
                    </div>
                    @endif
                    @if($task->due_date)
                    <div class="meta-item">
                        <strong>Échéance:</strong> 
                        <span style="{{ $task->due_date < now() ? 'color: #e53e3e; font-weight: bold;' : '' }}">
                            {{ $task->due_date->format('d/m/Y') }}
                            @if($task->due_date < now())
                                (En retard !)
                            @endif
                        </span>
                    </div>
                    @endif
                    @if($task->story_points)
                    <div class="meta-item">
                        <strong>Points:</strong> {{ $task->story_points }}
                    </div>
                    @endif
                </div>
            </div>

            <div class="action-buttons">
                <a href="{{ route('projects.tasks.show', [$project, $task]) }}" class="action-button">
                    📋 Voir la tâche
                </a>
                <a href="{{ route('projects.show', $project) }}" class="action-button secondary-button">
                    🏠 Aller au projet
                </a>
            </div>
        </div>

        <div class="footer">
            <p>Vous recevez cet email car vous avez été assigné(e) à cette tâche.</p>
            <p>ScrumGuiOpt - Gestion de projet agile</p>
        </div>
    </div>
</body>
</html> 