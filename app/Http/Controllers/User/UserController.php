<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\ApiController;
use App\Http\Requests\StoreUserRequest;
use App\Http\Resources\UserResource;
use App\Mail\UserCreated;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class UserController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();

        return $this->showAll(UserResource::collection($users));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreUserRequest  $request
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
     * @param  User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return $this->showOne(new UserResource($user));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  StoreUserRequest  $request
     * @param  User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(StoreUserRequest $request, User $user)
    {
        $request->validated();

        if ($request->has('name')) {
            $user->name = $request->name;
        }

        if ($request->has('email') && $user->email != $request->email) {
            $user->verified = User::UNVERIFIED_USER;
            $user->verification_token = User::generateVerificationCode();
            $user->email = $request->email;
        }

        if ($request->has('password')) {
            $user->password = bcrypt($request->password);
        }

        if ($request->has('city')) {
            $user->city = $request->city;
        }

        if ($request->has('state')) {
            $user->state = $request->state;
        }

        if ($request->has('zip')) {
            $user->zip = $request->zip;
        }

        if ($request->has('phone')) {
            $user->phone = $request->phone;
        }

        if ($request->has('admin')) {
            if (! $user->isVerified()) {
                return $this->errorResponse('Only verified users can modify the admin field', 409);
            }
            $user->admin = $request->admin;
        }

        if (! $user->isDirty()) {
            return $this->errorResponse('Please specify a field to update', 409);
        }

        $user->save();

        return $this->showOne(new UserResource($user));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();

        return $this->showOne(new UserResource($user));
    }

    public function verify($token)
    {
        $user = User::where('verification_token', $token)->firstOrFail();

        $user->verified = User::VERIFIED_USER;
        $user->email_verified_at = now();
        $user->verification_token = null;

        $user->save();

        return $this->showMessage('Account has been verified!');
    }

    public function resend(User $user)
    {
        if ($user->isVerified()) {
            return $this->errorResponse('This user is already verified', 409);
        }

        Mail::to($user)->send(new UserCreated($user));

        return $this->showMessage('The verification email has been resent');
    }
}
