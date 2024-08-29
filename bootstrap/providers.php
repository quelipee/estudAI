<?php

use App\Domains\AdminRouteServiceProvider;
use App\Domains\ChatDomain\ChatServiceProvider;

return [
    App\Providers\AppServiceProvider::class,
    AdminRouteServiceProvider::class,
    ChatServiceProvider::class,
];
