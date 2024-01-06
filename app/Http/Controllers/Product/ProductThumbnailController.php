<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\ApiController;
use App\Http\Requests\Media\MediaRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class ProductThumbnailController extends ApiController
{
    public function store(MediaRequest $request, Product $product): JsonResponse
    {
        $request->validated();

        $this->authorize('update', $product);

        $thumbnail = $request->thumbnail;

        if ($product->thumbnail) {
            Storage::disk('images')->delete($product->thumbnail);
        }

        $upload = $thumbnail->store('', 'images');

        $product->thumbnail = $upload;

        $product->save();

        return $this->showOne(new ProductResource($product));
    }

    public function destroy(Product $product): JsonResponse
    {
        $this->authorize('delete', $product);

        $thumbnail = $product->thumbnail;

        if ($thumbnail === null) {
            return $this->showMessage('This product has no thumbnail!');
        }

        try {
            Storage::disk('images')->delete($thumbnail);
        } catch (Exception $e) {
        }

        $product->thumbnail = null;

        $product->save();

        return $this->showMessage('Thumbnail Removed Successfully');
    }
}
