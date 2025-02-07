<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\ApiController;
use App\Http\Requests\Auth\RegisterUserRequest;
use App\Jobs\SaveCookieCartToDB;
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

        if ($request->hasCookie('cart')) {
            $cookie_cart = $request->cookie('cart');

            if (is_string($cookie_cart)) { // Check if $cookie_cart is a string
                $items = json_decode($cookie_cart, true);

                if (! empty($items) && is_array($items) && array_key_exists('items', $items)) {
                    $items = $items['items'];
                    // TODO: Sync items from cookie to cart
                }
            }
        }

        $response = [
            'user' => $user,
            'token' => $token,
        ];

        return $this->showMessage($response, 201);
    }
}
