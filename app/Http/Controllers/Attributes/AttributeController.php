<?php

namespace App\Http\Controllers\Attributes;

use App\Models\Attribute;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\ApiController;
use App\Http\Requests\AttributeStoreRequest;

class AttributeController extends ApiController
{

    public function index() : JsonResponse
    {
        return $this->showAll(Attribute::all());
    }


    public function store(AttributeStoreRequest $request) : JsonResponse
    {
        $data = $request->validated();

        $attribute = Attribute::create($data);

        return $this->showOne($attribute);
    }


    public function show(Attribute $attribute) : JsonResponse
    {
        return $this->showOne($attribute);
    }


    public function update(AttributeStoreRequest $request, Attribute $attribute) : JsonResponse
    {
        $data = $request->validated();

        $attribute->update($data);

        return $this->showOne($attribute);
    }


    public function destroy(Attribute $attribute) : JsonResponse
    {
        $attribute->delete();

        return $this->showOne($attribute);
    }
}
