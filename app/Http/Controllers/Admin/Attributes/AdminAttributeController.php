<?php

namespace App\Http\Controllers\Admin\Attributes;

use App\Http\Controllers\ApiController;
use App\Http\Requests\AttributeStoreRequest;
use App\Models\Attribute;
use App\Models\Product;
use Illuminate\Http\JsonResponse;

class AdminAttributeController extends ApiController
{
    public function index(): JsonResponse
    {
        return $this->showAll(Attribute::all());
    }

    public function store(AttributeStoreRequest $request): JsonResponse
    {
        $data = $request->validated();

        $data['product_id'] = Product::where('uuid', $data['product_id'])->first()->id;
        $attribute = Attribute::create($data);

        return $this->showOne($attribute, 201);
    }

    public function show(Attribute $attribute): JsonResponse
    {
        return $this->showOne($attribute);
    }

    public function update(AttributeStoreRequest $request, Attribute $attribute): JsonResponse
    {
        $data = $request->validated();

        $data['product_id'] = Product::where('uuid', $data['product_id'])->first()->id;
        $attribute->update($data);

        return $this->showOne($attribute);
    }

    public function destroy(Attribute $attribute): JsonResponse
    {
        $attribute->delete();

        return $this->showOne($attribute);
    }
}
