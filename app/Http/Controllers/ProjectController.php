<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index(): Renderable
    {
        $projects = Project::paginate(1);
        return view('projects.index', compact('projects'));
    }

    public function create(): Renderable
    {
        $project = new Project;
        $title = __('Crear proyecto');
        $action = route('projects.store');
        $buttonText = __('Crear proyecto');
        return view('projects.form', compact('project', 'title', 'action', 'buttonText'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|unique:projects,name|string|max:100',
            'description' => 'required|string|max:1000',
        ]);
        Project::create([
            'name' => $request->string('name'),
            'description' => $request->string('description'),
        ]);
        return redirect()->route('projects.index');
    }

    public function show(Project $project): Renderable
    {
        $project->load('user:id,name');
        return view('projects.show', compact('project'));
    }

    public function edit(Project $project): Renderable
    {
        $title = __('Editar proyecto');
        $action = route('projects.update', $project);
        $buttonText = __('Actualizar proyecto');
        $method = 'PUT';
        return view('projects.form', compact('project', 'title', 'action', 'buttonText', 'method'));
    }

    public function update(Request $request, Project $project): RedirectResponse
    {
        $request->validate([
            'name' => 'required|unique:projects,name,' . $project->id . '|string|max:100',
            'description' => 'required|string|max:1000',
        ]);
        $project->update([
            'name' => $request->string('name'),
            'description' => $request->string('description'),
        ]);
        return redirect()->route('projects.index');
    }

    public function destroy(Project $project): RedirectResponse
    {
        $project->delete();
        return redirect()->route('projects.index');
    }
}
