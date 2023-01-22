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

class CustomerGroupController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
