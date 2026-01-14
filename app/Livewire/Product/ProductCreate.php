<?php

namespace App\Livewire\Product;

use Livewire\Component;
use App\Models\Product;

class ProductCreate extends Component
{

    public $name;
    public $isOpen = false; // Buat buka/tutup modal

    public function save()
    {
        $this->validate(['name' => 'required']);

        Product::create(['name' => $this->name]);

        $this->reset(); // Kosongin form
        $this->isOpen = false; // Tutup modal

        $this->dispatch('refresh-table'); 
        session()->flash('success', 'Berhasil nambah data!');
    }
    public function render()
    {
        return view('livewire.product.product-create');
    }
}
