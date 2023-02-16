<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\ApiController;
use App\Http\Resources\VariantResource;
use App\Models\Attribute;
use App\Models\Variant;
use Illuminate\Http\Request;

class SellerVariantAttributeController extends ApiController
{
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
            'product_attributes.*' => 'required|string|exists:attributes,uuid',
        ]);

        $attributes = Attribute::whereIn('uuid', $data['product_attributes'])->get();

        abort_if($this->duplicateAttribute($variant, $attributes), 422, 'Cannot have the same attribute type more than once');

        $variant->attributes()->sync($attributes->pluck('id'));

        return $this->showOne(new VariantResource($variant->load(['attributes'])));
    }

    public function show($variant)
    {
        $vr = Variant::select(['id', 'barcode', 'sku', 'stock', 'ean'])->where('uuid', $variant)->first();

        return $this->showOne($vr->load('attributes'));
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

    private function duplicateAttribute($variant, $attributes): bool
    {
        // in order to use duplicates(),
        // we transform our eloquent collection in a Illuminate/Support/Collection

        $attributes_collection = collect($attributes);
        $attributes_collection = $attributes_collection->duplicates('attribute_type');

        if (count($attributes_collection) !== 0) {
            return true;
        }

        foreach ($attributes as $attribute) {
            if ($variant->attributes()->where('attribute_type', $attribute['attribute_type'])->exists()) {
                return true;
            }
        }

        return false;
    }
}
