<?php

namespace App\Traits;

use Ramsey\Uuid\Uuid;
use Illuminate\Database\Eloquent\Model;


trait HasUuid
{
    public static function bootHasUuid(): void
    {
        static::creating(function (Model $model): void {
            /** @phpstan-ignore-next-line */
            $model->uuid = Uuid::uuid4()->toString();
        });
    }

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }
}
