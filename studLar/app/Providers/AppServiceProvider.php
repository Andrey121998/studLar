<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\UserService;
use App\Repositories\UserRepository;
use App\Interfaces\UserRepositoryInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    // public function register(): void
    // {
    //     // Регистрация UserRepository для 2 урока блока 4
    //     $this->app->bind(UserRepository::class, function ($app) {
    //         return new UserRepository();
    //     });

    //     // Регистрация UserService
    //     $this->app->bind(UserService::class, function ($app) {
    //         return new UserService($app->make(UserRepository::class));
    //     });

    // }
    public function register(): void
    {

        if (app()->environment('local')) {
            $this->app->bind(
                UserRepositoryInterface::class,
                \App\Repositories\MockUserRepository::class
            );
        }

        else {
            $this->app->bind(
                UserRepositoryInterface::class,
                UserRepository::class
            );
        }

        // UserService автоматически получит правильную реализацию
        $this->app->bind(UserService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
