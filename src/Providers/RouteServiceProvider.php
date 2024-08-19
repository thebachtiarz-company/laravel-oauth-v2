<?php

namespace TheBachtiarz\OAuth\Providers;

use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends \Illuminate\Foundation\Support\Providers\RouteServiceProvider
{
    public function boot(): void
    {
        Route::prefix('auth')->middleware('api')->group(__DIR__ . '/../routes/api.php');
    }
}
