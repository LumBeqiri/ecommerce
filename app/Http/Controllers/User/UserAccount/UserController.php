<?php

namespace App\Http\Controllers\User\UserAccount;

use App\Http\Controllers\ApiController;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Resources\UserResource;
use App\Mail\UserCreated;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;

class UserController extends ApiController
{
    public function show(User $user): JsonResponse
    {
        $this->authorize('view', $user);

        return $this->showOne(new UserResource($user));
    }

    public function store(StoreUserRequest $request): JsonResponse
    {
        $this->authorize('create', User::class);

        $user = User::create($request->validated());

        return $this->showOne(new UserResource($user));
    }

    public function destroy(User $user): JsonResponse
    {
        $this->authorize('delete', $user);

        $user->delete();

        return $this->showMessage('User deleted successfully');
    }

    public function verify(string $token): JsonResponse
    {
        $user = User::where('verification_token', $token)->firstOrFail();

        $user->verified = (bool) User::VERIFIED_USER;
        $user->email_verified_at = now();
        $user->verification_token = null;

        $user->save();

        return $this->showMessage('Account has been verified!');
    }

    public function resend(User $user): JsonResponse
    {
        if ($user->isVerified()) {
            return $this->errorResponse('This user is already verified', 409);
        }

        Mail::to($user)->send(new UserCreated($user));

        return $this->showMessage('The verification email has been resent');
    }
}
