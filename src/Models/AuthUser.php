<?php

namespace TheBachtiarz\OAuth\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use TheBachtiarz\Base\Helpers\General\TemporaryHelper;
use TheBachtiarz\OAuth\Helpers\AuthUserHelper;
use TheBachtiarz\OAuth\Helpers\UserCodeHelper;
use TheBachtiarz\OAuth\Interfaces\Models\AuthUserInterface;
use TheBachtiarz\OAuth\Models\Factories\AuthUserFactory;

class AuthUser extends Authenticatable implements AuthUserInterface
{
    use SoftDeletes;

    /**
     * Auth user identifier method
     */
    protected string $authMethod;

    /**
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->setTable(self::TABLE_NAME);
        $this->fillable(self::ATTRIBUTE_FILLABLE);
        $this->setHidden([
            self::ATTRIBUTE_EMAIL,
            self::ATTRIBUTE_USERNAME,
            self::ATTRIBUTE_PASSWORD,
            self::ATTRIBUTE_REMEMBER_TOKEN,
            self::ATTRIBUTE_EMAIL_VERIFIED_AT,
            self::ATTRIBUTE_DELETED_AT,
        ]);

        $this->modelFactory = AuthUserFactory::class;

        $this->authMethod = AuthUserHelper::authMethod();

        parent::__construct($attributes);
    }

    // ? Public Methods

    #[\Override]
    public function save(array $options = [])
    {
        if (!$this->getCode()) {
            $this->setCode(UserCodeHelper::generate());
        }

        return parent::save(options: $options);
    }

    // ? Protected Methods

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string,string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ? Private Methods

    // ? Getter Modules

    /**
     * Get code
     */
    public function getCode(): ?string
    {
        return $this->{self::ATTRIBUTE_CODE};
    }

    /**
     * Get identifier
     */
    public function getIdentifier(): ?string
    {
        return $this->{$this->authMethod};
    }

    /**
     * Get email verified at
     */
    public function getEmailVerifiedAt(): ?Carbon
    {
        return $this->{self::ATTRIBUTE_EMAIL_VERIFIED_AT};
    }

    /**
     * Get password
     */
    public function getPassword(bool $unHashed = false): ?string
    {
        return $unHashed ? TemporaryHelper::get(attribute: self::TEMP_ORIGINAL_PASSWORD) : $this->{self::ATTRIBUTE_PASSWORD};
    }

    // ? Setter Modules

    /**
     * Set code
     */
    public function setCode(string $code): self
    {
        $this->{self::ATTRIBUTE_CODE} = $code;

        return $this;
    }

    /**
     * Set identifier
     */
    public function setIdentifier(string $identifier): self
    {
        $this->{$this->authMethod} = $identifier;

        return $this;
    }

    /**
     * Set email verified at
     */
    public function setEmailVerifiedAt(Carbon $emailVerifiedAt): self
    {
        $this->{self::ATTRIBUTE_EMAIL_VERIFIED_AT} = $emailVerifiedAt;

        return $this;
    }

    /**
     * Set password
     */
    public function setPassword(string $password): self
    {
        $this->{self::ATTRIBUTE_PASSWORD} = $password;
        TemporaryHelper::push(attribute: self::TEMP_ORIGINAL_PASSWORD, value: $password);

        return $this;
    }

    // ? Scopes

    // ? Maps

    public function simpleListMap(array $attributes = [], array $hides = []): array
    {
        $this->setData(attribute: 'identifier', value: $this->getIdentifier());

        $attributes[] = 'identifier';

        return parent::simpleListMap(attributes: $attributes, hides: $hides);
    }
}
