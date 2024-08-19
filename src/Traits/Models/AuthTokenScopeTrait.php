<?php

namespace TheBachtiarz\OAuth\Traits\Models;

use Illuminate\Contracts\Database\Query\Builder as BuilderContract;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use TheBachtiarz\OAuth\Helpers\AuthTokenHelper;
use TheBachtiarz\OAuth\Models\Authenticatable;

/**
 * Auth Token Scope Trait
 */
trait AuthTokenScopeTrait
{
    /**
     * Get list token by user
     */
    public function scopeGetByUser(EloquentBuilder|QueryBuilder $builder, Authenticatable $user, array $customConditions = []): BuilderContract
    {
        return $builder->where(AuthTokenHelper::builderWhereOwner(user: $user, customConditions: $customConditions));
    }

    /**
     * Get token by user and token name
     */
    public function scopeGetByUserName(EloquentBuilder|QueryBuilder $builder, Authenticatable $user, string $tokenName): BuilderContract
    {
        return $builder->getByUser($user, ['name' => $tokenName]);
    }
}
