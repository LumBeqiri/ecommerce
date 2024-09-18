<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

trait HasUlids
{
    public static function bootHasUlid(): void
    {
        static::creating(function (Model $model): void {
            /** @phpstan-ignore-next-line */
            if (! $model->ulid) {
                $model->ulid = Str::ulid();
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'ulid';
    }
}
