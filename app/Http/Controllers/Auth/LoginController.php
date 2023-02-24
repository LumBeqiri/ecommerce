<?php

namespace App\Http\Controllers\Auth;

use App\Models\Cart;
use App\Models\User;
use App\Services\CartService;
use App\Jobs\SaveCookieCartToDB;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\ApiController;

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
            $cart = json_decode($cookie_cart, true);
            if (! empty($cart) && array_key_exists('items', $cart)) {
                $items = $cart['items'];
                SaveCookieCartToDB::dispatch($items, $user,1);
            }
        }

        $user = new UserResource($user);

        $response = [
            'user' => $user,
            'token' => $token,
        ];

        return $this->showMessage($response);
    }
}
