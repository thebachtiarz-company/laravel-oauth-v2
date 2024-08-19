<?php

namespace TheBachtiarz\OAuth\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Laravel\Sanctum\NewAccessToken;
use TheBachtiarz\Base\DTOs\Libraries\Search\InputFilterDTO;
use TheBachtiarz\Base\DTOs\Libraries\Search\InputSortDTO;
use TheBachtiarz\Base\Enums\Generals\ModelFilterOperatorEnum;
use TheBachtiarz\Base\Enums\Generals\ModelSortDirectionEnum;
use TheBachtiarz\Base\Interfaces\Libraries\SearchCriteriaInputInterface;
use TheBachtiarz\Base\Interfaces\Libraries\SearchCriteriaOutputInterface;
use TheBachtiarz\Base\Repositories\AbstractRepository;
use TheBachtiarz\OAuth\Helpers\AuthTokenHelper;
use TheBachtiarz\OAuth\Interfaces\Models\AuthTokenInterface;
use TheBachtiarz\OAuth\Interfaces\Repositories\AuthTokenRepositoryInterface;
use TheBachtiarz\OAuth\Models\Authenticatable;

class AuthTokenRepository extends AbstractRepository implements AuthTokenRepositoryInterface
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->setModelEntity(AuthTokenInterface::class);

        parent::__construct();
    }

    /**
     * Get token by name
     */
    public function getByName(string $name): ?AuthTokenInterface
    {
        $this->modelBuilder(modelBuilder: $this->modelEntity->getByUserName(AuthTokenHelper::currentAuthenticatable(), $name));

        return $this->modelBuilder()->first();
    }

    /**
     * Get list token by user
     *
     * @return Collection<AuthTokenInterface>
     */
    public function getByUser(Authenticatable $user): Collection
    {
        return $this->modelEntity->getByUser($user)->get();
    }

    public function searchCriteria(SearchCriteriaInputInterface $input): SearchCriteriaOutputInterface
    {
        $currentAuth = AuthTokenHelper::currentAuthenticatable();

        $input->setFilters(new \Illuminate\Support\Collection([
            new InputFilterDTO(column: 'tokenable_type', operator: ModelFilterOperatorEnum::EQUAL, value: $currentAuth::class),
            new InputFilterDTO(column: 'tokenable_id', operator: ModelFilterOperatorEnum::EQUAL, value: $currentAuth->getId()),
        ]));

        $input->setSorts(new \Illuminate\Support\Collection([
            new InputSortDTO(column: $this->modelEntity->getCreatedAtColumn(), direction: ModelSortDirectionEnum::DESC),
        ]));

        $input->setMapResult(map: fn(AuthTokenInterface $token): array => [
            'name' => $token->getTokenName(),
            'last_used' => $token->getTokenLastUsed()?->getTimestamp() ?? 'Unused',
            'expired' => $token->getTokenExpiredAt()?->getTimestamp() ?? 'Never',
            'created' => $token->getTokenCreatedAt()->getTimestamp(),
        ]);

        return parent::searchCriteria(input: $input);
    }

    /**
     * Create new token from user
     */
    public function createToken(Authenticatable $user, array $abilities = ['*']): NewAccessToken
    {
        return $user->createToken(
            name: Str::ulid()->toBase58(),
            abilities: $abilities,
            expiresAt: $user->getTokenExpiresAt(),
        );
    }

    /**
     * Delete token by name
     */
    public function deleteByName(string $name): bool
    {
        $token = $this->getByName(name: $name);

        assert($token instanceof Model || $token === null);

        return $token?->delete() ?? false;
    }

    /**
     * Delete all token from user
     */
    public function deleteByUser(Authenticatable $user): bool
    {
        $collection = $this->getByUser(user: $user);

        foreach ($collection->all() ?? [] as $key => $token) {
            assert($token instanceof AuthTokenInterface);

            $this->deleteByName($token->getTokenName());
        }

        return true;
    }
}
