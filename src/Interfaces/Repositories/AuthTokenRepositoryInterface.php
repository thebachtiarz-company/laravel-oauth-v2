<?php

namespace TheBachtiarz\OAuth\Interfaces\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Laravel\Sanctum\NewAccessToken;
use TheBachtiarz\Base\Interfaces\Repositories\RepositoryInterface;
use TheBachtiarz\OAuth\Interfaces\Models\AuthTokenInterface;
use TheBachtiarz\OAuth\Models\Authenticatable;

interface AuthTokenRepositoryInterface extends RepositoryInterface
{
    /**
     * Get token by name
     */
    public function getByName(string $name): ?AuthTokenInterface;

    /**
     * Get list token by user
     *
     * @return Collection<AuthTokenInterface>
     */
    public function getByUser(Authenticatable $user): Collection;

    /**
     * Create new token from user
     */
    public function createToken(Authenticatable $user, array $abilities = ['*']): NewAccessToken;

    /**
     * Delete token by name
     */
    public function deleteByName(string $name): bool;

    /**
     * Delete all token from user
     */
    public function deleteByUser(Authenticatable $user): bool;
}
