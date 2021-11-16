<?php

namespace App\Models;


class Cart
{
    public $items=null;
    public $totalQty = 0;
    public $totalPrice =0;
    public $products = null;

    public function __construct($oldCart){
        if($oldCart){
            $this->items = $oldCart->items;
            $this->totalQty = $oldCart->totalQty;
            $this->totalPrice = $oldCart->totalPrice;
            $this->products = $oldCart->products;
        }
    }

    public function addProductId($id){
        $this->products[] = $id;
        $this->products = array_unique($this->products);
    }

    public function add($item, $id){
        $storedItem = ['item' => $item, 'qty' => 0, 'price' => $item->price];
        //check if user has items
        if($this->items){
            //if item exists on the cart already, then do nothing
            if(array_key_exists($id,$this->items)){
                $storedItem = $this->items[$id];
            }
        }
        $this->addProductId($id);

        $storedItem['qty']++;
        $storedItem['price'] = $item->price * $storedItem['qty']; 
        $this->items[$id] = $storedItem;
        $this->totalQty++;
        $this->totalPrice+= $item->price;
    }

    public function remove($item, $id){
        //check if user has items
        if($this->items){

            if(array_key_exists($id,$this->items)){
                $temp = $this->items[$id];
                if($temp['qty'] == 1){
                    $this->totalQty--;
                    $this->totalPrice-=$item->price;
                    unset($this->items[$id]);
                    if (($key = array_search($id, $this->products)) !== false) {
                        unset($this->products[$key]);
                    }
                }
                else{
                    $this->items[$id]['qty']--;
                    $this->items[$id]['price'] = $item->price * $this->items[$id]['qty']; 
                    $this->totalQty--;
                    $this->totalPrice-=$item->price;
                }
            }
        }
    }
}
