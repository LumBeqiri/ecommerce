<?php

namespace App\Http\Controllers\Discount;

use App\Http\Controllers\ApiController;
use App\Http\Requests\DiscountConditionRequest;
use App\Models\Product;
use App\Models\Discount;
use App\Models\DiscountCondition;
use App\Http\Requests\UpdateDiscountConditionRequest;
use App\Http\Resources\DiscountConditionResource;

class DiscountConditionController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Discount $discount)
    {
        return $this->showAll(DiscountConditionResource::collection($discount->discount_rule()->discount_conditions()->get()));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DiscountConditionRequest $request, Discount $discount)
    {
        $products = Product::whereIn('uuid', $request->products)->pluck('id'); 
        $discount->discount_rule()->discount_condition()->products()->attach($products);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(DiscountCondition $discount_condition)
    {
       return $this->showOne(new DiscountConditionResource($discount_condition));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateDiscountConditionRequest $request, DiscountCondition $discount_condition)
    {
        $products = Product::whereIn('uuid', $request->products)->pluck('id'); 
        $discount_condition->products()->syncWithoutDetaching($products);   

        return $this->showOne(new DiscountConditionResource($discount_condition));
    }

    public function removeProduct( DiscountCondition $discount_condition, Product $product){
        $discount_condition->products()->detach($product);

        return $this->showMessage('Product Removed Successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(DiscountCondition $discountCondition)
    {
        $discountCondition->delete();

        return $this->showMessage('Discount condition deleted Successfully!');
    }
}
