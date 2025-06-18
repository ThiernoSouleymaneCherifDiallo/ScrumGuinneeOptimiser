<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
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
        $projects = \App\Models\Project::with(['owner', 'sprints', 'tasks'])
            ->withCount(['sprints', 'tasks'])
            ->where('owner_id', Auth::id())
            ->latest()
            ->paginate(10);

        // Calculer les métriques globales
        $activeSprintsCount = \App\Models\Sprint::whereHas('project', function ($query) {
            $query->where('owner_id', Auth::id());
        })->where('status', 'active')->count();

        $openTasksCount = \App\Models\Task::whereHas('project', function ($query) {
            $query->where('owner_id', Auth::id());
        })->where('status', '!=', 'done')->count();

        return view('projects.index', compact('projects', 'activeSprintsCount', 'openTasksCount'));
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

        // Ajout du owner_id et user_id automatiquement
        $validated['owner_id'] = Auth::id();
        $validated['user_id'] = Auth::id(); // Ajouter user_id aussi
        $validated['status'] = 'active'; // Définir un statut par défaut
        
        try {
            $project = Project::create($validated);
            
            // Ajouter l'utilisateur comme membre du projet
            $project->members()->attach(Auth::id(), ['role' => 'admin']);

            return redirect()->route('projects.index')
                            ->with('success', 'Projet "' . $project->name . '" créé avec succès.');
        } catch (\Exception $e) {
            Log::error('Erreur création projet: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Erreur lors de la création du projet: ' . $e->getMessage());
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

