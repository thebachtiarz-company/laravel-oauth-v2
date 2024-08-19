<?php

namespace TheBachtiarz\OAuth\DTOs\Auth;

class NewAccessTokenInformation
{
    /**
     * Constructor
     */
    public function __construct(
        public readonly string $name,
        public readonly int|string $expires,
    ) {}
}
