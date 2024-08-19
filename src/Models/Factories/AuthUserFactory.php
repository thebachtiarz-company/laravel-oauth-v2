<?php

namespace TheBachtiarz\OAuth\Models\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use TheBachtiarz\OAuth\Helpers\UserCodeHelper;
use TheBachtiarz\OAuth\Interfaces\Models\AuthUserInterface;
use TheBachtiarz\OAuth\Models\AuthUser;

/**
 * @extends Factory<AuthUser>
 */
class AuthUserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<Model>
     */
    protected $model = AuthUser::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            AuthUserInterface::ATTRIBUTE_CODE => UserCodeHelper::generate(),
            AuthUserInterface::ATTRIBUTE_USERNAME => Str::random(),
            AuthUserInterface::ATTRIBUTE_EMAIL => fake()->safeEmail(),
            AuthUserInterface::ATTRIBUTE_EMAIL_VERIFIED_AT => now(),
            AuthUserInterface::ATTRIBUTE_PASSWORD => 'password',
        ];
    }
}
