<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Resources\Api\Auth\AuthTokenResource;
use App\Service\Api\Auth\AuthService;

class AuthController extends Controller
{
    public function __construct(
        private readonly AuthService $authService
    ) {

    }

    public function login(LoginRequest $request): AuthTokenResource
    {
        return AuthTokenResource::make(
            $this->authService->login($request->getDto())
        );
    }

}
