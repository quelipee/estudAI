<?php

namespace App\Domains\ChatDomain;

use App\Domains\ChatDomain\Services\ChatService;
use Carbon\Laravel\ServiceProvider;

class ChatServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(ChatContracts::class, function ($app) {
           return new ChatService(env('GEMINI_API_KEY'));
        });
    }
}
