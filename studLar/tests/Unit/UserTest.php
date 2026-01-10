<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;

class UserTest extends TestCase
{
    /** @test */
    public function test_user_can_be_created()
    {
        $userData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => bcrypt('password123'),
        ];

        $user = new User($userData);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('John Doe', $user->name);
        $this->assertEquals('john@example.com', $user->email);
        $this->assertTrue(password_verify('password123', $user->password));
    }

    /** @test */
    public function test_user_full_name()
    {
        $user1 = new User(['name' => 'John Doe']);
        $this->assertEquals('John Doe', $user1->getFullName());

        $user2 = new User(['name' => '  Jane Smith  ']);
        $this->assertEquals('Jane Smith', $user2->getFullName());


        $user3 = new User(['name' => '']);
        $this->assertEquals('', $user3->getFullName());


        $user4 = new User(['name' => 'Alice']);
        $this->assertEquals('Alice', $user4->getFullName());
    }
}
