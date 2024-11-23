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

        // return new ProjectResource($project);

        // tasks and members are loaded in ProjectResource but to make
        // it otional we use whenLoaded() in ProjectResource and load() in ProjectController
        return (new ProjectResource($project))
        ->load('tasks') // show tasks to ptoject
        ->load('members'); // show members to project, members are on pivot table
    }

    // {{DOMAIN}}/api/projects?include=tasks
    public function index(Request $request) {
        // return a collection of projects for authenticated user
        //return new ProjectCollection(Auth::user()->projects()->paginate());

        // spatie Query Builder extension all projects with their tasks loaded
        $projects = QueryBuilder::for(Project::class)
            ->allowedIncludes(['tasks']) // add ?include=tasks to url to load tasks
            ->paginate();

            // var_dump(
            //     $projects->toQuery()->toSql(),
            //     $projects->toQuery()->getBindings()
            // );

         return new ProjectCollection($projects);

    }

    public function store(StoreProjectRequest $request) {
        $validated = $request->validated();
        // create a new project for specific user
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
