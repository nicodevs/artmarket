<?php

namespace Tests\Feature;

use App\Search;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SearchesTest extends TestCase
{
    use RefreshDatabase;

    public function test_an_admin_can_list_searches()
    {
        $searches = factory(Search::class, 10)->create();

        $this->actingAsAdmin()
            ->json('GET', 'api/searches')
            ->assertStatus(200)
            ->assertJson([
                'success' => true
            ])
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'user_id',
                        'keyword',
                        'results_count'
                    ]
                ]
            ]);
    }

    public function test_a_guest_can_not_list_searches()
    {
        $this->json('GET', 'api/searches')
            ->assertStatus(401)
            ->assertJson([
                'success' => false
            ]);
    }

    public function test_a_user_can_not_list_searches()
    {
        $searches = factory(Search::class, 10)->create();

        $this->actingAsUser(factory(User::class)->create())
            ->json('GET', 'api/searches')
            ->assertStatus(401)
            ->assertJson([
                'success' => false
            ]);
    }
}
