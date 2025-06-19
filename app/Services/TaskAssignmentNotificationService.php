<?php

namespace App\Services;

use App\Mail\TaskAssignmentNotification;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class TaskAssignmentNotificationService
{
    /**
     * Envoie une notification d'assignation
     */
    public static function sendAssignmentNotification(Task $task, User $assignedUser, User $assignedBy = null): void
    {
        // Vérifier que l'utilisateur assigné a un email
        if ($assignedUser->email) {
            try {
                Mail::to($assignedUser->email)
                    ->send(new TaskAssignmentNotification($task, $assignedUser, $assignedBy));
            } catch (\Exception $e) {
                // Log l'erreur mais ne pas faire échouer le processus
                Log::error('Erreur lors de l\'envoi de notification d\'assignation', [
                    'assigned_user_id' => $assignedUser->id,
                    'task_id' => $task->id,
                    'assigned_by_id' => $assignedBy ? $assignedBy->id : null,
                    'error' => $e->getMessage()
                ]);
            }
        }
    }

    /**
     * Envoie une notification de changement d'assignation
     */
    public static function sendReassignmentNotification(Task $task, User $newAssignee, User $assignedBy = null): void
    {
        // Vérifier que l'utilisateur assigné a un email
        if ($newAssignee->email) {
            try {
                Mail::to($newAssignee->email)
                    ->send(new TaskAssignmentNotification($task, $newAssignee, $assignedBy));
            } catch (\Exception $e) {
                Log::error('Erreur lors de l\'envoi de notification de réassignation', [
                    'new_assignee_id' => $newAssignee->id,
                    'task_id' => $task->id,
                    'assigned_by_id' => $assignedBy ? $assignedBy->id : null,
                    'error' => $e->getMessage()
                ]);
            }
        }
    }
} 