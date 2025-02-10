<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\ApiController;
use App\Http\Requests\Permission\UpdateUserPermissionRequest;
use App\Http\Resources\PermissionResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Spatie\Permission\Models\Permission;

class VendorPermissionManagerController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        return $this->showAll(PermissionResource::collection(Permission::paginate(10)));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserPermissionRequest $request, User $user): JsonResource
    {
        $vendor = Vendor::where('user_id', auth()->id())->firstOrFail();

        abort_if(! $vendor->staff()->where('user_id', $user->id)->exists(), 422, 'Cannot update this staff');

        $validatedData = $request->validated();

        $permissionIds = $validatedData['permissions'];

        $user->permissions()->sync($permissionIds);
        $user->save();

        return new UserResource($user);
    }

    public function destroy(User $user, int $permission_id): JsonResponse
    {

        $permission = Permission::findOrFail($permission_id);

        $vendor = Vendor::where('user_id', auth()->user()->id)->firstOrFail();

        abort_if(! $vendor->staff()->where('user_id', $user->id)->exists(), 422, 'Cannot remove permission for this staff');

        $user->permissions()->detach($permission->id);

        return $this->showMessage(['Permission removed successfully']);
    }
}
