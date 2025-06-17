<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Sprint;
use Illuminate\Http\Request;

class SprintController extends Controller
{
    public function index(Project $project)
    {
        $sprints = $project->sprints()->latest()->paginate(10);
        return view('sprints.index', compact('project', 'sprints'));
    }

    public function create(Project $project)
    {
        return view('sprints.create', compact('project'));
    }

    public function store(Request $request, Project $project)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'goal' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:planned,active,completed',
        ]);
        $validated['project_id'] = $project->id;
        $sprint = Sprint::create($validated);
        return redirect()->route('projects.sprints.index', $project)->with('success', 'Sprint créé !');
    }

    public function show(Project $project, Sprint $sprint)
    {
        // Optionnel : vérifier que le sprint appartient bien au projet
        return view('sprints.show', compact('project', 'sprint'));
    }

    public function edit(Project $project, Sprint $sprint)
    {
        return view('sprints.edit', compact('project', 'sprint'));
    }

    public function update(Request $request, Project $project, Sprint $sprint)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'goal' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:planned,active,completed',
        ]);
        $sprint->update($validated);
        return redirect()->route('projects.sprints.index', $project)->with('success', 'Sprint mis à jour !');
    }

    public function destroy(Project $project, Sprint $sprint)
    {
        $sprint->delete();
        return redirect()->route('projects.sprints.index', $project)->with('success', 'Sprint supprimé.');
    }
}
