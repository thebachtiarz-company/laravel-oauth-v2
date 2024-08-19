<?php

namespace TheBachtiarz\OAuth\Repositories;

use TheBachtiarz\Base\Exceptions\BaseException;
use TheBachtiarz\Base\Repositories\AbstractRepository;
use TheBachtiarz\OAuth\Helpers\AuthUserHelper;
use TheBachtiarz\OAuth\Interfaces\Models\AuthUserInterface;
use TheBachtiarz\OAuth\Interfaces\Repositories\AuthUserRepositoryInterface;

class AuthUserRepository extends AbstractRepository implements AuthUserRepositoryInterface
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->setModelEntity(AuthUserInterface::class);

        parent::__construct();
    }

    // ? Public Methods

    /**
     * Get auth user by code
     */
    public function getByCode(string $code): ?AuthUserInterface
    {
        $this->modelBuilder(
            modelBuilder: $this->modelEntity::getByAttribute(AuthUserInterface::ATTRIBUTE_CODE, $code),
        );

        $entity = $this->modelBuilder()->first();

        if (!$entity && $this->throwIfNullEntity()) {
            throw new BaseException(
                message: sprintf('User with code \'%s\' not found!', $code),
                code: 404,
            );
        }

        return $entity;
    }

    /**
     * Get auth user by identifier
     */
    public function getByIdentifier(string $identifier): ?AuthUserInterface
    {
        $this->modelBuilder(
            modelBuilder: $this->modelEntity::getByAttribute(AuthUserHelper::authMethod(), $identifier),
        );

        $entity = $this->modelBuilder()->first();

        if (!$entity && $this->throwIfNullEntity()) {
            throw new BaseException(
                message: sprintf('User with identifier \'%s\' not found!', $identifier),
                code: 404,
            );
        }

        return $entity;
    }

    // ? Protected Methods

    // ? Private Methods

    // ? Getter Modules

    // ? Setter Modules
}
