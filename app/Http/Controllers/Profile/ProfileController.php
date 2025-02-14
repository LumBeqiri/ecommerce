<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\ApiController;
use App\Http\Requests\Profile\UpdateProfileRequest;
use Illuminate\Http\JsonResponse;

class ProfileController extends ApiController
{
 
    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProfileRequest $request) : JsonResponse
    {
        $user = $this->authUser();

        $user->update([
            'first_name' => $request->input('first_name'),
            'last_name'  => $request->input('last_name'),
            'email'      => $request->input('email'),
        ]);

        if ($user->vendor) {
            $vendorData = $request->only([
                'vendor_name',
                'city',
                'country_id',
                'status',
                'approval_date',
                'website'
            ]);

            $user->vendor->update($vendorData);
        } elseif ($user->staff) {
            $staffData = $request->only([
                'position',
                'status',
                'notes',
                'address',
                'vendor_id',
                'start_date',
                'end_date'
            ]);

            $user->staff->update($staffData);
        }

        return response()->json([
            'message' => 'Profile updated successfully',
            'user'    => $user->load('vendor', 'staff'),
        ]);
    }


}
