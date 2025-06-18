<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskCollection;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\QueryBuilder;


class TaskController extends Controller
{

    public function __construct()
    {
        // instruct TaskController to map its menthods with TaskPolicies
        // automatically map controller methods to policy methods using Laravel's conventional naming scheme
        // authorizeResource automatically maps controller methods to policy methods

        $this->authorizeResource(Task::class, 'task'); // (model class name, route parameter name that contains the model's ID is called task)
    }

    //GET http://taskmanager.local/api/tasks
    public function index(Request $request)
    {

        $tasks = QueryBuilder::for(Task::class)

        ->allowedFilters('is_done')
        ->defaultSort('-created_at') // last data first (-)
        ->allowedSorts(['title', 'is_done', 'created_at'])

        ->paginate();

        return new TaskCollection($tasks);
    }

    // GET http://taskmanager.local/api/tasks/1
    public function show(Task $task)
    {
        return new TaskResource($task);     // 200 OK
    }

    // POST http://taskmanager.local/api/tasks
    public function store(StoreTaskRequest $request)
    {

        $validated = $request->validated();
        // $task = Task::create($validated);

        $task = Auth::user()->tasks()->create($validated); // 1-1 relationship user id as creator_id o tasks table

        return new TaskResource($task);     //  201 Created

    }

     // PUT http://taskmanager.local/api/tasks/1
    public function update(UpdateTaskRequest $request, Task $task)
    {

        $validated = $request->validated();
        $task->update($validated);

        return new TaskResource($task);     //  200 OK

    }

    // DELETE http://taskmanager.local/api/tasks/1
    public function destroy(Request $request, Task $task)
    {

        $task->delete();

        return response()->noContent();     // 204 no content

    }

}
