<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\ApiController;
use App\Models\Buyer;


class BuyerProductController extends ApiController
{
    /**
     * @param Buyer $buyer
     * 
     * @return  \Illuminate\Http\Response
     */
    public function index(Buyer $buyer){
        $products = $buyer->orders()->with('products')
        ->get()
        ->pluck('products')
        ->collapse();

        return $this->showAll($products);
    }
}
