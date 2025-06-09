<?php

namespace App\Observers;

use App\Models\Project;


class ProjectObserver
{
    /**
     * Handle the Project "created" event.
     */
    // Create observer for a model where  an observer will make
    // sure that  creator of the project will automatically be a member of the project.

    public function created(Project $project): void
    {
        // add creator of the project as a member so crator of the project is also a member of the project
        $project->members()->attach($project->creator_id);
    }

    /**
     * Handle the Project "updated" event.
     */
    public function updated(Project $project): void
    {
        //
    }

    /**
     * Handle the Project "deleted" event.
     */
    public function deleted(Project $project): void
    {
        //
    }

    /**
     * Handle the Project "restored" event.
     */
    public function restored(Project $project): void
    {
        //
    }

    /**
     * Handle the Project "force deleted" event.
     */
    public function forceDeleted(Project $project): void
    {
        //
    }
}
