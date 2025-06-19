<?php

namespace App\Mail;

use App\Models\Task;
use App\Models\TaskComment;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TaskCommentNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $task;
    public $comment;
    public $commentAuthor;
    public $recipient;

    /**
     * Create a new message instance.
     */
    public function __construct(Task $task, TaskComment $comment, User $commentAuthor, User $recipient)
    {
        $this->task = $task;
        $this->comment = $comment;
        $this->commentAuthor = $commentAuthor;
        $this->recipient = $recipient;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $projectName = $this->task->project->name;
        $taskTitle = $this->task->title;
        $authorName = $this->commentAuthor->name;

        return new Envelope(
            subject: "[{$projectName}] Nouveau commentaire sur la tâche : {$taskTitle}",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.task-comment-notification',
            with: [
                'task' => $this->task,
                'comment' => $this->comment,
                'commentAuthor' => $this->commentAuthor,
                'recipient' => $this->recipient,
                'project' => $this->task->project,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }

    /**
     * Détermine qui doit recevoir une notification pour ce commentaire
     */
    public static function getRecipients(Task $task, TaskComment $comment): array
    {
        $recipients = [];
        $commentAuthor = $comment->user;

        // 1. Le propriétaire du projet (sauf s'il est l'auteur du commentaire)
        if ($task->project->owner_id !== $commentAuthor->id) {
            $projectOwner = $task->project->owner;
            if ($projectOwner) {
                $recipients[] = $projectOwner;
            }
        }

        // 2. L'assigné de la tâche (sauf s'il est l'auteur du commentaire)
        if ($task->assignee_id && $task->assignee_id !== $commentAuthor->id) {
            $assignee = $task->assignee;
            if ($assignee) {
                $recipients[] = $assignee;
            }
        }

        // 3. Le rapporteur de la tâche (sauf s'il est l'auteur du commentaire)
        if ($task->reporter_id && $task->reporter_id !== $commentAuthor->id) {
            $reporter = $task->reporter;
            if ($reporter) {
                $recipients[] = $reporter;
            }
        }

        // Supprimer les doublons
        $recipients = array_unique($recipients, SORT_REGULAR);

        return $recipients;
    }
}
