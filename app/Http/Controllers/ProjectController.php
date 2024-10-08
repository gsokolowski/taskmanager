<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Http\Resources\ProjectCollection;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\QueryBuilder;

class ProjectController extends Controller
{
    // {{DOMAIN}}/api/projects/1
    public function show(Project $project) {
        return (new ProjectResource($project))
        ->load('tasks') // add tasks to ptoject
        ->load('members'); // add members to project
    }

    // {{DOMAIN}}/api/projects?include=tasks
    public function index(Request $request) {

        $projects = QueryBuilder::for(Project::class)
            ->allowedIncludes('tasks')
            ->paginate();

        return new ProjectCollection($projects);
        //return new ProjectCollection(Auth::user()->projects()->paginate()); // return a collection of projects
    }

    public function store(StoreProjectRequest $request) {
        $validated = $request->validated();
        $project = Auth::user()->projects()->create($validated);
        return new ProjectResource($project);
    }

    public function update(UpdateProjectRequest $request, Project $project) {
        $validated = $request->validated();
        $project->update($validated);
        return new ProjectResource($project);
    }

    public function destroy(Project $project) {
        $project->delete();
        return response()->noContent();
    }
}
