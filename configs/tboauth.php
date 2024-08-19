<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Auth User Model
    |--------------------------------------------------------------------------
    |
    | Define auth user model class
    |
    */
    'auth_user_model' => env('TB_AUTH_USER_MODEL', \TheBachtiarz\OAuth\Models\AuthUser::class),

    /*
    |--------------------------------------------------------------------------
    | Auth Identifier Method
    |--------------------------------------------------------------------------
    |
    | Define auth identifier method for authentication
    |
    */
    'auth_identifier_method' => env('TB_AUTH_IDENTIFIER_METHOD', \TheBachtiarz\OAuth\Interfaces\Models\AuthUserInterface::ATTRIBUTE_EMAIL),

    /*
    |--------------------------------------------------------------------------
    | Auto Verified Email
    |--------------------------------------------------------------------------
    |
    | Define is email automatically verified
    |
    */
    'email_auto_verified' => true,
];
