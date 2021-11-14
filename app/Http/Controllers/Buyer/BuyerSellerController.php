<?php

namespace App\Http\Controllers\Buyer;

use App\Models\Buyer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiController;

class BuyerSellerController extends ApiController
{
    public function index(Buyer $buyer){
        $sellers = $buyer->orders()->with('products.seller')->get()
        ->pluck('products')
        ->collapse()
        ->pluck('seller')
        ->unique('id')
        ->values();



        return $this->showAll($sellers);
    }
}
