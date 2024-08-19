<?php

namespace TheBachtiarz\OAuth\Helpers;

use TheBachtiarz\OAuth\Models\Authenticatable;
use TheBachtiarz\OAuth\Models\AuthUser;

class OauthModelHelper
{
    private static Authenticatable $oauthModel;

    /**
     * Get defined auth user model
     */
    public static function instance(): Authenticatable
    {
        static::$oauthModel = app(config('tboauth.auth_user_model') ?? AuthUser::class);

        return static::$oauthModel;
    }
}
