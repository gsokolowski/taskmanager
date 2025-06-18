<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserCollection;
use App\Models\Project;
use Illuminate\Http\Request;

class MembersController extends Controller
{

    // list members of the project
    public function index(Request $request, Project $project)
    {
        $members = $project->members;
        // see sql ues this call
        $sql = $project->members()->toSql();
        // dd($sql);
        // select * from users
        // inner join member on users.id = member.user_id
        // where member.project_id = 1;

        return new UserCollection($members);
    }
}
