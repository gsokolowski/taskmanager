<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProjectPolicy
{
    /**
     * Determine whether the current user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the logged in user can view the model.
     */
    public function view(User $user, Project $project): bool
    {
        // if user is the creator of the project, then return true
        //var_dump($user->id, $project->creator_id);
        // return $user->id === $project->creator_id;

        // if logged in user is a member of the project, then return true
        // if($user->id === $project->creator_id) {
        //     return true;
        // }

        // var_dump($user->memberships->toArray());
        // var_dump($project->id);

        // if logged in user is a member of the project, then return true
        // if($user->memberships->contains($project->id)) {
        //     return true;
        // }
        // return false;

        //Now creator of the project is also a member of the project as well so we can have only thi line
        return $user->memberships->contains($project->id);

    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // every sign in user has permition to create project through ProjectController store methode
        // we authorize create function
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Project $project): bool
    {
        // check is user is the creator of th project before you allow it to update project
        //through ProjectController update  methode
        // we authorize update function
        return $user->id === $project->creator_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Project $project): bool
    {
        // check is user is the creator of th project before you allow it to delete project
        // through ProjectController delete  methode
        // we authorize delete function
        return $user->id === $project->creator_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Project $project): bool
    {
        //
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Project $project): bool
    {
        //
        return false;
    }
}
