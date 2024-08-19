<?php

namespace TheBachtiarz\OAuth\Services;

use Illuminate\Support\Carbon;
use Laravel\Sanctum\NewAccessToken;
use TheBachtiarz\Base\DTOs\Services\ResponseDataDTO;
use TheBachtiarz\Base\Enums\Services\ResponseConditionEnum;
use TheBachtiarz\Base\Enums\Services\ResponseHttpCodeEnum;
use TheBachtiarz\Base\Enums\Services\ResponseStatusEnum;
use TheBachtiarz\Base\Interfaces\Libraries\SearchCriteriaInputInterface;
use TheBachtiarz\Base\Services\AbstractService;
use TheBachtiarz\OAuth\Helpers\AuthTokenHelper;
use TheBachtiarz\OAuth\Helpers\AuthUserHelper;
use TheBachtiarz\OAuth\Interfaces\Models\AuthUserInterface;
use TheBachtiarz\OAuth\Interfaces\Repositories\AuthTokenRepositoryInterface;
use TheBachtiarz\OAuth\Interfaces\Repositories\AuthUserRepositoryInterface;
use TheBachtiarz\OAuth\Interfaces\Services\AuthUserServiceInterface;
use TheBachtiarz\OAuth\Models\AuthToken;
use TheBachtiarz\OAuth\Models\AuthUser;
use TheBachtiarz\OAuth\Policies\AuthenticationPolicy;

class AuthUserService extends AbstractService implements AuthUserServiceInterface
{
    /**
     * Constructor
     */
    public function __construct(
        protected AuthUserRepositoryInterface $authUserRepository,
        protected AuthTokenRepositoryInterface $authTokenRepository,
        protected SearchCriteriaInputInterface $searchCriteriaInput,
    ) {}

    /**
     * Get auth user by code
     */
    public function getAuthUser(string $code): ResponseDataDTO
    {
        try {
            $this->setResponse(new ResponseDataDTO(
                condition: ResponseConditionEnum::TRUE,
                status: ResponseStatusEnum::SUCCESS,
                httpCode: ResponseHttpCodeEnum::OK,
                message: 'User detail',
                data: $this->authUserRepository->getByCode(code: $code)?->simpleListMap(),
            ));
        } catch (\Throwable $th) {
            $this->log($th, 'error');

            $this->setResponse(new ResponseDataDTO(
                message: $th->getMessage(),
            ));
        }

        return $this->getResponse();
    }

    /**
     * Create a new auth user
     */
    public function createAuthUser(string $identifier, string $password): ResponseDataDTO
    {
        try {
            $prepare = new AuthUser();
            $prepare->setIdentifier(identifier: $identifier)->setPassword(password: $password);

            if (config(key: 'tboauth.email_auto_verified', default: false)) {
                $prepare->setEmailVerifiedAt(Carbon::now());
            }

            $this->setResponse(new ResponseDataDTO(
                condition: ResponseConditionEnum::TRUE,
                status: ResponseStatusEnum::SUCCESS,
                httpCode: ResponseHttpCodeEnum::OK,
                message: 'Successfully create a new user',
                data: $this->authUserRepository->createOrUpdate(model: $prepare)->simpleListMap(),
            ));
        } catch (\Throwable $th) {
            $this->log($th, 'error');

            $this->setResponse(new ResponseDataDTO(
                message: $th->getMessage(),
            ));
        }

        return $this->getResponse();
    }

    /**
     * Update auth user
     */
    public function updateAuthUser(string $identifier, string $password): ResponseDataDTO
    {
        try {
            $currentAuth = AuthTokenHelper::currentAuthenticatable();

            assert($currentAuth instanceof AuthUserInterface);

            AuthUserHelper::preventIfIdentifierIsExist(auth: $currentAuth, identifier: $identifier);

            $currentAuth->setIdentifier(identifier: $identifier)->setPassword(password: $password);

            $this->setResponse(new ResponseDataDTO(
                condition: ResponseConditionEnum::TRUE,
                status: ResponseStatusEnum::SUCCESS,
                httpCode: ResponseHttpCodeEnum::OK,
                message: 'Successfully update current user',
                data: $this->authUserRepository->createOrUpdate(model: $currentAuth)->simpleListMap(),
            ));
        } catch (\Throwable $th) {
            $this->log($th, 'error');

            $this->setResponse(new ResponseDataDTO(
                message: $th->getMessage(),
            ));
        }

        return $this->getResponse();
    }

