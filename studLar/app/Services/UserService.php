<?php

namespace App\Services;

use App\Repositories\UserRepository;
use App\Interfaces\UserRepositoryInterface;

class UserService
{
    protected $userRepository;

    // Теперь принимаем интерфейс
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    // public function __construct(UserRepository $userRepository)
    // {
    //     $this->userRepository = $userRepository;
    // }

    public function getUsers()
    {
        return $this->userRepository->getAll();
    }
    public function createUser(array $data)
    {
        //для реализации 2 урока блока 4
        if (!isset($data['password'])) {
            $data['password'] = bcrypt('password123');
        } else {
            $data['password'] = bcrypt($data['password']);
        }

        return $this->userRepository->create($data);
    }

    public function getUserById($id)
    {
        return $this->userRepository->find($id);
    }

    public function checkDependencies()
{
    return [
        'repository_class' => get_class($this->userRepository),
        'repository_methods' => get_class_methods($this->userRepository),
        'is_di_working' => $this->userRepository instanceof \App\Repositories\UserRepository
    ];
}
}
