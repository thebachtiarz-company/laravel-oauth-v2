<?php

namespace TheBachtiarz\OAuth\DTOs\Auth;

use Illuminate\Contracts\Support\Arrayable;

class NewAccessTokenResponseDTO implements Arrayable
{
    /**
     * Constructor
     */
    public function __construct(
        public readonly NewAccessTokenInformation $information,
        public readonly string $token,
    ) {}

    public function toArray()
    {
        return [
            'information' => $this->information,
            'token' => $this->token,
        ];
    }
}
