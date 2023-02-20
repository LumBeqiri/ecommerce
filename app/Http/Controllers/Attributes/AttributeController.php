<?php

namespace App\Http\Controllers\Attributes;

use App\Http\Controllers\ApiController;
use App\Http\Requests\AttributeStoreRequest;
use App\Models\Attribute;

class AttributeController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return $this->showAll(Attribute::all());
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(AttributeStoreRequest $request)
    {
        $data = $request->validated();

        $attribute = Attribute::create($data);

        return $this->showOne($attribute);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Attribute $attribute)
    {
        return $this->showOne($attribute);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(AttributeStoreRequest $request, Attribute $attribute)
    {
        $data = $request->validated();

        $attribute->update($data);

        return $this->showOne($attribute);
    }

    /**
     * Remove the specified resource from storage.
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Attribute $attribute)
    {
        $attribute->delete();

        return $this->showOne($attribute);
    }
}
