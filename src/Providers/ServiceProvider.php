<?php

namespace TheBachtiarz\OAuth\Providers;

use Laravel\Sanctum\PersonalAccessToken;
use TheBachtiarz\OAuth\Interfaces\Models\AuthTokenInterface;
use TheBachtiarz\OAuth\Interfaces\Models\AuthUserInterface;
use TheBachtiarz\OAuth\Interfaces\Repositories\AuthTokenRepositoryInterface;
use TheBachtiarz\OAuth\Interfaces\Repositories\AuthUserRepositoryInterface;
use TheBachtiarz\OAuth\Interfaces\Services\AuthUserServiceInterface;
use TheBachtiarz\OAuth\Models\AuthToken;
use TheBachtiarz\OAuth\Models\AuthUser;
use TheBachtiarz\OAuth\Repositories\AuthTokenRepository;
use TheBachtiarz\OAuth\Repositories\AuthUserRepository;
use TheBachtiarz\OAuth\Services\AuthUserService;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(abstract: PersonalAccessToken::class, concrete: AuthToken::class);

        $this->app->bind(abstract: AuthTokenInterface::class, concrete: AuthToken::class);
        $this->app->bind(abstract: AuthTokenRepositoryInterface::class, concrete: AuthTokenRepository::class);
        $this->app->bind(abstract: AuthUserInterface::class, concrete: AuthUser::class);
        $this->app->bind(abstract: AuthUserRepositoryInterface::class, concrete: AuthUserRepository::class);
        $this->app->bind(abstract: AuthUserServiceInterface::class, concrete: AuthUserService::class);

        (new ConfigProvider())();
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        if (! $this->app->runningInConsole()) {
            return;
        }

        $configName  = 'tboauth';
        $publishName = 'thebachtiarz-oauth';

        $this->publishes([__DIR__ . "/../../configs/$configName.php" => config_path("$configName.php")], "$publishName-config");
        $this->publishes([__DIR__ . '/../../database/migrations' => database_path('migrations')], "$publishName-migrations");
    }
}
