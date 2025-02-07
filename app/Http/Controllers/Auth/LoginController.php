<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\ApiController;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
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

        $token = $user->createToken('secretFORnowToken')->plainTextToken;

        if ($request->hasCookie('cart')) {
            $cookie_cart = $request->cookie('cart');

            if (is_string($cookie_cart) && ! empty($cookie_cart)) {
                $cart = json_decode($cookie_cart, true);

                if (! empty($cart) && array_key_exists('items', $cart)) {
                    $items = $cart['items'];

                    // TODO: Sync items from cookie to cart

                }
            }
        }

        $user = new UserResource($user);

        $response = [
            'user' => $user,
            'token' => $token,
        ];

        return $this->showMessage($response)->withCookie(cookie('token', $token, config('sanctum.expiration')));
    }
}
