<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\ApiController;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;
use App\Jobs\SaveCookieCartToDB;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class LoginController extends ApiController
{

    public function __invoke(LoginRequest $request) : JsonResponse
    {
        $data = $request->validated();

        $user = User::where('email', $data['email'])->first();

        if (! $user || ! Hash::check($data['password'], $user->password)) {
            return $this->errorResponse('Wrong credentials', 401);
        }

        $token = $user->createToken('secretFORnowToken')->plainTextToken;

        if ($request->hasCookie('cart')) {
            $cookie_cart = $request->cookie('cart');
            $items = json_decode($cookie_cart, true);
            if (! empty($items) && array_key_exists('items', $items)) {
                $items = $items['items'];
                SaveCookieCartToDB::dispatch($items, $user);
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
