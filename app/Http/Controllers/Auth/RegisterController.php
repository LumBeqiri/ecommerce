<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\ApiController;
use App\Http\Requests\Auth\RegisterUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class RegisterController extends ApiController
{
    public function register(RegisterUserRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['password'] = bcrypt($data['password']);
        $data['verification_token'] = User::generateVerificationCode();

        $user = User::create($data);

        $token = $user->createToken('ecommerceToken')->plainTextToken;

        $response = [
            'user' => new UserResource($user),
            'token' => $token,
        ];

        return $this->showMessage($response, 201);
    }
}
