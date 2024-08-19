<?php

namespace TheBachtiarz\OAuth\Http\Requests\Rules;

use TheBachtiarz\Base\Http\Requests\Rules\AbstractRule;
use Illuminate\Validation\Rules\Password;

class AuthPasswordRule extends AbstractRule
{
    public const PASSWORD = 'password';

    #[\Override]
    public static function rules(): array
    {
        return [
            self::PASSWORD => [
                'required',
                Password::min(8)->mixedCase()->numbers()->symbols(),
            ],
        ];
    }
}
