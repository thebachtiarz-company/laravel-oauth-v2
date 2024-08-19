<?php

namespace TheBachtiarz\OAuth\Models;

use Illuminate\Support\Carbon;
use Laravel\Sanctum\PersonalAccessToken;
use TheBachtiarz\OAuth\Interfaces\Models\AuthTokenInterface;
use TheBachtiarz\OAuth\Traits\Models\AuthTokenScopeTrait;

class AuthToken extends PersonalAccessToken implements AuthTokenInterface
{
    use AuthTokenScopeTrait;

    /**
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->setTable(self::TABLE_NAME);

        parent::__construct($attributes);
    }

    /**
     * Get user instance
     */
    public function getUserInstance(): Authenticatable
    {
        /** @var Authenticatable $auth */
        $auth = app($this->tokenable_type);

        return $auth::find($this->tokenable_id);
    }

    /**
     * Get token name
     */
    public function getTokenName(): string
    {
        return $this->name;
    }

    /**
     * Get token abilities
     */
    public function getTokenAbilities(): array
    {
        return $this->abilities;
    }

    /**
     * Get token last used
     */
    public function getTokenLastUsed(): ?Carbon
    {
        return $this->last_used_at;
    }

    /**
     * Get token expired at
     */
    public function getTokenExpiredAt(): ?Carbon
    {
        return $this->expires_at;
    }

    /**
     * Get token created at
     */
    public function getTokenCreatedAt(): Carbon
    {
        return $this->{$this->getCreatedAtColumn()};
    }
}
