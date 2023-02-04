<?php

namespace App\Http\Controllers\Discount;

use App\Http\Controllers\ApiController;
use App\Http\Requests\DiscountRequest;
use App\Models\Discount;
use App\Models\DiscountRule;
use Illuminate\Http\Request;

class DiscountController extends ApiController
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
    public function store(DiscountRequest $request)
    {
        $data = $request->validated();

        $discountRule = DiscountRule::create(
            ['value' => $request->percentage ?? $request->amount] 
            +
            $request->only([
            'description',
            'discount_type',
            'allocation',
            'metadata'
            ])
        );

        return $discountRule;
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
