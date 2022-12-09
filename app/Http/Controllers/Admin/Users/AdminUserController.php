<?php

namespace App\Http\Controllers\Admin\Users;

use App\Models\User;
use App\Http\Resources\UserResource;
use App\Http\Controllers\ApiController;
use App\Http\Requests\StoreUserRequest;

class AdminUserController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->showAll(UserResource::collection(User::all()));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();

        $data['password'] = bcrypt($request->password);
        $data['verified'] = User::UNVERIFIED_USER;
        $data['verification_token'] = User::generateVerificationCode();

        $user = User::create($data);

        return $this->showOne(new UserResource($user));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return $this->showOne(new UserResource($user));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreUserRequest $request, User $user)
    {
        $request->validated();


        $user->fill($request->only(['name', 'city', 'state', 'zip', 'phone']));

        if($request->has('email')){
            $user->verified = User::UNVERIFIED_USER;
            $user->verification_token = User::generateVerificationCode();
            $user->email = $request->email;
        }

        if($request->has('password')){ $user->password = bcrypt($request->password);}

        if($request->has('admin')){
            if(!$user->isVerified()){
                return $this->errorResponse('Only verified users can modify the admin field',409);
            }
            $user->assignRole('admin');
        }

        if(!$user->isDirty()){
            return $this->errorResponse('Please specify a field to update', 409);
        }

        $user->save();

        return $this->showOne(new UserResource($user));
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  User $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {   
         if($user->id != 1){
            $user->delete();
         }
        
         return $this->showMessage('User deleted successfully!');
    }
}
