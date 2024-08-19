<?php

namespace TheBachtiarz\OAuth\Http\Requests\Rules;

use TheBachtiarz\Base\Http\Requests\Rules\AbstractRule;
use TheBachtiarz\OAuth\Interfaces\Models\AuthUserInterface;

class AuthIdentifierRule extends AbstractRule
{
    public const IDENTIFIER = 'identifier';

    #[\Override]
    public static function rules(): array
    {
        return [
            self::IDENTIFIER => match (config('tboauth.auth_identifier_method')) {
                AuthUserInterface::ATTRIBUTE_EMAIL => AuthEmailRule::rules()[AuthEmailRule::EMAIL],
                AuthUserInterface::ATTRIBUTE_USERNAME => AuthUsernameRule::rules()[AuthUsernameRule::USERNAME],
                default => AuthEmailRule::rules()[AuthEmailRule::EMAIL],
            },
        ];
    }
}
