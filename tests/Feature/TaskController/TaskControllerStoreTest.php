<?php

namespace Tests\Feature\TaskController;

use App\Models\Task;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class TaskControllerStoreTest extends TestCase
{

        /** @test */
        public function test_authenticated_user_can_create_a_task()
        {
            $user = User::factory()->create();
            Sanctum::actingAs($user);

            // preapare rouute
            $route = route('tasks.store');

            // prepare data to store task
            $taskData = [
                'title' => 'Test Task',
            ];

            // postJason
            $response = $this->postJson($route, $taskData);

            // check if task has been creted
            $response->assertCreated(); // status 201

            // check if newly created task is in databese. taks is db table
            $this->assertDatabaseHas('tasks', [
                'title' => 'Test Task',
                'creator_id' => $user->id,
            ]);

        }

        /** @test */
        public function test_tile_filed_is_required()
        {

            $user = User::factory()->create();
            Sanctum::actingAs($user);

            // preapare rouute
            $route = route('tasks.store');

            // prepare data to store task wih no title field
            $taskData = [];

            // postJason
            $response = $this->postJson($route, $taskData);

            // check for validation where title is required
            $response->assertJsonValidationErrors([
                'title' => 'required',
            ]);
        }
}