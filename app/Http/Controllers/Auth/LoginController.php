<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\ApiController;
use App\Http\Resources\UserResource;

class LoginController extends ApiController
{
    public function __invoke(LoginRequest $request){
        $data = $request->validated();
  
        $user = User::where('email', $data['email'])->first();
        $user = UserResource::make($user);

        if (!$user || !Hash::check($data['password'], $user->password)) {
            return $this->errorResponse('Wrong credentials', 401);
        }

        $token = $user->createToken("secretFORnowToken")->plainTextToken;
        
        $response = [
            'user' => $user,
            'token' => $token
        ];

        return 'test';

        return $this->showMessage($response);
    }
}
