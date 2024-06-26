<?php

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\ApiController;
use App\Http\Requests\Media\MediasRequest;
use App\Http\Resources\MediaResource;
use App\Http\Resources\VariantResource;
use App\Models\Media;
use App\Models\Variant;
use App\Services\UploadImageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class AdminVariantMediaController extends ApiController
{
    public function index(Variant $variant): JsonResponse
    {
        $medias = $variant->medias;

        return $this->showAll(MediaResource::collection($medias));
    }

    public function store(MediasRequest $request, Variant $variant, UploadImageService $uploadImageService): JsonResponse
    {
        $medias = $request->file('medias');

        try {
            $uploadImageService->upload($variant, $medias, Variant::class);
        } catch (\Throwable $th) {
            return $this->showError('Something went wrong');
        }

        return $this->showOne(new VariantResource($variant));
    }

    public function destroy(Variant $variant, Media $media): JsonResponse
    {
        Storage::disk('images')->delete($media->path);

        Media::where('id', $media->id)
            ->where('mediable_id', $variant->id)
            ->where('mediable_type', $media->mediable_type)
            ->delete();

        return $this->showMessage('Image deleted successfully!');
    }
}
