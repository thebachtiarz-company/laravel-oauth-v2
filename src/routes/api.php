<?php

use Illuminate\Support\Facades\Route;
use TheBachtiarz\OAuth\Http\Controllers\AuthController;

/**
 * Group    : User.
 * URI      : {{base_url}}/auth/user/---
 */
Route::prefix('user')->controller(AuthController::class)->group(function () {
    /**
     * Name     : Get user.
     * Method   : GET.
     * URL      : {{base_url}}/auth/user/get
     */
    Route::get('get', 'findUser');

    /**
     * Name     : Create user.
     * Method   : POST.
     * URL      : {{base_url}}/auth/user/create
     */
    Route::post('create', 'createUser');

    /**
     * Name     : Update user.
     * Method   : POST.
     * URL      : {{base_url}}/auth/user/update
     */
    Route::post('update', 'updateUser')->middleware('auth:sanctum');
});

/**
 * Group    : Token.
 * URI      : {{base_url}}/auth/token/---
 */
Route::prefix('token')->controller(AuthController::class)->group(function () {
    /**
     * Name     : Create token.
     * Method   : POST.
     * URL      : {{base_url}}/auth/token/create
     */
    Route::post('create', 'createToken');

    /**
     * Name     : Create token using user code.
     * Method   : POST.
     * URL      : {{base_url}}/auth/token/create-code
     */
    Route::post('create-code', 'createTokenUsingCode');

    /**
     * Name     : Get list token.
     * Method   : GET.
     * URL      : {{base_url}}/auth/token/list
     */
    Route::get('list', 'getListToken')->middleware('auth:sanctum');

    /**
     * Name     : Delete token.
     * Method   : POST.
     * URL      : {{base_url}}/auth/token/delete
     */
    Route::post('delete', 'deleteToken')->middleware('auth:sanctum');

    /**
     * Name     : Revoke token.
     * Method   : POST.
     * URL      : {{base_url}}/auth/token/revoke
     */
    Route::post('revoke', 'revokeToken')->middleware('auth:sanctum');
});
