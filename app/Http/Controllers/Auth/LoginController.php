<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\ApiController;

class LoginController extends ApiController
{
    public function __invoke(LoginRequest $request){
        $data = $request->validated();
  
        $user = User::where('email', $data['email'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            return $this->errorResponse('Wrong credentials', 401);
        }

        $token = $user->createToken("secretFORnowToken")->plainTextToken;
        
        $response = [
            'user' => $user,
            'token' => $token
        ];

        return $this->showMessage($response);
    }
}
