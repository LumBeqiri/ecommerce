<?php

namespace App\Http\Controllers\Variant;

use App\Http\Controllers\ApiController;
use App\Http\Resources\VariantResource;
use App\Models\Variant;

class VariantController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $variants = Variant::all();
        return $this->showAll(VariantResource::collection($variants));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Variant $variant)
    {
        return $this->showOne(new VariantResource($variant));
    }
}
