<?php

use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Http\Controllers\UserController;
use Illuminate\Container\Container;
use App\Services\UserService;
use App\Interfaces\UserRepositoryInterface;

Route::get('/test-di', function () {
    // Получаем экземпляр контейнера для 2 урока
    $container = Container::getInstance();

    // Получаем UserService через контейнер
    $service = $container->make(UserService::class);

    // Используем сервис
    $users = $service->getUsers();

    return response()->json([
        'message' => 'DI контейнер работает!',
        'users_count' => $users->count(),
        'users' => $users
    ]);
});
Route::get('/test-interface-di', function () {
    $container = Container::getInstance();

    // Получаем сервис через контейнер
    $service = $container->make(UserService::class);

    // Проверяем, какая реализация используется
    $repository = $container->make(UserRepositoryInterface::class);

    return response()->json([
        'repository_class' => get_class($repository),
        'repository_implements' => class_implements($repository),
        'users' => $service->getUsers()
    ]);
});

Route::get('/test-provider', function () {
    $container = Container::getInstance();

    // Получаем сервисы разными способами
    $service1 = $container->make(UserService::class);
    $service2 = $container->make('user.service'); // через алиас
    $repository = $container->make('user.repository'); // через алиас

    // Проверяем синглтон (должны быть один и тот же объект)
    $isSingleton = $service1 === $service2;

    return response()->json([
        'service1_class' => get_class($service1),
        'service2_class' => get_class($service2),
        'is_singleton' => $isSingleton,
        'repository_class' => get_class($repository),
        'users' => $service1->getUsers()
    ]);
});
Route::get('/', function () {
    return view('welcome');
});
Route::get('/test-users', function () {
    return User::all();
});
Route::get('/users', [UserController::class, 'index']);
