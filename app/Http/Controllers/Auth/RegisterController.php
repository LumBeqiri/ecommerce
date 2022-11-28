<?php

namespace App\Http\Controllers\Auth;

use Error;
use App\Models\User;
use App\Services\CartService;
use App\Jobs\SaveCookieCartToDB;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\RegisterUserRequest;

class RegisterController extends ApiController
{
    
    /**
     * @param RegisterUserRequest $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function register(RegisterUserRequest $request){

        $data = $request->validated();
        $data['password'] = bcrypt($data['password']);
        $data['verification_token'] = User::generateVerificationCode();

        $user = User::create($data);

        $token = $user->createToken('ecommerceToken')->plainTextToken;

        if($request->hasCookie('cart')){
            $cookie_cart = $request->cookie('cart');
            $items = json_decode($cookie_cart, true);
            if(!empty($items) && array_key_exists('items', $items)){
                $items = $items['items'];
                SaveCookieCartToDB::dispatch($items, $user);
            }
        }

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return $this->showMessage($response, 201);

    }
}
