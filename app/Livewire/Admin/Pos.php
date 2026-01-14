<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\DB;

class Pos extends Component
{
    public $search = '';
    public $cart = [];
    public $pay = 0;

    public function getProductsProperty(){
        return Product::query()
        ->when($this->search, function($q){
            $q->where('name', 'like', '%' . $this->search . '%');
        })
        ->latest()->get();
    }

    public function getTotalProperty(){
        $total = 0;
            foreach ($this->cart as $item) {
                $total += $item['price'] * $item['quantity'];
            }
        return $total;
    }

    public function getChangeProperty(){
        return max(0, (int)$this->pay - $this->total );
    }

    public function addToCart($id){
        $product = Product::find($id);
        
        if(!$product)
            return;

        if(isset($this->cart[$id])){
            $this->cart[$id]['quantity']++;  
        } else{
            $this->cart[$id] = [
                'id' => $product->id,
                'name' => $product->name,
                'image' => $product->image,
                'quantity' => 1
            ];
        }

    }

    public function decreaseItem($id){
        if(isset($this->cart[$id])){
            if($this->cart[$id]['quantity'] > 1){

                $this->cart[$id]['quantity']--;
            } else{
                unset($this->cart[$id]);
            }
        }
    }
    public function increaseItem($id){
        if(isset($this->cart[$id])){

            $this->cart[$id]['quantity']++;
        }
    }

    public function removeFromCart($id){
        if(isset($this->cart[$id])){
            unset($this->cart[$id]);
        }
    }
    
    public function saveTransaction(){
        if(empty($this->cart))
            return ;


        if($this->pay < $this->total){
            session()->flash('error', 'uang kurang !!');
            return;
        }

        DB::transaction(function () {
            $transaction = Transaction::create([
                'invoice_no' => 'INV-'. time(),
                'user_id' => auth()->user()->id,
                'customer_name' => 'umum',
                'total_amount' => $this->total,
                'pay_amount' => $this->pay,
                'change' => $this->change,
                'status' =>'done',
            ]);
            foreach($this->cart as $item){
                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $item['id'],
                    'qty' => $item['quantity'],
                    'subtotal' => $item['price'] * $item['quantity']
                ]);
            }
        });
        $this->cart = [];
        $this->pay = 0;
        $this->search = '';
        unset($this->total);
        session()->flash('success', 'Transaksi Berhasil!');
    }

    #[Layout('components.layouts.admin')]
    public function render()
    {   
        return view('livewire.admin.pos',
        [
            'products' =>Product::all(),
        ]);
    }
}
