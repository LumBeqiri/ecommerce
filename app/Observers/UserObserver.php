<?php

namespace App\Observers;

use App\Mail\UserCreated;
use App\Mail\UserMailChanged;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class UserObserver
{
    /**
     * Handle the User "created" event.
     *
     * @return void
     */
    public function created(User $user)
    {
        retry(5, function () use ($user) {
            Mail::to($user)->send(new UserCreated($user));
        }, 100);
    }

    /**
     * Handle the User "updated" event.
     *
     * @return void
     */
    public function updated(User $user)
    {
        if ($user->isDirty('email')) {
            retry(5, function () use ($user) {
                Mail::to($user)->send(new UserMailChanged($user));
            }, 100);
        }
    }
}
