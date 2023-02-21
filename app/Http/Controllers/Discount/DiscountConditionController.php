<?php

namespace App\Http\Controllers\Discount;

use App\Models\Product;
use App\Models\Discount;
use App\Models\CustomerGroup;
use App\Models\DiscountCondition;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\ApiController;
use App\Http\Requests\DiscountConditionRequest;
use App\Http\Resources\DiscountConditionResource;
use App\Http\Requests\UpdateDiscountConditionRequest;

class DiscountConditionController extends ApiController
{

    public function index(Discount $discount) : JsonResponse
    {
        return $this->showAll(DiscountConditionResource::collection($discount->discount_rule()->discount_conditions()->get()));
    }

    public function store(DiscountConditionRequest $request, Discount $discount) : JsonResponse
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

    public function show(DiscountCondition $discount_condition) : JsonResponse
    {
        return $this->showOne(new DiscountConditionResource($discount_condition));
    }


    public function update(UpdateDiscountConditionRequest $request, DiscountCondition $discount_condition) : JsonResponse
    {
        if ($request->has('products')) {
            $products = Product::whereIn('uuid', $request->products)->pluck('id');
            $discount_condition->products()->syncWithoutDetaching($products);
        }
        if ($request->has('customer_group')) {
            $products = CustomerGroup::whereIn('uuid', $request->customer_group)->pluck('id');
            $discount_condition->customer_groups()->syncWithoutDetaching($products);
        }

        return $this->showOne(new DiscountConditionResource($discount_condition));
    }

    public function removeProduct(DiscountCondition $discount_condition, Product $product)
    {
        $discount_condition->products()->detach($product);

        return $this->showMessage('Product Removed Successfully!');
    }

    public function removeCustomerGroup(DiscountCondition $discount_condition, CustomerGroup $customerGroup)
    {
        $discount_condition->customer_groups()->detach($customerGroup);

        return $this->showMessage('Customer Group Removed Successfully!');
    }


    public function destroy(DiscountCondition $discountCondition) : JsonResponse
    {
        $discountCondition->delete();

        return $this->showMessage('Discount condition deleted Successfully!');
    }
}
