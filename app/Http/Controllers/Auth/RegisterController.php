<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\ApiController;
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

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return $this->showMessage($response, 201);

    }
}
