<?php

namespace App\Http\Controllers\Admin\Users;

use App\Http\Controllers\ApiController;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class AdminUserController extends ApiController
{
    public function index(): JsonResponse
    {
        return $this->showAll(UserResource::collection(User::all()));
    }

    public function store(StoreUserRequest $request): JsonResponse
    {
        $data = $request->validated();

        $data['password'] = bcrypt($request->password);
        $data['verified'] = User::UNVERIFIED_USER;
        $data['verification_token'] = User::generateVerificationCode();

        $user = User::create($data);

        return $this->showOne(new UserResource($user));
    }

    public function show(User $user): JsonResponse
    {
        return $this->showOne(new UserResource($user));
    }

    public function update(StoreUserRequest $request, User $user): JsonResponse
    {
        $request->validated();

        $user->fill($request->only(['city', 'zip', 'phone', 'country_id']));

        if ($request->has('email')) {
            $user->verified = User::UNVERIFIED_USER;
            $user->verification_token = User::generateVerificationCode();
            $user->email = $request->email;
        }

        if ($request->has('password')) {
            $user->password = bcrypt($request->password);
        }

        $user->save();

        return $this->showOne(new UserResource($user));
    }

    public function destroy(User $user): JsonResponse
    {
        if ($user->id == 1) {
            return $this->showError('This user cannot be deleted');
        }

        $user->delete();

        return $this->showMessage('User deleted successfully!');
    }
}
