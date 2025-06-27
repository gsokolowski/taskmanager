<?php

namespace Tests\Feature\TaskController;

use App\Models\Task;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class TaskControllerShowTest extends TestCase
{

    // authenticated task creators should be able to see_their own tasks
    public function test_authenticated_task_creators_should_be_able_to_see_their_own_tasks(): void
    {

        // create new user
        $user=User::factory()->create();

        // authenticate user
        Sanctum::actingAs($user);

        // create new task so you can show it for that user
        $task = Task::factory()->for($user, 'creator')->create();

        // get route to show task
        $route = route('tasks.show', $task);

        // make get rquest to this route (end point) and collect response as Json
        $response = $this->getJson($route);

        // test if this response returns 200 response

        $response->assertOk()
            ->assertJson([
                'data' => [
                    'id' => $task->id,
                    'title' => $task->title,
                    'is_done' => $task->is_done,
                    'status' => 'open',
                    'project_id' => null,
                    'creator_id' => $user->id,
                    'created_at' => $task->created_at->jsonSerialize(), // needs to be jasonSerialize otherwise willnot match with datatim
                ],
            ]);
    }

    // unauthenticated task creators should be able to see their own tasks
    public function test_unauthenticated_task_creators_should_be_able_to_see_their_own_tasks(): void
    {

        // create new user
        $user=User::factory()->create();

        // create new task so you can show it for that user
        $task = Task::factory()->for($user, 'creator')->create();

        // get route to show task
        $route = route('tasks.show', $task);

        // make get rquest to this route (end point) and collect response as Json
        $response = $this->getJson($route);

        // test if this response is unauthorized
        $response->assertUnauthorized();

    }

    // est_authorised_user_but_not_creator_of_the_task
    public function test_authorised_user_but_not_creator_of_the_task(): void
    {
        $user=User::factory()->create();
        $task=Task::factory()->create(); // task but not created by this user
        // authenticate user
        Sanctum::actingAs($user);
        $route = route('tasks.show',$task);

        $response = $this->getJson($route);

        $response->assertNotFound(); //returns 404

    }

}