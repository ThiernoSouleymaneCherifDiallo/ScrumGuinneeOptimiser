<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Gate;

use Illuminate\Routing\Controller as BaseController;

class ProjectController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
       $projects = \App\Models\Project::with('owner')
        ->where('user_id', Auth::id())
        ->latest()
        ->get();
        return view('projects.index', compact('projects'));
    }

    public function create()
    {
        return view('projects.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'description' => 'nullable|string',
            'key' => 'required|unique:projects,key|max:10|alpha_num',
        ]);

        // Ajout du owner_id automatiquement
        $validated['owner_id'] = Auth::id();
        
        try {
            $project = Project::create($validated);
            $project->members()->attach(Auth::id(), ['role' => 'admin']);

            return redirect()->route('projects.show', $project)
                            ->with('success', 'Projet créé avec succès.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la création du projet.');
        }
    }

    public function show(Project $project)
    {
        if (!Gate::allows('view', $project)) {
            abort(403, 'Non autorisé.');
        }

        $project->load(['members', 'owner']);
        return view('projects.show', compact('project'));
    }

    public function edit(Project $project)
    {
        if (!Gate::allows('update', $project)) {
            abort(403, 'Non autorisé.');
        }

        return view('projects.edit', compact('project'));
    }

    public function update(Request $request, Project $project)
    {
        if (!Gate::allows('update', $project)) {
            abort(403, 'Non autorisé.');
        }

        $validated = $request->validate([
            'name' => 'required|max:255',
            'description' => 'nullable|string',
        ]);

        try {
            $project->update($validated);
            return redirect()->route('projects.show', $project)
                            ->with('success', 'Projet mis à jour avec succès.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la mise à jour du projet.');
        }
    }

    public function destroy(Project $project)
    {
        if (!Gate::allows('delete', $project)) {
            abort(403, 'Non autorisé.');
        }

        try {
            $project->delete();
            return redirect()->route('projects.index')
                            ->with('success', 'Projet supprimé avec succès.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la suppression du projet.');
        }
    }
}

