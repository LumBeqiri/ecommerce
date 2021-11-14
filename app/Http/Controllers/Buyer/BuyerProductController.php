<?php

namespace App\Http\Controllers\Buyer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;
use App\Models\Buyer;
use App\Models\Seller;

class BuyerProductController extends ApiController
{
    public function index(Buyer $buyer){
        $products = $buyer->orders()->with('products')
        ->get()
        ->pluck('products')
        ->collapse();

        return $this->showAll($products);
    }
}
