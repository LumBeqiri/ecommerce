<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductOrderController extends ApiController
{
    public function index(Product $product){
        $orders = $product->orders;

        return $this->showAll($orders);
    }
}
