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

    public function store( Request $request, Project $project)
    {
        // validate incomming request check if user_id  does exist in users table
        // simple validation here
        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        // attach user to project members pivot table
        // use syncWithoutDetaching which will make sure user cant be attacjed
        // to the same project more then once
        $project->members()->syncWithoutDetaching([$request->user_id]);

        // And return new list of the project members
        $members = $project->members;
        return new UserCollection($members);

    }


    public function destroy(Request $request, Project $project, int $member)
    {
        // don't allow to delete if member is not a creator of the project
        abort_if($project->creator_id === $member, 400, 'Not allowed to remove creator from the project');

        $project->members()->detach([$member]);

        // And return new list of the project members
        $members = $project->members;
        return new UserCollection($members);

    }
}
