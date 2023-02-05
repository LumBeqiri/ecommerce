<?php

namespace App\Http\Controllers\Discount;

use App\Http\Controllers\ApiController;
use App\Http\Requests\DiscountRequest;
use App\Http\Resources\DiscountResource;
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
        $request->validated();

        $discountRule = DiscountRule::create(
            ['value' => $request->percentage ?? $request->amount] 
            +
            $request->only([
                'description',
                'discount_type',
                'allocation',
                'metadata',
            ])
        );

        $code_availabilty = Discount::where('code', $request->code)
            ->where('seller_id', auth()->id())
            ->get();

        if($code_availabilty){
            return $this->showError('Name ' . $request->code . ' is already taken!', 422);
        }

        $discount = $discountRule->discount()->create(
            $request->only([
                'code',
                'is_dynamic',
                'is_disabled',
                'parent_id',
                'ends_at',
                'usage_limit',
                'usage_count',
                ])
        );

        $discount->starts_at = now();

        return $this->showOne(new DiscountResource($discount->load('discount_rule')));

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
