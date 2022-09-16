<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\ApiController;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Resources\UserResource;
use App\Mail\UserPasswordChanged;
use Mail;

class ChangePasswordController extends ApiController
{
    /**
     * @param ChangePasswordRequest $request
     * 
     * @return UserResource
     */
    public function __invoke(ChangePasswordRequest $request){
        $data = $request->validated();

        $user = User::whereEmail(auth()->user()->email)->first();

        if (!$user || !Hash::check($data['old_password'], $user->password)) {
            return $this->errorResponse('Wrong credentials', 401);
        }
  

        if($user->isDirty('password')){
            return $this->errorResponse('cannot change the password', 400);
        }

        $user->password= bcrypt($data['new_password']);

        $user->save();
        
        retry(5, function() use($user){
            Mail::to($user)->send(new UserPasswordChanged($user));
        });

        return new UserResource($user);
    
    }
}
