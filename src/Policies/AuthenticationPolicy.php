<?php

namespace TheBachtiarz\OAuth\Policies;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Auth;
use TheBachtiarz\OAuth\Helpers\AuthUserHelper;
use TheBachtiarz\OAuth\Helpers\OauthModelHelper;
use TheBachtiarz\OAuth\Interfaces\Models\AuthUserInterface;
use TheBachtiarz\OAuth\Models\Authenticatable;

class AuthenticationPolicy
{
    /**
     * Define credentials
     *
     * @param string $identifier Use email/username based from configuration.
     * @param string $password Credential password.
     * @param array $abilities Set credential ability, default: all.
     */
    public function __construct(
        public string $identifier,
        public string $password,
        public array $abilities = ['*'],
    ) {}

    // ? Public Methods

    /**
     * Create a new session based from credentials
     *
     * @throws AuthenticationException
     */
    public function createSession(): Authenticatable
    {
        Auth::attempt(
            credentials: [
                AuthUserHelper::authMethod() => $this->identifier,
                AuthUserInterface::ATTRIBUTE_PASSWORD => $this->password,
            ],
            remember: true,
        );

        if (!Auth::hasUser()) {
            throw new AuthenticationException('User not found.');
        }

        return OauthModelHelper::instance()::find(Auth::user()->id);
    }
}
