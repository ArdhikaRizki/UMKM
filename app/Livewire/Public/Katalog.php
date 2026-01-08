<?php

namespace App\Livewire\Public;

use App\Models\Product;
use Livewire\Component;

class Katalog extends Component
{

    public $search = '';

    public function addToCart($productID){
        $product = Product::find($productID);

        if(!$product)
            return;

        $cart =  session()->get('cart', []);

        if(isset($cart[$productID])){
            $cart[$productID]['quantity']++;
        }else {
            $cart[$productID] = [
                'name' => $product->name,
                // 'category' => $product->category,
                'price' => $product->price,
                'quantity' => 1,
                'image' => $product->image,

            ];
        }

        session()->put('cart', $cart);

        dd(session()->get('cart'));



    }
    public function render()
    {
        $cart =  session()->get('cart', []);
        $amountProducts = array_sum(array_column($cart, 'quantity'));

        
        $products = Product::query()->when($this->search, function($query){
            $query->where('name', 'like', '%'. $this->search .'%');

        })->latest()->get();

        return view('livewire.public.katalog', [
            'products' => $products,
            'totalCart' => $amountProducts
        ]);
    }
}
