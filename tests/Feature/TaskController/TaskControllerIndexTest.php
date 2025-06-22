<?php

namespace Tests\Feature\TaskControllerIndexTest;

use App\Models\Task;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class TaskControllerIndexTest extends TestCase
{

    // test if authenticated users can fetch the task list
    // this function is just a test and does not return anything hence :void
    public function test_authenticated_users_can_fetch_the_task_list(): void
    {
        // as task list is accesable only by authenticated user
        //you have to create user
        $user = User::factory()->create();
        // then uthentiacte user using Sanctum
        Sanctum::actingAs($user);
        // create task for this user
        Task::factory()->for($user, 'creator')->create();
        // get route
        $route = route('tasks.index');

        // make get rquest to this route (end point) and collect response as Json
        $response = $this->getJson($route);
        $response->assertOk()
                    ->assertJsonCount(1,'data') // there is one task under data Json Key
                    ->assertJsonStructure([
                        'data' => [
                            '*' => ['id','title','is_done','creator_id','project_id','created_at','status']
                        ]
                    ]);


        // $response->dd();

        // check if you get back 200 http response from that request
        $response->assertStatus(200);

    }

    public function test_unauthenticated_users_can_not_fetch_the_task_list(): void
    {

        // get route
        $route = route('tasks.index');

        // make get rquest to this route (end point) and collect response as Json
        $response = $this->getJson($route);

        // check if you get unauthorised response
        $response->assertUnauthorized();

    }
}
