<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Vendor */
class VendorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return array_merge(
            [
                'id' => $this->ulid,
                'vendor_name' => $this->vendor_name,
                'city' => $this->city,
                'country' => new CountryResource($this->country),
                'website' => $this->website,
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
                'deleted_at' => $this->deleted_at,
            ],
            $this->protectedData()
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function protectedData(): array
    {
        /** @var User|null $user */
        $user = auth()->user();

        // Check if the user is logged in and if they match the required conditions
        if ($user && ($user->id == $this->user_id || $user->hasRole('admin'))) {
            return [
                'user_id' => $this->user_id,
                'status' => $this->status,
                'approval_date' => $this->approval_date,
            ];
        }

        return []; // Return an empty array if the conditions are not met or the user is not logged in
    }
}
