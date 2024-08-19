<?php

namespace TheBachtiarz\OAuth\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Laravel\Sanctum\HasApiTokens;
use TheBachtiarz\Base\Interfaces\Models\ModelInterface;
use TheBachtiarz\Base\Traits\Models\ModelTrait;

abstract class Authenticatable extends User implements ModelInterface
{
    use Notifiable;
    use HasApiTokens;
    use HasFactory;
    use ModelTrait;

    /**
     * Define token expires time
     */
    protected ?Carbon $tokenExpiresAt = null;

    // ? Protected Methods

    /**
     * Create a new factory instance for the model.
     *
     * @return Factory<static>
     */
    protected static function newFactory()
    {
        return (new static())->modelFactory::new();
    }

    // ? Getter Modules

    /**
     * Get the value of tokenExpiresAt
     */
    public function getTokenExpiresAt(): ?Carbon
    {
        return $this->tokenExpiresAt;
    }

    // ? Setter Modules

    /**
     * Set the value of tokenExpiresAt
     */
    public function setTokenExpiresAt(?Carbon $tokenExpiresAt = null): static
    {
        $this->tokenExpiresAt = $tokenExpiresAt;

        return $this;
    }
}