    /**
     * Create new token from user identifier
     */
    public function createAuthToken(string $identifier, string $password): ResponseDataDTO
    {
        try {
            $authentication = (new AuthenticationPolicy(
                identifier: $identifier,
                password: $password,
            ))->createSession();

            $process = $this->authTokenRepository->createToken(user: $authentication);

            assert($process instanceof NewAccessToken);

            $this->setResponse(new ResponseDataDTO(
                condition: ResponseConditionEnum::TRUE,
                status: ResponseStatusEnum::SUCCESS,
                httpCode: ResponseHttpCodeEnum::OK,
                message: 'New token credential',
                data: AuthTokenHelper::newAccessTokenModify(newAccessToken: $process),
            ));
        } catch (\Throwable $th) {
            $this->log($th, 'error');

            $this->setResponse(new ResponseDataDTO(
                message: $th->getMessage(),
            ));
        }

        return $this->getResponse();
    }

    /**
     * Create new token using auth user code
     */
    public function createAuthTokenUsingAuthCode(string $authCode): ResponseDataDTO
    {
        try {
            $authentication = $this->authUserRepository->getByCode(code: $authCode);

            assert($authentication instanceof AuthUser);

            $process = $this->authTokenRepository->createToken(user: $authentication);

            assert($process instanceof NewAccessToken);

            $this->setResponse(new ResponseDataDTO(
                condition: ResponseConditionEnum::TRUE,
                status: ResponseStatusEnum::SUCCESS,
                httpCode: ResponseHttpCodeEnum::OK,
                message: 'New token credential',
                data: AuthTokenHelper::newAccessTokenModify(newAccessToken: $process),
            ));
        } catch (\Throwable $th) {
            $this->log($th, 'error');

            $this->setResponse(new ResponseDataDTO(
                message: $th->getMessage(),
            ));
        }

        return $this->getResponse();
    }

    /**
     * Get list auth token by current authenticated user
     */
    public function getListAuthToken(int $perPage = 15, int $currentPage = 1): ResponseDataDTO
    {
        try {
            $input = $this->searchCriteriaInput;
            $input->setPerPage($perPage)->setCurrentPage($currentPage);

            $tokens = $this->authTokenRepository->searchCriteria(input: $input);

            $this->setResponse(new ResponseDataDTO(
                condition: ResponseConditionEnum::TRUE,
                status: ResponseStatusEnum::SUCCESS,
                httpCode: ResponseHttpCodeEnum::OK,
                message: 'List of token credential',
                data: $tokens->getResultPaginate()->toArraySimple(),
            ));
        } catch (\Throwable $th) {
            $this->log($th, 'error');

            $this->setResponse(new ResponseDataDTO(
                message: $th->getMessage(),
            ));
        }

        return $this->getResponse();
    }

    /**
     * Delete token by token name based from current authenticated user
     */
    public function deleteToken(string $tokenName): ResponseDataDTO
    {
        try {
            AuthTokenHelper::currentAuthenticatable();

            $token = $this->authTokenRepository->getByName(name: $tokenName);

            if (!$token) {
                throw new \Exception(message: 'Credential not found');
            }

            assert($token instanceof AuthToken);

            $this->setResponse(new ResponseDataDTO(
                condition: ResponseConditionEnum::TRUE,
                status: ResponseStatusEnum::SUCCESS,
                httpCode: ResponseHttpCodeEnum::OK,
                message: 'Successfully delete credential',
                data: $token->delete(),
            ));
        } catch (\Throwable $th) {
            $this->log($th, 'error');

            $this->setResponse(new ResponseDataDTO(
                message: $th->getMessage(),
            ));
        }

        return $this->getResponse();
    }

    public function destroyAuthToken(): ResponseDataDTO
    {
        try {
            $authentication = AuthTokenHelper::currentAuthenticatable();

            $this->setResponse(new ResponseDataDTO(
                condition: ResponseConditionEnum::TRUE,
                status: ResponseStatusEnum::SUCCESS,
                httpCode: ResponseHttpCodeEnum::OK,
                message: 'Successfully logout',
                data: $authentication->tokens()->where(),
            ));
        } catch (\Throwable $th) {
            $this->log($th, 'error');

            $this->setResponse(new ResponseDataDTO(
                message: $th->getMessage(),
            ));
        }

        return $this->getResponse();
    }

    /**
     * Revoke all token based from current authenticated user
     */
    public function revokeTokens(): ResponseDataDTO
    {
        try {
            $authentication = AuthTokenHelper::currentAuthenticatable();

            $tokens = $this->authTokenRepository->deleteByUser(user: $authentication);

            $this->setResponse(new ResponseDataDTO(
                condition: ResponseConditionEnum::TRUE,
                status: ResponseStatusEnum::SUCCESS,
                httpCode: ResponseHttpCodeEnum::OK,
                message: 'Successfully revoke all credential',
                data: $tokens,
            ));
        } catch (\Throwable $th) {
            $this->log($th, 'error');

            $this->setResponse(new ResponseDataDTO(
                message: $th->getMessage(),
            ));
        }

        return $this->getResponse();
    }
}
