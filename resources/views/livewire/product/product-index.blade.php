<div class="max-w-7xl mx-auto">
    <!-- Flash Messages -->
    @if(session('success'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl font-semibold">
            ✅ {{ session('success') }}
        </div>
    @endif
    
    @if(session('error'))
        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl font-semibold">
            {{ session('error') }}
        </div>
    @endif

    <!-- Header Section -->
    <div class="mb-4 flex flex-col md:flex-row md:justify-between md:items-center gap-3">
        <livewire:product.product-create />
        
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
            <div class="bg-blue-50 px-4 py-2 rounded-lg text-center">
                <div class="text-xs text-gray-600">Total Produk</div>
                <div class="text-2xl font-bold text-blue-600">{{ $totalProducts }}</div>
            </div>
            
            <select wire:model.live="categoryFilter" class="border rounded-lg px-4 py-2 text-sm bg-white">
                <option value=""> Semua Kategori</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat }}">{{ $cat }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <livewire:product.product-edit />

    <!-- Desktop Table View -->
    <div class="hidden md:block overflow-x-auto bg-white rounded-xl shadow-sm">
        <table class="w-full">
            <thead>
                <tr class="bg-gradient-to-r from-blue-500 to-blue-600 text-white">
                    <th class="p-3 text-left">No</th>
                    <th class="p-3 text-left">Nama Produk</th>
                    <th class="p-3 text-left">Kategori</th>
                    <th class="p-3 text-right">Harga</th>
                    <th class="p-3 text-center">Stok</th>
                    <th class="p-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $index => $p)
                <tr class="border-b hover:bg-blue-50 transition">
                    <td class="p-3 text-gray-600 font-semibold">{{ $index + 1 }}</td>
                    <td class="p-3 font-medium">{{ $p->name }}</td>
                    <td class="p-3">
                        <span class="bg-blue-100 text-blue-800 text-xs px-3 py-1 rounded-full font-semibold">{{ $p->category }}</span>
                    </td>
                    <td class="p-3 text-right font-semibold text-gray-700">Rp {{ number_format($p->price, 0, ',', '.') }}</td>
                    <td class="p-3 text-center">
                        <span class="px-3 py-1 rounded-full text-sm font-bold {{ $p->stock < 10 ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' }}">
                            {{ $p->stock }}
                        </span>
                    </td>
                    <td class="p-3">
                        <div class="flex justify-center gap-1">
                            <button 
                                wire:click="openStockModal({{ $p->id }})"
                                class="bg-green-500 text-white px-3 py-1.5 rounded-lg text-xs font-semibold hover:bg-green-600 transition"
                                title="Tambah Stok"
                            >
                                + Stok
                            </button>

                            <button 
                                wire:click="openStockHistory({{ $p->id }})"
                                class="bg-blue-500 text-white px-3 py-1.5 rounded-lg text-xs font-semibold hover:bg-blue-600 transition flex items-center gap-1"
                                title="Riwayat Stok"
                            >
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                            </button>
                            
                            <button 
                                wire:click="$dispatch('edit-mode', { id: {{ $p->id }} })"
                                class="bg-yellow-400 text-gray-800 px-3 py-1.5 rounded-lg text-xs font-semibold hover:bg-yellow-500 transition"
                                title="Edit Produk"
                            >
                                Edit
                            </button>

                            <button 
                                wire:click="delete({{ $p->id }})" 
                                class="bg-red-500 text-white px-3 py-1.5 rounded-lg text-xs font-semibold hover:bg-red-600 transition"
                                onclick="return confirm('Hapus produk ini?')"
                                title="Hapus Produk"
                            >
                                Hapus
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="p-8 text-center text-gray-500">
                        <div class="text-4xl mb-2"></div>
                        <div class="font-semibold">Belum ada produk</div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Mobile Card View -->
    <div class="md:hidden space-y-3">
        @forelse($products as $index => $p)
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white px-4 py-2 flex justify-between items-center">
                <span class="font-bold">{{ $index + 1 }}. {{ $p->name }}</span>
                <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full font-semibold">{{ $p->category }}</span>
            </div>
            
            <div class="p-4 space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-gray-600 text-sm">Harga:</span>
                    <span class="font-bold text-lg text-gray-800">Rp {{ number_format($p->price, 0, ',', '.') }}</span>
                </div>
                
                <div class="flex justify-between items-center">
                    <span class="text-gray-600 text-sm">Stok:</span>
                    <span class="px-4 py-1 rounded-full text-sm font-bold {{ $p->stock < 10 ? 'bg-red-100 text-red-700 animate-pulse' : 'bg-green-100 text-green-700' }}">
                        {{ $p->stock }} unit
                        @if($p->stock < 10)
                            <span class="text-xs">Rendah</span>
                        @endif
                    </span>
                </div>
                
                <div class="flex gap-2 pt-2">
                    <button 
                        wire:click="openStockModal({{ $p->id }})"
                        class="flex-1 bg-green-500 text-white py-2 rounded-lg text-sm font-bold hover:bg-green-600 transition"
                    >
                        + Stok
                    </button>

                    <button 
                        wire:click="openStockHistory({{ $p->id }})"
                        class="bg-blue-500 text-white px-3 py-2 rounded-lg text-sm font-bold hover:bg-blue-600 transition flex items-center justify-center"
                        title="Riwayat"
                    >
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                    </button>
                    
                    <button 
                        wire:click="$dispatch('edit-mode', { id: {{ $p->id }} })"
                        class="flex-1 bg-yellow-400 text-gray-800 py-2 rounded-lg text-sm font-bold hover:bg-yellow-500 transition"
                    >
                        Edit
                    </button>

                    <button 
                        wire:click="delete({{ $p->id }})" 
                        class="bg-red-500 text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-red-600 transition"
                        onclick="return confirm('Hapus produk ini?')"
                    >
                        Hapus
                    </button>
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-xl p-8 text-center text-gray-500">
            <div class="text-5xl mb-3"></div>
            <div class="font-semibold text-lg">Belum ada produk</div>
        </div>
        @endforelse
    </div>

    <!-- Modal Tambah Stok -->
    @if($showStockModal)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" wire:click="closeStockModal">
        <div class="bg-white rounded-xl p-6 w-96" wire:click.stop>
            <h3 class="text-xl font-bold mb-4">Tambah Stok Produk</h3>
            
            @if(session('success'))
                <div class="bg-green-100 text-green-700 p-3 rounded mb-4">{{ session('success') }}</div>
            @endif

            <div class="mb-4">
                <label class="block font-bold mb-2">Jumlah Stok</label>
                <input type="number" wire:model="stockQuantity" class="w-full border rounded-lg p-2" placeholder="Contoh: 10" min="1">
                @error('stockQuantity') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label class="block font-bold mb-2">Harga Beli per Unit (Rp)</label>
                <input type="number" wire:model="purchasePrice" class="w-full border rounded-lg p-2" placeholder="Contoh: 5000" min="0">
                @error('purchasePrice') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label class="block font-bold mb-2">Catatan (Opsional)</label>
                <textarea wire:model="notes" class="w-full border rounded-lg p-2" rows="2" placeholder="Supplier, keterangan, dll"></textarea>
            </div>

            @if($stockQuantity && $purchasePrice)
            <div class="bg-blue-50 p-3 rounded mb-4">
                <p class="text-sm text-gray-600">Total Biaya:</p>
                <p class="text-xl font-bold text-blue-600">Rp {{ number_format($stockQuantity * $purchasePrice, 0, ',', '.') }}</p>
            </div>
            @endif

            <div class="flex gap-2">
                <button wire:click="addStock" class="flex-1 bg-green-500 text-white py-2 rounded-lg font-bold hover:bg-green-600">
                    Simpan
                </button>
                <button wire:click="closeStockModal" class="flex-1 bg-gray-300 text-gray-700 py-2 rounded-lg font-bold hover:bg-gray-400">
                    Batal
                </button>
            </div>
        </div>
    </div>
    @endif

    <!-- Modal Riwayat Stock -->
    @if($showStockHistoryModal && $stockHistory)
    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-3xl max-h-[90vh] overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-blue-500 to-indigo-600 p-5 text-white">
                <h3 class="text-xl font-bold flex items-center gap-2">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                    Riwayat Penambahan Stok
                </h3>
                <p class="text-blue-100 text-sm mt-1">Klik "Batalkan" untuk membatalkan transaksi jika salah input</p>
            </div>

            <!-- Content -->
            <div class="p-6 overflow-y-auto max-h-[calc(90vh-180px)]">
                @if($stockHistory->count() > 0)
                    <div class="space-y-3">
                        @foreach($stockHistory as $history)
                        <div class="border border-gray-200 rounded-xl p-4 hover:shadow-md transition bg-gradient-to-r from-gray-50 to-white">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-2">
                                        <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-xs font-bold">
                                            +{{ $history->quantity }} Unit
                                        </span>
                                        <span class="text-xs text-gray-500">
                                            {{ $history->created_at->format('d M Y, H:i') }}
                                        </span>
                                    </div>
                                    
                                    <div class="grid grid-cols-2 gap-3 text-sm">
                                        <div>
                                            <p class="text-gray-500 text-xs">Harga Beli/Unit</p>
                                            <p class="font-bold text-gray-800">Rp {{ number_format($history->purchase_price, 0, ',', '.') }}</p>
                                        </div>
                                        <div>
                                            <p class="text-gray-500 text-xs">Total Biaya</p>
                                            <p class="font-bold text-green-600">Rp {{ number_format($history->total_cost, 0, ',', '.') }}</p>
                                        </div>
                                    </div>

                                    @if($history->notes)
                                    <div class="mt-2 bg-yellow-50 border-l-4 border-yellow-400 p-2 rounded">
                                        <p class="text-xs text-gray-700"><strong>Catatan:</strong> {{ $history->notes }}</p>
                                    </div>
                                    @endif

                                    <p class="text-xs text-gray-400 mt-2">
                                        Oleh: {{ $history->user->name ?? 'System' }}
                                    </p>
                                </div>

                                <button 
                                    wire:click="cancelLastStock({{ $history->id }})"
                                    onclick="return confirm('Yakin batalkan transaksi ini? Stok akan dikurangi {{ $history->quantity }} unit.')"
                                    class="ml-4 bg-red-500 text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-red-600 transition transform hover:scale-105 flex items-center gap-2"
                                    title="Batalkan transaksi ini"
                                >
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                    Batalkan
                                </button>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <p class="text-sm text-blue-800 flex items-start gap-2">
                            <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" /></svg>
                            <span><strong>Tips:</strong> Jika salah input stok, klik "Batalkan" pada transaksi yang ingin dibatalkan. Stok produk akan otomatis dikurangi.</span>
                        </p>
                    </div>
                @else
                    <div class="text-center py-12">
                        <svg class="w-24 h-24 mx-auto text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" /></svg>
                        <p class="text-gray-500 font-semibold">Belum ada riwayat penambahan stok</p>
                    </div>
                @endif
            </div>

            <!-- Footer -->
            <div class="p-4 bg-gray-50 border-t flex justify-end">
                <button wire:click="closeStockHistory" class="px-6 py-2 bg-gray-600 text-white rounded-lg font-bold hover:bg-gray-700 transition">
                    Tutup
                </button>
            </div>
        </div>
    </div>
    @endif
</div>
