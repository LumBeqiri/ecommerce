<?php

namespace App\Http\Controllers\Vendor;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;
use App\Http\Requests\Permission\UpdateUserPermission;
use Spatie\Permission\Models\Permission;
use App\Http\Resources\PermissionResource;
use App\Http\Resources\UserResource;
use Laravel\Pulse\Recorders\UserRequests;

class VendorPermissionManagerController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->showAll(PermissionResource::collection(Permission::paginate(10)));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserPermission $request, User $user)
    {
        $validatedData = $request->validated();
        
        $permissionIds = $validatedData['permissions'];

        $user->permissions()->sync($permissionIds);
        $user->save();

        return new UserResource($user);
    }


    public function destroy(User $user, Permission $permission)
    {
        $user->permissions()->detach($permission);
    
        return $this->showMessage(['Permission removed successfully']);
    }
}
