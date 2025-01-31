<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\ApiController;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\UserResource;
use App\Jobs\SaveCookieCartToDB;
use App\Models\User;
use App\Services\CartService;
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

                    // when guest user logs in
                    // CartService::saveCookieItemsToCart($items); // just for testing/ delete this afterwards
                    SaveCookieCartToDB::dispatch($items, $user); // keep this one
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
