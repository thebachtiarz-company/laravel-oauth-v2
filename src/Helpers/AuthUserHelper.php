<?php

namespace TheBachtiarz\OAuth\Helpers;

use TheBachtiarz\Base\Exceptions\BaseException;
use TheBachtiarz\OAuth\Interfaces\Models\AuthUserInterface;
use TheBachtiarz\OAuth\Models\Authenticatable;

class AuthUserHelper
{
    /**
     * State auth method
     */
    protected static ?string $authMethod = null;

    /**
     * Get or define auth method for authentication
     */
    public static function authMethod(?string $override = null): string
    {
        if (!static::$authMethod) {
            static::$authMethod = $override;
        }

        return static::$authMethod ?? config(key: 'tboauth.auth_identifier_method', default: AuthUserInterface::ATTRIBUTE_EMAIL);
    }

    /**
     * Prevent if identifier is exist
     *
     * @throws BaseException
     */
    public static function preventIfIdentifierIsExist(Authenticatable $auth, string $identifier): void
    {
        if ($auth->{static::authMethod()} === $identifier) {
            return;
        }

        if (static::checkIsIdentifierHasBeenUsed(identifier: $identifier)) {
            throw new BaseException(message: 'Identifier has been used!', code: 202);
        }
    }

    /**
     * Check is identifier has been used
     */
    public static function checkIsIdentifierHasBeenUsed(string $identifier): bool
    {
        $entity = OauthModelHelper::instance()->where(
            column: static::authMethod(),
            operator: '=',
            value: $identifier,
        )->first();

        return !!$entity;
    }
}
