<?php

namespace TheBachtiarz\OAuth\Helpers;

use Illuminate\Support\Str;
use TheBachtiarz\Base\Helpers\General\StringHelper;
use TheBachtiarz\OAuth\Interfaces\Models\AuthUserInterface;
use TheBachtiarz\OAuth\Models\Authenticatable;

class UserCodeHelper
{
    /**
     * Generate new user code
     */
    public static function generate(int $length = 32, ?string $prefix = null): string
    {
        if ($length < 20) {
            $length = 20;
        }

        $prefix ??= AuthUserInterface::USER_CODE_PREFIX;

        do {
            $uuid = Str::orderedUuid()->toString();
            $uuidHead = explode(separator: '-', string: $uuid)[0];

            $proposed = sprintf(
                '%s%s%s',
                $prefix,
                $uuidHead,
                StringHelper::shuffleBoth($length - mb_strlen($uuidHead) - mb_strlen($prefix)),
            );
        } while (static::check($proposed));

        return $proposed;
    }

    private static function check(string $proposed): bool
    {
        $check = OauthModelHelper::instance()::getByAttribute(AuthUserInterface::ATTRIBUTE_CODE, $proposed)->first();

        assert($check instanceof Authenticatable || $check == null);

        return !!$check?->getId();
    }
}
