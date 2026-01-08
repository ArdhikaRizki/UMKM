<?php

namespace App\Livewire\Public;

use App\Models\Product;
use Livewire\Component;
use Livewire\Attributes\Computed;

class Katalog extends Component
{

    public $search = '';
    public $showCart = false;


    public function toggleCart(){
        $this->showCart = !$this->showCart;
    }

    public function addQuantity($id){
        $cart = session()->get('cart', []);
        if(isset($cart[$id])){
            $cart[$id]['quantity']++;
            session()->put('cart', $cart);
            unset($this->totalCart);
        }
    }

    public function decreaseQuantity($id){
        $cart = session()->get('cart', []);
        if(isset($cart[$id])){
            if($cart[$id]['quantity'] > 1){

                $cart[$id]['quantity']--;
            } else{
                unset($cart[$id]);
            }
                session()->put('cart', $cart);
                unset($this->totalCart);
        }

    }

    public function removeFromCart($id){
        $cart = session()->get('cart', []);
        if(isset($cart[$id])){
            unset($cart[$id]);
            session()->put('cart', $cart);
            unset($this->totalCart);
        }
    }
    #[Computed]
    public function totalPrice(){
        $cart = session()->get('cart', []);
        $total = 0;
        foreach ($cart as $item) {
            $total =+ $item['quantity'] * $item['price'];
        }
        return $total;
    }

    public function checkout(){
        $cart = session()->get('cart', []);
        
        if(empty($cart))
            return;
        
        $adminPhone = '628993106053';
        $message = "Halo Min!! Saya Mau pesan barang Dari warung : \n\n";
        
        foreach ($cart as $item) {
            $subtotal = $item['quantity'] *$item['price'];
            $message .= "- {$item['name']} ({$item['quantity']}x) : RP " . number_format($subtotal, 0,',','.') . "\n";

        }
        $message .= "\nTotal Bayar: RP " . number_format($this->totalPrice, 0 , ',', '.') . "\n";
        $message .= "\n\nMohon Di Proses Min!!!"; 

        $encodedMessage = urlencode($message);
        $waLink = "https://wa.me/{$adminPhone}?text={$encodedMessage}";

        return redirect()->to($waLink);
    }

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

        // dd(session()->get('cart'));
        unset($this->totalCart);



    }
    #[Computed]
    public function totalCart()
    {
        $cart = session()->get('cart', []);
        return array_sum(array_column($cart, 'quantity'));
    }

    public function render()
     {
        $products = Product::query()->when($this->search, function($query){
            $query->where('name', 'like', '%'. $this->search .'%');

        })->latest()->get();

        return view('livewire.public.katalog', [
            'products' => $products 
        ]);
    }
}
