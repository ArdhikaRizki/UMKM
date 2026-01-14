<?php

namespace App\Livewire\Product;

use App\Models\Product;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class ProductEdit extends Component
{
    use WithFileUploads;
    
    public $productId;
    public $name;
    public $category;
    public $price;
    public $image;
    public $oldImage;
    public $isOpen = false;

    #[On('edit-mode')]
    public function loadData($id)
    {
        $product = Product::find($id);
        $this->productId = $product->id;
        $this->name = $product->name;
        $this->category = $product->category;
        $this->price = $product->price;
        $this->oldImage = $product->image;
        $this->isOpen = true;
    }

    public function update()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($this->productId) {
            $product = Product::find($this->productId);
            
            $updateData = [
                'name' => $this->name,
                'category' => $this->category,
                'price' => $this->price,
            ];

            // Handle upload gambar baru
            if ($this->image) {
                // Hapus gambar lama jika ada
                if ($product->image && Storage::disk('public')->exists($product->image)) {
                    Storage::disk('public')->delete($product->image);
                }

                // Simpan gambar baru
                $imagePath = $this->image->store('products', 'public');
                $updateData['image'] = $imagePath;
            }

            $product->update($updateData);

            session()->flash('message', 'Produk berhasil diupdate!');
        }

        $this->isOpen = false;
        $this->reset(['name', 'category', 'price', 'productId', 'image', 'oldImage']);
        
        $this->dispatch('refresh-table');
    }

    public function removeImage()
    {
        $this->image = null;
    }

    public function render()
    {
        return view('livewire.product.product-edit');
    }
}
