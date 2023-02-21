<?php

namespace App\Http\Controllers\CustomerGroup;

use App\Http\Controllers\ApiController;
use App\Http\Requests\CustomerGroupRequest;
use App\Http\Resources\CustomerGroupResource;
use App\Models\CustomerGroup;
use App\Models\User;
use App\Services\CustomerGroupService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;

class CustomerGroupController extends ApiController
{
    public function index(): JsonResponse
    {
        $customerGroups = CustomerGroup::where('user_id', auth()->id())->get();

        return $this->showAll(CustomerGroupResource::collection($customerGroups));
    }

    public function store(CustomerGroupRequest $request, CustomerGroupService $customerGroupService): JsonResponse
    {
        $data = $request->validated();

        $name_availabilty = CustomerGroup::where('name', $data['name'])
            ->where('user_id', auth()->id())
            ->get();

        if (count($name_availabilty)) {
            return $this->showError('Name '.$data['name'].' is already taken!', 422);
        }

        $customerGroup = $customerGroupService->createCustomerGroup($data['name'], Arr::get($data, 'metadata', ''));

        $users = User::whereIn('uuid', $data['users'])->get();

        $customerGroup->users()->attach($users);

        return $this->showOne(new CustomerGroupResource($customerGroup));
    }

    public function show(CustomerGroup $customerGroup): JsonResponse
    {
        $this->authorize('view', $customerGroup);

        return $this->showOne(new CustomerGroupResource($customerGroup));
    }

    public function update(CustomerGroupRequest $request, CustomerGroup $customerGroup): JsonResponse
    {
        $this->authorize('update', $customerGroup);

        $request->validated();
        $customerGroup->fill($request->validated);
        $customerGroup->save();

        return $this->showOne(new CustomerGroupResource($customerGroup));
    }

    public function destroy(CustomerGroup $customerGroup): JsonResponse
    {
        $this->authorize('delete', $customerGroup);

        $customerGroup->delete();

        return $this->showMessage('Customer group deleted successfully!');
    }
}
