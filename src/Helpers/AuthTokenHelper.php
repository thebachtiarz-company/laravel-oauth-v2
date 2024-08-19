<?php

namespace TheBachtiarz\OAuth\Helpers;

use Laravel\Sanctum\NewAccessToken;
use TheBachtiarz\OAuth\DTOs\Auth\NewAccessTokenInformation;
use TheBachtiarz\OAuth\DTOs\Auth\NewAccessTokenResponseDTO;
use TheBachtiarz\OAuth\Models\Authenticatable;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class AuthTokenHelper
{
    /**
     * Current user authenticated
     */
    private static Authenticatable $currentAuth;

    /**
     * Get current user authenticated
     */
    public static function currentAuthenticatable(): Authenticatable
    {
        if (!Auth::hasUser()) {
            throw new AuthenticationException();
        }

        static::$currentAuth = OauthModelHelper::instance()::find(Auth::user()->id);

        return static::$currentAuth;
    }

    /**
     * New access token modifier
     */
    public static function newAccessTokenModify(NewAccessToken $newAccessToken): NewAccessTokenResponseDTO
    {
        $expires = $newAccessToken->accessToken->expires_at
            ? (new Carbon($newAccessToken->accessToken->expires_at))->getTimestamp()
            : 'Never';

        return new NewAccessTokenResponseDTO(
            information: new NewAccessTokenInformation(
                name: $newAccessToken->accessToken->name,
                expires: $expires,
            ),
            token: $newAccessToken->plainTextToken,
        );
    }

    /**
     * Generate where builder only for owner
     */
    public static function builderWhereOwner(Authenticatable $user, array $customConditions = []): array
    {
        return array_merge(
            [
                'tokenable_type' => $user::class,
                'tokenable_id' => $user->getId(),
            ],
            $customConditions,
        );
    }
}
