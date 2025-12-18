<?php

namespace App\Repositories;

use App\Interfaces\UserRepositoryInterface;

class MockUserRepository implements UserRepositoryInterface
{
    private $mockUsers = [
        ['id' => 1, 'name' => 'Mock User 1', 'email' => 'mock1@example.com'],
        ['id' => 2, 'name' => 'Mock User 2', 'email' => 'mock2@example.com'],
        ['id' => 3, 'name' => 'Mock User 3', 'email' => 'mock3@example.com'],
    ];

    public function getAll()
    {
        return collect($this->mockUsers);
    }

    public function find($id)
    {
        return collect($this->mockUsers)->firstWhere('id', $id);
    }

    public function create(array $data)
    {
        $newId = count($this->mockUsers) + 1;
        $data['id'] = $newId;
        $this->mockUsers[] = $data;
        return $data;
    }

    public function update($id, array $data)
    {
        foreach ($this->mockUsers as &$user) {
            if ($user['id'] == $id) {
                $user = array_merge($user, $data);
                return $user;
            }
        }
        return null;
    }

    public function delete($id)
    {
        $this->mockUsers = array_filter($this->mockUsers, function($user) use ($id) {
            return $user['id'] != $id;
        });
        return true;
    }
}
