<?php

namespace App\Http\Controllers\Attributes;

use App\Models\Attribute;
use App\Http\Controllers\ApiController;
use App\Http\Requests\AttributeStoreRequest;

class AttributeController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->showAll(Attribute::all());
    }

    /**
     * @param AttributeStoreRequest $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function store(AttributeStoreRequest $request)
    {
        $data = $request->validated();

        $attribute = Attribute::create($data);

        return $this->showOne($attribute);

    }


    /**
     * @param Attribute $attribute
     * 
     * @return \Illuminate\Http\Response
     */
    public function show(Attribute $attribute)
    {
        return $this->showOne($attribute);
    }


    /**
     * @param AttributeStoreRequest $request
     * @param Attribute $attribute
     * 
     * @return \Illuminate\Http\Response
     */
    public function update(AttributeStoreRequest $request, Attribute $attribute)
    {
        $data = $request->validated();

        $attribute->update($data);

        return $this->showOne($attribute);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Attribute $attribute
     * @return \Illuminate\Http\Response
     */
    public function destroy(Attribute $attribute)
    {
        $attribute->delete();

        return $this->showOne($attribute);
    }
}
