<?php

namespace TheBachtiarz\OAuth\Interfaces\Repositories;

use TheBachtiarz\Base\Interfaces\Repositories\RepositoryInterface;
use TheBachtiarz\OAuth\Interfaces\Models\AuthUserInterface;

interface AuthUserRepositoryInterface extends RepositoryInterface
{
    /**
     * Get auth user by code
     */
    public function getByCode(string $code): ?AuthUserInterface;

    /**
     * Get auth user by identifier
     */
    public function getByIdentifier(string $identifier): ?AuthUserInterface;

    // ? Getter Modules

    // ? Setter Modules
}
