<?php


namespace App\Policies;

use App\Models\Project;
use App\Models\User;

class ProjectPolicy
{
    public function viewAny(User $user): bool
    {
        return true; // Tous les utilisateurs authentifiés peuvent voir la liste
    }

    public function view(User $user, Project $project): bool
    {
        return $user->id === $project->owner_id || $project->members()->where('user_id', $user->id)->exists();
    }

    public function create(User $user): bool
    {
        return true; // Tous les utilisateurs authentifiés peuvent créer
    }

    public function update(User $user, Project $project): bool
    {
        return $user->id === $project->owner_id || 
               $project->members()->where('user_id', $user->id)
                      ->where('role', 'admin')
                      ->exists();
    }

    public function delete(User $user, Project $project): bool
    {
        return $user->id === $project->owner_id;
    }

    public function addMember(User $user, Project $project): bool
    {
        return $user->id === $project->owner_id || 
               $project->members()->where('user_id', $user->id)
                      ->where('role', 'admin')
                      ->exists();
    }
}

