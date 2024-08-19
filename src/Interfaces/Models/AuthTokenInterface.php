<?php

namespace TheBachtiarz\OAuth\Interfaces\Models;

use Illuminate\Support\Carbon;
use TheBachtiarz\OAuth\Models\Authenticatable;

interface AuthTokenInterface
{
    public const TABLE_NAME = 'personal_access_tokens';

    /**
     * Get user instance
     */
    public function getUserInstance(): Authenticatable;

    /**
     * Get token name
     */
    public function getTokenName(): string;

    /**
     * Get token abilities
     */
    public function getTokenAbilities(): array;

    /**
     * Get token last used
     */
    public function getTokenLastUsed(): ?Carbon;

    /**
     * Get token expired at
     */
    public function getTokenExpiredAt(): ?Carbon;

    /**
     * Get token created at
     */
    public function getTokenCreatedAt(): Carbon;
}
