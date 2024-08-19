<?php

namespace TheBachtiarz\OAuth\Http\Controllers;

use Illuminate\Http\JsonResponse;
use TheBachtiarz\Base\Http\Controllers\AbstractController;
use TheBachtiarz\Base\Http\Requests\Rules\PaginateRule;
use TheBachtiarz\OAuth\Http\Requests\CreateTokenRequest;
use TheBachtiarz\OAuth\Http\Requests\CreateTokenUsingCodeRequest;
use TheBachtiarz\OAuth\Http\Requests\CreateUserRequest;
use TheBachtiarz\OAuth\Http\Requests\DeleteTokenRequest;
use TheBachtiarz\OAuth\Http\Requests\FindUserRequest;
use TheBachtiarz\OAuth\Http\Requests\GetListTokenRequest;
use TheBachtiarz\OAuth\Http\Requests\Rules\AuthCodeRule;
use TheBachtiarz\OAuth\Http\Requests\Rules\AuthIdentifierRule;
use TheBachtiarz\OAuth\Http\Requests\Rules\AuthPasswordRule;
use TheBachtiarz\OAuth\Http\Requests\Rules\TokenNameRule;
use TheBachtiarz\OAuth\Http\Requests\UpdateUserRequest;
use TheBachtiarz\OAuth\Interfaces\Services\AuthUserServiceInterface;

class AuthController extends AbstractController
{
    /**
     * Constructor
     */
    public function __construct(
        protected AuthUserServiceInterface $authUserService,
    ) {
        parent::__construct();
    }

    // ? Public Methods

    /**
     * Find user using code
     */
    public function findUser(FindUserRequest $request): JsonResponse
    {
        $this->authUserService->getAuthUser(
            code: $request->input(AuthCodeRule::CODE),
        );

        return $this->getJsonResponse();
    }

    /**
     * Create user
     */
    public function createUser(CreateUserRequest $request): JsonResponse
    {
        $this->authUserService->createAuthUser(
            identifier: $request->input(AuthIdentifierRule::IDENTIFIER),
            password: $request->input(AuthPasswordRule::PASSWORD),
        );

        return $this->getJsonResponse();
    }

    /**
     * Update user
     */
    public function updateUser(UpdateUserRequest $request): JsonResponse
    {
        $this->authUserService->updateAuthUser(
            identifier: $request->input(AuthIdentifierRule::IDENTIFIER),
            password: $request->input(AuthPasswordRule::PASSWORD),
        );

        return $this->getJsonResponse();
    }

    /**
     * Create token
     */
    public function createToken(CreateTokenRequest $request): JsonResponse
    {
        $this->authUserService->createAuthToken(
            identifier: $request->input(AuthIdentifierRule::IDENTIFIER),
            password: $request->input(AuthPasswordRule::PASSWORD),
        );

        return $this->getJsonResponse();
    }

    /**
     * Create token using user code
     */
    public function createTokenUsingCode(CreateTokenUsingCodeRequest $request): JsonResponse
    {
        $this->authUserService->createAuthTokenUsingAuthCode(
            authCode: $request->input(AuthCodeRule::CODE),
        );

        return $this->getJsonResponse();
    }

    /**
     * Get list of user token
     */
    public function getListToken(GetListTokenRequest $request): JsonResponse
    {
        $this->authUserService->getListAuthToken(
            perPage: $request->input(PaginateRule::PER_PAGE, 15),
            currentPage: $request->input(PaginateRule::CURRENT_PAGE, 1),
        );

        return $this->getJsonResponse();
    }

    /**
     * Delete token
     */
    public function deleteToken(DeleteTokenRequest $request): JsonResponse
    {
        $this->authUserService->deleteToken(
            tokenName: $request->input(TokenNameRule::NAME),
        );

        return $this->getJsonResponse();
    }

    /**
     * Revoke token
     */
    public function revokeToken(): JsonResponse
    {
        $this->authUserService->revokeTokens();

        return $this->getJsonResponse();
    }

    // ? Protected Methods

    // ? Private Methods

    // ? Getter Modules

    // ? Setter Modules
}
