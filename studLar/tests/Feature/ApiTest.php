<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;

class ApiTest extends TestCase
{
    /** @test */
    public function test_user_api_returns_users()
    {

        $initialCount = User::count();


        if ($initialCount < 3) {
            User::factory()->count(3 - $initialCount)->create();
        }


        $response = $this->get('/users');


        $response->assertStatus(200);


        $response->assertJsonStructure([
            '*' => ['id', 'name', 'email']
        ]);


        $users = $response->json();
        $this->assertGreaterThan(0, count($users));


        if (count($users) > 0) {
            $firstUser = $users[0];
            $this->assertArrayHasKey('id', $firstUser);
            $this->assertArrayHasKey('name', $firstUser);
            $this->assertArrayHasKey('email', $firstUser);
        }
    }
}
