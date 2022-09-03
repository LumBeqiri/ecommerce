<?php

namespace App\Scopes;

use App\Models\Seller;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Builder;

class SellerScope implements Scope{
       
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder<Seller>  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     */
    public function apply(Builder $builder, Model $model){
        $builder->has('products');
        
    }
}