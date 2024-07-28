<?php

namespace App\Http\Controllers;

use App\Http\Resources\TaskCollection;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request)
    {

        // $task=Task::all();
        // return response()->json($task);

        //return response()->json(Task::all());

        // wrapped in data as collection
        return new TaskCollection(Task::all());
    }
}
