<?php

namespace App\Http\Controllers\User\Products;

use App\Http\Controllers\ApiController;
use App\Models\Product;
use Illuminate\Http\JsonResponse;

class AdminProductThumbnailController extends ApiController
{
    public function destroy(Product $product): JsonResponse
    {
        $product->clearMediaCollection('thumbnails');

        return $this->showMessage('Thumbnail Removed Successfully');
    }
}
