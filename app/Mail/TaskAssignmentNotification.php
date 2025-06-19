<?php

namespace App\Mail;

use App\Models\Task;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TaskAssignmentNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $task;
    public $assignedUser;
    public $assignedBy;

    /**
     * Create a new message instance.
     */
    public function __construct(Task $task, User $assignedUser, User $assignedBy = null)
    {
        $this->task = $task;
        $this->assignedUser = $assignedUser;
        $this->assignedBy = $assignedBy;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $projectName = $this->task->project->name;
        $taskTitle = $this->task->title;

        return new Envelope(
            subject: "[{$projectName}] Nouvelle tâche assignée : {$taskTitle}",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.task-assignment-notification',
            with: [
                'task' => $this->task,
                'assignedUser' => $this->assignedUser,
                'assignedBy' => $this->assignedBy,
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
}
