<?php

namespace App\Http\Controllers\Discount;

use App\Models\Product;
use App\Models\Discount;
use App\Models\CustomerGroup;
use App\Models\DiscountCondition;
use App\Http\Controllers\ApiController;
use App\Http\Requests\DiscountConditionRequest;
use App\Http\Resources\DiscountConditionResource;
use App\Http\Requests\UpdateDiscountConditionRequest;

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
        $discountCondition = $discount->discount_rule->discount_conditions()->create([
            'model_type' => $request->model_type,
            'operator' => $request->operator,
            'metadata' => $request->metadata ?? null,
        ]);

        if ($request->has('products')) {
            $discountCondition->model_type = $request->model_type;
            $products = Product::whereIn('uuid', $request->products)->pluck('id');
            $discountCondition->products()->attach($products);
        }
        if ($request->has('customer_group')) {
            $products = Product::whereIn('uuid', $request->products)->pluck('id');
            $discountCondition->products()->attach($products);
        }

        return $this->showOne(new DiscountConditionResource($discountCondition->load('products', 'customer_groups')));
        

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
        if($request->has('products')){
            $products = Product::whereIn('uuid', $request->products)->pluck('id'); 
            $discount_condition->products()->syncWithoutDetaching($products);   
        }
        if($request->has('customer_group')){
            $products = CustomerGroup::whereIn('uuid', $request->customer_group)->pluck('id'); 
            $discount_condition->customer_groups()->syncWithoutDetaching($products);   
        }


        return $this->showOne(new DiscountConditionResource($discount_condition));
    }

    public function removeProduct( DiscountCondition $discount_condition, Product $product){
        $discount_condition->products()->detach($product);

        return $this->showMessage('Product Removed Successfully!');
    }

    public function removeCustomerGroup( DiscountCondition $discount_condition, CustomerGroup $customerGroup){
        $discount_condition->customer_groups()->detach($customerGroup);

        return $this->showMessage('Customer Group Removed Successfully!');
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
