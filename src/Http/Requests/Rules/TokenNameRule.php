<?php

namespace TheBachtiarz\OAuth\Http\Requests\Rules;

use TheBachtiarz\Base\Http\Requests\Rules\AbstractRule;

class TokenNameRule extends AbstractRule
{
    public const NAME = 'name';

    #[\Override]
    public static function rules(): array
    {
        return [
            self::NAME => [
                'required',
                'alpha_num',
            ],
        ];
    }


    #[\Override]
    public static function messages(): array
    {
        return [
            sprintf('%s.required', self::NAME) => 'Token name required!',
            sprintf('%s.*', self::NAME) => 'Token name invalid!',
        ];
    }
}
