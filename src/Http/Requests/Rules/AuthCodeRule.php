<?php

namespace TheBachtiarz\OAuth\Http\Requests\Rules;

use TheBachtiarz\Base\Http\Requests\Rules\AbstractRule;
use TheBachtiarz\OAuth\Interfaces\Models\AuthUserInterface;

class AuthCodeRule extends AbstractRule
{
    public const CODE = 'code';

    #[\Override]
    public static function rules(): array
    {
        return [
            self::CODE => [
                'required',
                'string',
                'alpha_dash:ascii',
                'starts_with:' . AuthUserInterface::USER_CODE_PREFIX,
            ],
        ];
    }


    #[\Override]
    public static function messages(): array
    {
        return [
            sprintf('%s.required', self::CODE) => 'Auth code required!',
            sprintf('%s.*', self::CODE) => 'Auth code invalid!',
        ];
    }
}
