<?php

namespace App\Http\Controllers\Seller;

use App\Models\Variant;
use App\Models\Attribute;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

use function PHPUnit\Framework\isEmpty;

class SellerVariantAttributeController extends ApiController
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
    public function store(Request $request, Variant $variant)
    {
        $data = $request->validate([
            'product_attributes' => 'array',
            'product_attributes.*' => 'required|string|exists:attributes,uuid'
        ]);

        $attributes = Attribute::whereIn('uuid', $data['product_attributes'])->get();

        abort_if($this->duplicateAttribute($attributes), 422, 'Cannot have the same attribute type more than once');

        foreach($attributes as $attribute){
            $variant->attributes()->attach($attribute);
        }
        
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
    public function destroy(Variant $variant, Attribute $attribute)
    {
        $variant->attributes()->detach($attribute);

        return $this->showMessage('Attribute removed successfully!');
    }

    private function duplicateAttribute($attributes) : bool
    {
        $attributes = collect($attributes);
        $attributes = $attributes->duplicates('attribute_type');

        return count($attributes) !== 0;
    }
}


