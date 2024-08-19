<?php

namespace TheBachtiarz\OAuth\Interfaces\Services;

use TheBachtiarz\Base\DTOs\Services\ResponseDataDTO;
use TheBachtiarz\Base\Interfaces\Services\ServiceInterface;

interface AuthUserServiceInterface extends ServiceInterface
{
    /**
     * Get auth user by code
     */
    public function getAuthUser(string $code): ResponseDataDTO;

    /**
     * Create a new auth user
     */
    public function createAuthUser(string $identifier, string $password): ResponseDataDTO;

    /**
     * Update auth user
     */
    public function updateAuthUser(string $identifier, string $password): ResponseDataDTO;

    /**
     * Create new token from user identifier
     */
    public function createAuthToken(string $identifier, string $password): ResponseDataDTO;

    /**
     * Create new token using auth user code
     */
    public function createAuthTokenUsingAuthCode(string $authCode): ResponseDataDTO;

    /**
     * Get list auth token by current authenticated user
     */
    public function getListAuthToken(int $perPage = 15, int $currentPage = 1): ResponseDataDTO;

    /**
     * Delete token by token name based from current authenticated user
     */
    public function deleteToken(string $tokenName): ResponseDataDTO;

    /**
     * Revoke all token based from current authenticated user
     */
    public function revokeTokens(): ResponseDataDTO;
}
