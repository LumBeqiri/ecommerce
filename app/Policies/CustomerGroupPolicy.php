<?php

namespace App\Policies;

use App\Models\CustomerGroup;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class CustomerGroupPolicy
{
    use HandlesAuthorization;
    
    /**
     * Determine whether the user can view the model.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, CustomerGroup $customerGroup)
    {
        return $user->id === $customerGroup->user_id
        ? Response::allow()
        : Response::deny('You do not own this CustomerGroup!');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, CustomerGroup $customerGroup)
    {
        return $user->id === $customerGroup->user_id
        ? Response::allow()
        : Response::deny('You do not own this CustomerGroup!');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, CustomerGroup $customerGroup)
    {
        return $user->id === $customerGroup->user_id
        ? Response::allow()
        : Response::deny('You do not own this CustomerGroup!');
    }

}
