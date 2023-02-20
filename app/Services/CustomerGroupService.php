<?php

namespace App\Services;

use App\Models\CustomerGroup;

class CustomerGroupService
{
    /**
     * @param  mixed  $name
     * @param  mixed  $metadata
     * @return CustomerGroup
     */
    public function createCustomerGroup($name, $metadata = null)
    {
        return CustomerGroup::create([
            'name' => $name,
            'metadata' => $metadata,
            'user_id' => auth()->id(),
        ]);
    }

    // /**
    //  * @param  mixed  $users
    //  * @param  App\Models\CustomerGroup  $custom_group
    //  * @return [type]
    //  */
    // public function addUsersToCustomGroup($users, $custom_group)
    // {
    // }
}
