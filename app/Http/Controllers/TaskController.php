<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Resources\TaskCollection;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{

    //GET http://taskmanager.local/api/tasks
    public function index(Request $request)
    {

        // $task=Task::all();
        // return response()->json($task);

        //return response()->json(Task::all());

        // wrapped in data as collection as we use TaskCollection class in TaskController
        return new TaskCollection(Task::all());
    }

    // GET http://taskmanager.local/api/tasks/1
    public function show(Task $task)
    {
        return new TaskResource($task);
    }

    // POST http://taskmanager.local/api/tasks
    public function store(StoreTaskRequest $request)
    {

        $validated = $request->validated();

        $task = Task::create($validated);

        return new TaskResource($task);
    }

    // public function update(Request $request, Task $task)
    // {
    //     $task->update($request->all());
    //     return response()->json($task);
    // }

}
