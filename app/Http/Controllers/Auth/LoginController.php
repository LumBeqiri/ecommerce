<?php

namespace App\Http\Controllers\Auth;

use App\Data\CartItemData;
use App\Http\Controllers\ApiController;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\CartService;
use App\values\Roles;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class LoginController extends ApiController
{
    public function __invoke(LoginRequest $request): JsonResponse
    {
        $data = $request->validated();
        $user = User::where('email', $data['email'])->first();

        if (! $user || ! Hash::check($data['password'], $user->password)) {
            return $this->errorResponse('Wrong credentials', 401);
        }

        $token = $user->createToken('secretFORnowToKEn')->plainTextToken;


        if (isset($data['cart_items']) && is_array($data['cart_items']) && $user->hasRole(Roles::BUYER)) {
            $cartItemsDTO = collect($data['cart_items'])
                ->map(fn ($item) => new CartItemData($item['variant_id'], $item['quantity']))
                ->all();

            CartService::syncItemsToCart($user, $cartItemsDTO);
        }

        $response = [
            'user' => new UserResource($user),
            'token' => $token,
        ];

        return $this->showMessage($response)->withCookie(cookie('token', $token, config('sanctum.expiration')));
    }
}
