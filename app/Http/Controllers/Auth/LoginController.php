<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Services\CartService;
use App\Jobs\SaveCookieCartToDB;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\ApiController;
use App\Models\Variant;
use Illuminate\Support\Facades\Validator;

class LoginController extends ApiController
{
    /**
     * @param LoginRequest $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function __invoke(LoginRequest $request){
        $data = $request->validated();
        
        $user = User::where('email', $data['email'])->first();
        
        if (!$user || !Hash::check($data['password'], $user->password)) {
            return $this->errorResponse('Wrong credentials', 401);
        }

        $token = $user->createToken("secretFORnowToken")->plainTextToken;

        if($request->hasCookie('cart')){
            $cookie_cart = $request->cookie('cart');
            $items = json_decode($cookie_cart, true);
            if(!empty($items) && array_key_exists('items', $items)){
                $items = $items['items'];
                // CartService::moveCartFromCookieToDB($items, $user);
                SaveCookieCartToDB::dispatch($items, $user);
            }
        }

        $user = UserResource::make($user);


        
        $response = [
            'user' => $user,
            'token' => $token
        ];

        return $this->showMessage($response);
    }
}
