<?php

namespace App\Http\Controllers\Seller;

use App\Models\Media;
use App\Models\Variant;
use App\Http\Requests\MediasRequest;
use App\Services\UploadImageService;
use App\Http\Resources\MediaResource;
use App\Http\Controllers\ApiController;
use App\Http\Resources\VariantResource;
use Illuminate\Support\Facades\Storage;

class SellerVariantMediaController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Variant $variant)
    {
        $medias = $variant->medias;

        return $this->showAll(MediaResource::collection($medias));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MediasRequest $request, Variant $variant)
    {
        $medias = $request->file('medias');

        try {
            UploadImageService::upload($variant, $medias, Variant::class);
        } catch (\Throwable $th) {
            return $this->showError('Something went wrong');
        }

        return $this->showOne(new VariantResource($variant));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Variant $variant, Media $media)
    {
        Storage::disk('images')->delete($media->path);

        Media::where('id', $media->id)
            ->where('mediable_id', $variant->id)
            ->where('mediable_type', $media->mediable_type)
            ->delete();

        return $this->showMessage('Image deleted successfully!');
    }
}
