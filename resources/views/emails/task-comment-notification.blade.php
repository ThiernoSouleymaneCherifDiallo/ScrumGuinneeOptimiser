<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouveau commentaire sur {{ $task->title }}</title>
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
        .comment-section {
            background-color: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 25px;
        }
        .comment-author {
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 10px;
        }
        .comment-time {
            font-size: 12px;
            color: #718096;
            margin-bottom: 15px;
        }
        .comment-content {
            background-color: #f7fafc;
            border-radius: 6px;
            padding: 15px;
            border-left: 3px solid #667eea;
            font-size: 14px;
            line-height: 1.6;
        }
        .action-button {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            padding: 12px 24px;
            border-radius: 6px;
            font-weight: 500;
            margin-top: 20px;
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
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>💬 Nouveau commentaire</h1>
            <div class="project-name">{{ $project->name }}</div>
        </div>

        <div class="content">
            <div class="task-info">
                <div class="task-title">{{ $task->title }}</div>
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
                    @if($task->assignee)
                    <div class="meta-item">
                        <strong>Assigné à:</strong> {{ $task->assignee->name }}
                    </div>
                    @endif
                    @if($task->due_date)
                    <div class="meta-item">
                        <strong>Échéance:</strong> {{ $task->due_date->format('d/m/Y') }}
                    </div>
                    @endif
                </div>
            </div>

            <div class="comment-section">
                <div class="comment-author">{{ $commentAuthor->name }} a commenté :</div>
                <div class="comment-time">{{ $comment->created_at->format('d/m/Y à H:i') }}</div>
                <div class="comment-content">
                    {!! nl2br(e($comment->content)) !!}
                </div>
            </div>

            <a href="{{ route('tasks.comments.index', [$project, $task]) }}" class="action-button">
                Voir le commentaire et répondre
            </a>
        </div>

        <div class="footer">
            <p>Vous recevez cet email car vous êtes impliqué dans cette tâche.</p>
            <p>ScrumGuiOpt - Gestion de projet agile</p>
        </div>
    </div>
</body>
</html> 