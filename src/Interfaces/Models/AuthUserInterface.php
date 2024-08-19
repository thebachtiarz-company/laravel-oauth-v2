<?php

namespace TheBachtiarz\OAuth\Interfaces\Models;

use Illuminate\Support\Carbon;
use TheBachtiarz\Base\Interfaces\Models\ModelInterface;

interface AuthUserInterface extends ModelInterface
{
    public const TABLE_NAME = 'auth_users';

    public const ATTRIBUTE_FILLABLE = [
        self::ATTRIBUTE_CODE,
        self::ATTRIBUTE_USERNAME,
        self::ATTRIBUTE_EMAIL,
        self::ATTRIBUTE_EMAIL_VERIFIED_AT,
        self::ATTRIBUTE_PASSWORD,
        self::ATTRIBUTE_REMEMBER_TOKEN,
    ];

    public const ATTRIBUTE_CODE = 'code';
    public const ATTRIBUTE_USERNAME = 'username';
    public const ATTRIBUTE_EMAIL = 'email';
    public const ATTRIBUTE_EMAIL_VERIFIED_AT = 'email_verified_at';
    public const ATTRIBUTE_PASSWORD = 'password';
    public const ATTRIBUTE_REMEMBER_TOKEN = 'remember_token';

    public const ATTRIBUTE_IDENTIFIER = 'identifier';

    public const TEMP_ORIGINAL_PASSWORD = 'oauthv2_original_password';

    public const USER_CODE_PREFIX = 'AuSrvT';

    // ? Getter Modules

    /**
     * Get code
     */
    public function getCode(): ?string;

    /**
     * Get identifier
     */
    public function getIdentifier(): ?string;

    /**
     * Get email verified at
     */
    public function getEmailVerifiedAt(): ?Carbon;

    /**
     * Get password
     */
    public function getPassword(bool $unHashed = false): ?string;

    // ? Setter Modules

    /**
     * Set code
     */
    public function setCode(string $code): self;

    /**
     * Set identifier
     */
    public function setIdentifier(string $identifier): self;

    /**
     * Set email verified at
     */
    public function setEmailVerifiedAt(Carbon $emailVerifiedAt): self;

    /**
     * Set password
     */
    public function setPassword(string $password): self;
}
