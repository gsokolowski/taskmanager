<?php

namespace Tests\Feature\TaskController;

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
                    ->assertJsonStructure([ // check if you receve Json Column Structure you should receive
                        'data' => [
                            '*' => [
                                'id',
                                'title',
                                'is_done',
                                'creator_id',
                                'project_id',
                                'created_at',
                                'status']
                        ]
                    ]);

        // $response->dd();

        // check if you get back 200 http response from that request
        $response->assertStatus(200);

    }

    // test for filtering data using data provider which returns array of arrays
    // phpunit will look over arays and use array imputs of your test function
    // this way you can test the same scenario with many defferent inputs
    /**
     * @dataProvider filterFields
     */
    // The @dataProvider annotation tells PHPUnit to use the filterFields method as a data provider for this test.
    // filterFields() will be passed here as parameters from the array
    public function test_filterable_fields($field, $value, $expectedCode): void
    {
        $user = User::factory()->create();
        // then uthentiacte user using Sanctum
        Sanctum::actingAs($user);
        // get route with passed fild parameter to mimic http://taskmanager.local/tasks?filter[field]=value
        $route = route('tasks.index', [
            "filter[{$field}]" => $value
        ]);
        // make get rquest to this route (end point) and collect response as Json
        $response = $this->getJson($route);
        $response->assertStatus($expectedCode);
    }

    public static function filterFields(): array
    {
        return [
            ['id', 1, 400], // field, test value, expect response 400 bad request
            ['title', 'Sample Title', 400], // field, test value, expect response 400 bad request
            ['is_done', 1, 200] // field, test value, only filarable fields is allowedFilters 'is_done' check TaskController index()
        ];
    }

    // test sortable fields
    /**
     * @dataProvider sortableFields
     */
    public function test_sortable_fields($field, $expectedCode): void
    {
        $user = User::factory()->create();
        // then uthentiacte user using Sanctum
        Sanctum::actingAs($user);
        // get route with passed fild parameter to mimic http://taskmanager.local/tasks?filter[field]=value
        $route = route('tasks.index', [
            "sort" => $field
        ]);
        // make get rquest to this route (end point) and collect response as Json
        $response = $this->getJson($route);
        $response->assertStatus($expectedCode);
    }

    public static function sortableFields(): array
    {
        return [
            ['id', 400], // field, expect response 400 bad request
            ['title', 200], // field,  expect response 200 bad request
            ['is_done', 200], // field, expect response 200 bad request
            ['created_at', 200], // field, expect response 200 bad request
            ['updated_at', 400] // field, expect response 200 bad request
        ];
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
