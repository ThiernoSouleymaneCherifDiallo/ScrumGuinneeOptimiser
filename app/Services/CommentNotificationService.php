<?php

namespace App\Services;

use App\Mail\TaskCommentNotification;
use App\Models\Task;
use App\Models\TaskComment;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class CommentNotificationService
{
    /**
     * Envoie les notifications pour un nouveau commentaire
     */
    public static function sendNotifications(TaskComment $comment): void
    {
        $task = $comment->task;
        $recipients = TaskCommentNotification::getRecipients($task, $comment);

        foreach ($recipients as $recipient) {
            // Vérifier que l'utilisateur a un email
            if ($recipient->email) {
                try {
                    Mail::to($recipient->email)
                        ->send(new TaskCommentNotification($task, $comment, $comment->user, $recipient));
                } catch (\Exception $e) {
                    // Log l'erreur mais ne pas faire échouer le processus
                    \Log::error('Erreur lors de l\'envoi de notification de commentaire', [
                        'recipient_id' => $recipient->id,
                        'comment_id' => $comment->id,
                        'error' => $e->getMessage()
                    ]);
                }
            }
        }
    }

    /**
     * Envoie les notifications pour une réponse à un commentaire
     */
    public static function sendReplyNotifications(TaskComment $reply): void
    {
        // Pour les réponses, on notifie aussi l'auteur du commentaire parent
        if ($reply->parent_id) {
            $parentComment = $reply->parent;
            $task = $reply->task;
            
            $recipients = TaskCommentNotification::getRecipients($task, $reply);
            
            // Ajouter l'auteur du commentaire parent s'il n'est pas l'auteur de la réponse
            if ($parentComment->user_id !== $reply->user_id) {
                $parentAuthor = $parentComment->user;
                if ($parentAuthor && $parentAuthor->email) {
                    $recipients[] = $parentAuthor;
                }
            }

            // Supprimer les doublons
            $recipients = array_unique($recipients, SORT_REGULAR);

            foreach ($recipients as $recipient) {
                if ($recipient->email) {
                    try {
                        Mail::to($recipient->email)
                            ->send(new TaskCommentNotification($task, $reply, $reply->user, $recipient));
                    } catch (\Exception $e) {
                        \Log::error('Erreur lors de l\'envoi de notification de réponse', [
                            'recipient_id' => $recipient->id,
                            'reply_id' => $reply->id,
                            'error' => $e->getMessage()
                        ]);
                    }
                }
            }
        }
    }
} 