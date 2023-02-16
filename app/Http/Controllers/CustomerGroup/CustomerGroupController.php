<?php

namespace App\Http\Controllers\CustomerGroup;

use App\Http\Controllers\ApiController;
use App\Http\Requests\CustomerGroupRequest;
use App\Http\Resources\CustomerGroupResource;
use App\Models\CustomerGroup;
use App\Models\User;
use App\Services\CustomerGroupService;
use Illuminate\Support\Arr;

class CustomerGroupController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customerGroups = CustomerGroup::where('user_id', auth()->id())->get();

        return $this->showAll(CustomerGroupResource::collection($customerGroups));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CustomerGroupRequest $request, CustomerGroupService $customerGroupService)
    {
        $data = $request->validated();

        $name_availabilty = CustomerGroup::where('name', $data['name'])
            ->where('user_id', auth()->id())
            ->get();

        if (count($name_availabilty)) {
            return $this->showError('Name '.$data['name'].' is already taken!', 422);
        }

        $customerGroup = $customerGroupService->createCustomerGroup($data['name'], Arr::get('metadata', ''));

        $users = User::whereIn('uuid', $data['users'])->get();

        $customerGroup->users()->attach($users);

        return $this->showOne(new CustomerGroupResource($customerGroup));
    }

    /**
     * Display the specified resource.
     *
     * @param  CustomerGroup  $customerGroup
     * @return \Illuminate\Http\Response
     */
    public function show(CustomerGroup $customerGroup)
    {
        $this->authorize('view', $customerGroup);

        return $this->showOne(new CustomerGroupResource($customerGroup));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CustomerGroupRequest $request, CustomerGroup $customerGroup)
    {
        $this->authorize('update', $customerGroup);

        $request->validated();
        $customerGroup->fill($request->validated);
        $customerGroup->save();

        return $this->showOne(new CustomerGroupResource($customerGroup));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(CustomerGroup $customerGroup)
    {
        $this->authorize('delete', $customerGroup);

        $customerGroup->delete();

        return $this->showMessage('Customer group deleted successfully!');
    }
}
