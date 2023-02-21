<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\ApiController;
use App\Http\Requests\RegisterUserRequest;
use App\Jobs\SaveCookieCartToDB;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class RegisterController extends ApiController
{

    public function register(RegisterUserRequest $request) : JsonResponse
    {
        $data = $request->validated();
        $data['password'] = bcrypt($data['password']);
        $data['verification_token'] = User::generateVerificationCode();

        $user = User::create($data);

        $token = $user->createToken('ecommerceToken')->plainTextToken;

        if ($request->hasCookie('cart')) {
            $cookie_cart = $request->cookie('cart');
            $items = json_decode($cookie_cart, true);
            if (! empty($items) && array_key_exists('items', $items)) {
                $items = $items['items'];
                SaveCookieCartToDB::dispatch($items, $user);
            }
        }

        $response = [
            'user' => $user,
            'token' => $token,
        ];

        return $this->showMessage($response, 201);
    }
}
