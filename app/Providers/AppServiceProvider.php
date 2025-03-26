<?php

namespace App\Providers;

use App\Services\UserService;
use App\Repositories\FilmRepository;
use App\Repositories\UserRepository;
use App\Repositories\SessionRepository;
use Illuminate\Support\ServiceProvider;
use App\Repositories\contract\FilmRepositoryInterface;
use App\Repositories\contract\UserRepositoryInterface;
use App\Repositories\contract\SessionRepositoryInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(FilmRepositoryInterface::class, FilmRepository::class);
        $this->app->bind(SessionRepositoryInterface::class, SessionRepository::class);

        $this->app->bind(UserService::class, function ($app) {
            return new UserService($app->make(UserRepositoryInterface::class));
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
