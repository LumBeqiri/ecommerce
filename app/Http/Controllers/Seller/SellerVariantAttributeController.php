<?php

namespace App\Http\Controllers\Seller;

use App\Models\Variant;
use App\Models\Attribute;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\ApiController;
use App\Http\Resources\VariantResource;
use Illuminate\Database\Eloquent\Collection;

class SellerVariantAttributeController extends ApiController
{

    public function store(Request $request, Variant $variant) : JsonResponse
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

    public function show(Variant $variant) : JsonResponse
    {
        return $this->showOne($variant->load('attributes'));
    }

    public function destroy(Variant $variant, Attribute $attribute) : JsonResponse
    {
        $variant->attributes()->detach($attribute);

        return $this->showMessage('Attribute removed successfully!');
    }

    private function duplicateAttribute(Variant $variant, Collection $attributes): bool
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
