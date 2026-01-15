<?php

namespace App\Livewire\Product;

use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Layout;
use App\Models\Product;
use App\Models\StockPurchase;
use Illuminate\Support\Facades\Auth;

class ProductIndex extends Component
{
    public $categoryFilter = '';
    public $showStockModal = false;
    public $showStockHistoryModal = false;
    public $selectedProductId;
    public $stockQuantity;
    public $purchasePrice;
    public $notes;

    #[On('refresh-table')] 
    public function refresh() 
    {
        // Kosong aja, kuncinya cuma biar dia render ulang
    }

    public function openStockModal($productId)
    {
        $this->selectedProductId = $productId;
        $this->showStockModal = true;
        $this->reset(['stockQuantity', 'purchasePrice', 'notes']);
    }

    public function closeStockModal()
    {
        $this->showStockModal = false;
        $this->reset(['selectedProductId', 'stockQuantity', 'purchasePrice', 'notes']);
    }

    public function addStock()
    {
        $this->validate([
            'stockQuantity' => 'required|integer|min:1',
            'purchasePrice' => 'required|numeric|min:0',
        ]);

        $product = Product::find($this->selectedProductId);
        $totalCost = $this->stockQuantity * $this->purchasePrice;

        // Catat pembelian stok
        StockPurchase::create([
            'product_id' => $this->selectedProductId,
            'quantity' => $this->stockQuantity,
            'purchase_price' => $this->purchasePrice,
            'total_cost' => $totalCost,
            'user_id' => Auth::id(),
            'notes' => $this->notes,
        ]);

        // Update stok produk
        $product->increment('stock', $this->stockQuantity);

        session()->flash('success', "Stok {$product->name} bertambah {$this->stockQuantity} unit!");
        $this->closeStockModal();
    }

    public function openStockHistory($productId)
    {
        $this->selectedProductId = $productId;
        $this->showStockHistoryModal = true;
    }

    public function closeStockHistory()
    {
        $this->showStockHistoryModal = false;
        $this->selectedProductId = null;
    }

    public function cancelLastStock($purchaseId)
    {
        $purchase = StockPurchase::find($purchaseId);
        
        if (!$purchase) {
            session()->flash('error', 'Transaksi tidak ditemukan!');
            return;
        }

        $product = Product::find($purchase->product_id);
        
        // Cek apakah stok cukup untuk dikurangi
        if ($product->stock < $purchase->quantity) {
            session()->flash('error', 'Stok produk tidak cukup! Mungkin sudah terjual.');
            return;
        }

        // Kurangi stok produk
        $product->decrement('stock', $purchase->quantity);
        
        // Hapus record pembelian
        $purchase->delete();

        session()->flash('success', "Transaksi pembelian {$purchase->quantity} unit berhasil dibatalkan!");
        
        // Tutup modal jika tidak ada history lagi
        $remainingHistory = StockPurchase::where('product_id', $this->selectedProductId)->count();
        if ($remainingHistory == 0) {
            $this->closeStockHistory();
        }
    }

    public function delete($id)
    {
        $product = Product::find($id);
        
        if (!$product) {
            session()->flash('error', 'Produk tidak ditemukan!');
            return;
        }

        // Cek apakah produk masih punya stok
        if ($product->stock > 0) {
            // Hitung nilai stok (ambil rata-rata harga beli dari pembelian terakhir)
            $lastPurchase = StockPurchase::where('product_id', $id)
                            ->latest()
                            ->first();
            
            $estimatedValue = $lastPurchase 
                ? $product->stock * $lastPurchase->purchase_price 
                : $product->stock * $product->price;

            session()->flash('error', 
                "❌ Tidak bisa hapus! Produk '{$product->name}' masih punya stok {$product->stock} unit (nilai ±Rp " . number_format($estimatedValue, 0, ',', '.') . "). Jual atau kurangi stok dulu!");
            return;
        }

        $product->delete();
        session()->flash('success', "Produk '{$product->name}' berhasil dihapus!");
    }

    #[Layout('components.layouts.admin')]
    public function render()
    {
        $query = Product::query();
        
        if ($this->categoryFilter) {
            $query->where('category', $this->categoryFilter);
        }
        
        $products = $query->latest()->get();
        $categories = Product::distinct()->pluck('category');
        
        // Load stock history untuk produk yang dipilih
        $stockHistory = null;
        if ($this->selectedProductId) {
            $stockHistory = StockPurchase::where('product_id', $this->selectedProductId)
                ->with('user')
                ->latest()
                ->get();
        }
        
        return view('livewire.product.product-index', [
            'products' => $products,
            'categories' => $categories,
            'totalProducts' => $products->count(),
            'stockHistory' => $stockHistory,
        ]);
    }
}
