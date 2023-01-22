<?php

namespace App\Http\Controllers\CustomerGroup;

use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\CustomerGroupService;
use App\Http\Controllers\ApiController;
use App\Http\Requests\CustomerGroupRequest;
use App\Services\CreateCustomerGroupService;
use App\Http\Resources\CustomerGroupResource;
use App\Models\CustomerGroup;

class CustomerGroupController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customer_groups = CustomerGroup::where('user_id', auth()->id())->get();

        return $this->showAll(CustomerGroupResource::collection($customer_groups));
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

        if(count($name_availabilty)){
            return $this->showError('Name ' . $data['name'] . ' is already taken!');
        }

        $customerGroup = $customerGroupService->createCustomerGroup($data['name'], Arr::get('metadata', ''));

        return $this->showOne(new CustomerGroupResource($customerGroup));

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
