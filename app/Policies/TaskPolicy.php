<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TaskPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // allow sing in users to fatch list of tasks
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Task $task): bool
    {
        // is the user creator of the task
        if($user->id === $task->creator_id){
            return true;
        }

        // check if
        // task is related to the any project &&

        // SELECT p.*
        // FROM projects p
        // JOIN tasks t ON p.id = t.project_id
        // WHERE t.id = 5;

        // $task->project

        // and wheather user is a member of this project of the task

        // SELECT EXISTS (
        //     SELECT 1
        //     FROM member
        //     WHERE user_id = 2          -- The user ID you're checking
        //     AND project_id = 1         -- The project ID you're checking
        // ) AS is_member;

        // $user->memberships->contains($task->project)

        if($task->project && $user->memberships->contains($task->project)) {
            return true;
        }
        // otherwise
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // allow all sign in users to create a task
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Task $task): bool
    {
        // allow to update only by creator of the task
        return $user->id === $task->creator_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Task $task): bool
    {
        // allow to delete only by creator of the task
        return $user->id === $task->creator_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Task $task): bool
    {
        // for soft deletes
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Task $task): bool
    {
        // for soft deletes
        return false;
    }
}
