<?php

namespace TheBachtiarz\OAuth\Http\Requests\Rules;

use TheBachtiarz\Base\Http\Requests\Rules\AbstractRule;

class AuthEmailRule extends AbstractRule
{
    public const EMAIL = 'email';

    #[\Override]
    public static function rules(): array
    {
        return [
            self::EMAIL => [
                'required',
                'email',
            ],
        ];
    }
}
