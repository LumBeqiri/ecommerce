<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\ApiController;
use App\Http\Requests\Media\MediaRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\JsonResponse;

class ProductThumbnailController extends ApiController
{
    public function store(MediaRequest $request, Product $product): JsonResponse
    {
        $request->validated();

        $this->authorize('update', $product);

        if ($request->hasFile('thumbnail')) {
            $product->addMediaFromRequest('thumbnail')
                ->toMediaCollection('thumbnails');
        }

        return $this->showOne(new ProductResource($product));
    }

    public function destroy(Product $product): JsonResponse
    {
        $this->authorize('delete', $product);

        $product->clearMediaCollection('thumbnails');

        return $this->showMessage('Thumbnail Removed Successfully');
    }
}
