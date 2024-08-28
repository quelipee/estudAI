<?php

namespace App\Providers;

use App\Domains\CourseDomain\CourseService\CourseService;
use App\Domains\CourseDomain\Interfaces\CourseTopicsContracts;
use App\Domains\UserDomain\AuthServiceContract;
use App\Domains\UserDomain\UserService\UserService;
use GeminiAPI\Client;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(Client::class,function ($app){
            return new Client(env('GEMINI_API_KEY'));
        });

        $this->app->singleton(AuthServiceContract::class,function ($app){
            return new UserService();
        });

        $this->app->bind(CourseTopicsContracts::class,function ($app){
            $config = $app['config']['usertype.provider_default'];
            return match ($config){
                'user' => new UserService(),
                'admin' => new CourseService(),
            };
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
