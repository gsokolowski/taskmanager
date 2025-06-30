<?php

namespace Tests\Feature\TaskController;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class TaskControllerUpdateTest extends TestCase
{
    public function test_authenticated_user_can_update_title(): void
    {
        // create new user
        $user = User::factory()->create();
        // create task for this user you need to be creator of the task to be able to update the task to be able to update the task
        $task = Task::factory()->for($user, 'creator')->create();
        // authenticate user
        Sanctum::actingAs($user);
        // get route to task update
        $route = route('tasks.update', $task);
        // set update data
        $dataToUpdate = ['title' => 'foo'];
        // send put json data to update route
        $response = $this->putJson($route, $dataToUpdate);

        $response->assertOk(); // expect successful response status 200

        // now title shuld be equal to 'foo'
        $this->assertEquals('foo', $task->refresh()->title); // use refresh to update data of this title as it doent updateautomatically
    }

    public function test_authenticated_user_cannot_update_as_project_member(): void
    {
        $user = User::factory()->create();
        // create new project
        $project = Project::factory()->create();
        // attach member (user->id) to this project
        $project->members()->attach([$user->id]);

        // createTask model instance using a factory, while also defining its relationships
        // create a Task as the project creator
        $task = Task::factory()  // Start a Task factory
            ->for($project->creator, 'creator') // Associate with the project's creator (User model)
            ->for($project) // Associate with the Project model
            ->create();

         // authenticate user
        Sanctum::actingAs($user);

        $route = route('tasks.update', $task);
        $dataToUpdate = ['title' => 'foo'];
        $response = $this->putJson($route, [$dataToUpdate]);

        $response->assertForbidden(); // expect 403
    }

    public function test_unauthenticated_response_no_user_no_authentcation(): void
    {
        $task = Task::factory()->create();

        $route = route('tasks.update', $task);
        $dataToUpdate = ['title' => 'foo'];
        $response = $this->putJson($route, [$dataToUpdate]);
        // no user no authentcation
        $response->assertUnauthorized(); // expect 401
    }

    // when you try to chenge a task you have no permision to change
    public function test_authenticated_user_without_right_to_change_task_no_access_response(): void
    {
        $user = User::factory()->create(); // create new user
        $task = Task::factory()->create(); // create new task
        Sanctum::actingAs($user);           // autheniticate user

        // no relationship between user and the task means no access for updating

        $route = route('tasks.update', $task);
        $response = $this->putJson($route, [
            'title' => 'foo',
        ]);

        $response->assertNotFound(); // expect 404
    }
}