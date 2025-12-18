<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Interfaces\UserRepositoryInterface;
use App\Repositories\UserRepository;
use App\Repositories\MockUserRepository;
use App\Services\UserService;

class UserServiceProvider extends ServiceProvider
{
    /**
     * Регистрация сервисов в контейнере.
     */
    public function register(): void
    {
        // Регистрация репозитория
        $this->app->bind(UserRepositoryInterface::class, function ($app) {
            // В зависимости от окружения используем разную реализацию
            if ($app->environment('local', 'testing')) {
                return new MockUserRepository();
            }
            return new UserRepository();
        });

        // Регистрация сервиса
        $this->app->singleton(UserService::class, function ($app) {
            return new UserService($app->make(UserRepositoryInterface::class));
        });

        // Можно регистрировать псевдонимы (алиасы)
        $this->app->alias(UserService::class, 'user.service');
        $this->app->alias(UserRepositoryInterface::class, 'user.repository');
    }

    /**
     * Загрузка сервисов после регистрации.
     */
    public function boot(): void
    {

    }
}
