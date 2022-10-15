<?php

namespace App\Http\Controllers\Product;

use App\Models\{Product,Cart};
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Controllers\ApiController;


class ProductController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::all();
        return $this->showAll($products);
    }



    /**
     * Display the specified resource.
     *
     * @param  Product $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product){
       $product = QueryBuilder::for(Product::class)
            ->allowedIncludes(['variants', 'seller'])
            ->where('uuid', $product->uuid)
            ->first();
        return ($product);
    }


}
