<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\ApiController;
use App\Http\Requests\RegisterUserRequest;

class AuthController extends ApiController
{
    
    public function register(RegisterUserRequest $request){

        $data = $request->all();
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

    public function login(Request $request){
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'required',
         ]);
   
         $user = User::where('email', $request->email)->first();
   
         if (!$user || !Hash::check($request->password, $user->password)) {
            return $this->errorResponse('Wrong credentials', 401);
         }
   
         return $user->createToken($request->device_name)->plainTextToken;
    }



}
