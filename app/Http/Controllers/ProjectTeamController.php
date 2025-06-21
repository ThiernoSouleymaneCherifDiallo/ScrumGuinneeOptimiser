<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ProjectTeamController extends Controller
{
    public function index(Project $project)
    {
        // Vérifier que l'utilisateur est admin du projet
        $this->authorize('manageTeam', $project);
        
        // Récupérer tous les membres du projet avec leurs rôles
        $members = $project->members()->withPivot('role')->get();
        
        // Récupérer les statistiques des membres
        $memberStats = [];
        foreach ($members as $member) {
            $memberStats[$member->id] = [
                'tasks_assigned' => $project->tasks()->where('assignee_id', $member->id)->count(),
                'tasks_completed' => $project->tasks()->where('assignee_id', $member->id)->where('status', 'done')->count(),
                'story_points' => $project->tasks()->where('assignee_id', $member->id)->sum('story_points'),
            ];
        }
        
        return view('projects.team.index', compact('project', 'members', 'memberStats'));
    }
    
    public function search(Request $request, Project $project)
    {
        // Vérifier que l'utilisateur est admin du projet
        $this->authorize('manageTeam', $project);
        
        $query = $request->input('query');
        
        if (empty($query)) {
            return response()->json([]);
        }
        
        // Rechercher les utilisateurs qui ne sont pas déjà membres du projet
        $existingMemberIds = $project->members()->pluck('users.id')->toArray();
        
        $users = User::where(function($q) use ($query) {
            $q->where('name', 'like', "%{$query}%")
              ->orWhere('email', 'like', "%{$query}%");
        })
        ->whereNotIn('id', $existingMemberIds)
        ->where('id', '!=', Auth::id()) // Exclure l'utilisateur actuel
        ->limit(10)
        ->get(['id', 'name', 'email']);
        
        return response()->json($users);
    }
    
    public function addMember(Request $request, Project $project)
    {
        // Vérifier que l'utilisateur est admin du projet
        $this->authorize('manageTeam', $project);
        
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'role' => ['required', Rule::in(['admin', 'member', 'viewer'])],
        ]);
        
        // Vérifier que l'utilisateur n'est pas déjà membre
        if ($project->members()->where('user_id', $request->user_id)->exists()) {
            return back()->withErrors(['user_id' => 'Cet utilisateur est déjà membre du projet.']);
        }
        
        // Ajouter le membre
        $project->members()->attach($request->user_id, [
            'role' => $request->role,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        return back()->with('message', 'Membre ajouté avec succès !');
    }
    
    public function updateMemberRole(Request $request, Project $project, User $user)
    {
        // Vérifier que l'utilisateur est admin du projet
        $this->authorize('manageTeam', $project);
        
        $request->validate([
            'role' => ['required', Rule::in(['admin', 'member', 'viewer'])],
        ]);
        
        // Vérifier que l'utilisateur est membre du projet
        if (!$project->members()->where('user_id', $user->id)->exists()) {
            return back()->withErrors(['user_id' => 'Cet utilisateur n\'est pas membre du projet.']);
        }
        
        // Empêcher de changer le rôle du propriétaire du projet
        if ($project->owner_id === $user->id) {
            return back()->withErrors(['role' => 'Impossible de modifier le rôle du propriétaire du projet.']);
        }
        
        // Mettre à jour le rôle
        $project->members()->updateExistingPivot($user->id, [
            'role' => $request->role,
            'updated_at' => now(),
        ]);
        
        return back()->with('message', 'Rôle mis à jour avec succès !');
    }
    
    public function removeMember(Project $project, User $user)
    {
        // Vérifier que l'utilisateur est admin du projet
        $this->authorize('manageTeam', $project);
        
        // Empêcher de supprimer le propriétaire du projet
        if ($project->owner_id === $user->id) {
            return back()->withErrors(['user_id' => 'Impossible de supprimer le propriétaire du projet.']);
        }
        
        // Supprimer le membre
        $project->members()->detach($user->id);
        
        return back()->with('message', 'Membre supprimé avec succès !');
    }
} 