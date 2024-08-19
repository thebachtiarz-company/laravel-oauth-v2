<?php

namespace TheBachtiarz\OAuth\Http\Requests\Rules;

use TheBachtiarz\Base\Http\Requests\Rules\AbstractRule;

class AuthUsernameRule extends AbstractRule
{
    public const USERNAME = 'username';

    #[\Override]
    public static function rules(): array
    {
        return [
            self::USERNAME => [
                'required',
                'alpha_dash:ascii',
                'min:8',
            ],
        ];
    }
}
