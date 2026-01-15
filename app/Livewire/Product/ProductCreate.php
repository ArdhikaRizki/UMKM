<?php

namespace App\Livewire\Product;

use Livewire\Component;
use App\Models\Product;
use App\Models\StockPurchase;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;

class ProductCreate extends Component
{
    use WithFileUploads;

    public $name;
    public $category;
    public $price;
    public $stock = 0;
    public $purchasePrice;
    public $image;
    public $isOpen = false;

    public function save()
    {
        $validationRules = [
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|max:2048',
        ];

        // Jika stock > 0, harga beli wajib diisi
        if ($this->stock > 0) {
            $validationRules['purchasePrice'] = 'required|numeric|min:0';
        }

        $this->validate($validationRules);

        $data = [
            'name' => $this->name,
            'category' => $this->category,
            'price' => $this->price,
            'stock' => $this->stock,
        ];

        // Handle upload gambar jika ada
        if ($this->image) {
            $imagePath = $this->image->store('products', 'public');
            $data['image'] = $imagePath;
        }

        $product = Product::create($data);

        // Jika ada stock awal, catat sebagai pembelian stock pertama
        if ($this->stock > 0 && $this->purchasePrice) {
            StockPurchase::create([
                'product_id' => $product->id,
                'quantity' => $this->stock,
                'purchase_price' => $this->purchasePrice,
                'total_cost' => $this->stock * $this->purchasePrice,
                'user_id' => Auth::id(),
                'notes' => 'Stock awal produk baru',
            ]);
        }

        $this->reset(['name', 'category', 'price', 'stock', 'purchasePrice', 'image']);
        $this->isOpen = false;

        $this->dispatch('refresh-table'); 
        session()->flash('success', 'Produk berhasil ditambahkan!');
    }

    public function removeImage()
    {
        $this->image = null;
    }
    public function render()
    {
        return view('livewire.product.product-create');
    }
}
