<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ProjectMemberController extends Controller
{
    // Remove the constructor with middleware
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    public function create(Project $project)
    {
        if (!Gate::allows('update', $project)) {
            abort(403, 'Non autorisé.');
        }

        $users = User::whereDoesntHave('memberProjects', function ($query) use ($project) {
            $query->where('project_id', $project->id);
        })->get();

        return view('projects.members.create', compact('project', 'users'));
    }

    public function store(Request $request, Project $project)
    {
        if (!Gate::allows('update', $project)) {
            abort(403, 'Non autorisé.');
        }

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'role' => 'required|in:member,admin'
        ]);

        try {
            $project->members()->attach($validated['user_id'], ['role' => $validated['role']]);
            return redirect()->route('projects.index', $project)
                            ->with('success', 'Membre ajouté avec succès.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de l\'ajout du membre.');
        }
    }

    public function destroy(Project $project, User $member)
    {
        if (!Gate::allows('update', $project)) {
            abort(403, 'Non autorisé.');
        }

        try {
            $project->members()->detach($member->id);
            return redirect()->route('projects.show', $project)
                            ->with('success', 'Membre retiré avec succès.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors du retrait du membre.');
        }
    }
}