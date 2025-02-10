<?php

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\ApiController;
use App\Http\Requests\Media\MediasRequest;
use App\Http\Resources\MediaResource;
use App\Http\Resources\VariantResource;
use App\Models\Variant;
use Exception;
use Illuminate\Http\JsonResponse;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class AdminVariantMediaController extends ApiController
{
    public function index(Variant $variant): JsonResponse
    {
        $medias = $variant->media;

        return $this->showAll(MediaResource::collection($medias));
    }

    public function store(MediasRequest $request, Variant $variant): JsonResponse
    {
        /** @var array<int, \Illuminate\Http\UploadedFile> $medias */
        $medias = $request->file('files');

        try {
            foreach ($medias as $media) {
                $variant->addMedia($media)
                    ->toMediaCollection('variants');
            }
        } catch (Exception $ex) {
            return $this->showError($ex->getMessage());
        }

        return $this->showOne(new VariantResource($variant));
    }

    public function destroy(Variant $variant, string $media_uuid): JsonResponse
    {
        $media = Media::where('uuid', $media_uuid)->firstOrFail();
        if ($variant->id !== $media->model_id) {
            return $this->showError('This media does not belong to the specified variant.');
        }

        try {
            $media->delete();
        } catch (Exception $ex) {
            return $this->showError($ex->getMessage(), $ex->getCode());
        }

        return $this->showMessage('Image deleted successfully!');
    }
}
