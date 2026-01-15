<div>
    <button wire:click="$set('isOpen', true)" class="bg-blue-600 text-white px-6 py-2.5 rounded-lg font-bold hover:bg-blue-700 transition shadow-lg flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
        Tambah Produk
    </button>

    @if($isOpen)
    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
            <!-- Header -->
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-5 rounded-t-2xl sticky top-0 z-10">
                <h2 class="text-xl font-bold text-white flex items-center gap-2">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>
                    Tambah Produk Baru
                </h2>
                <p class="text-blue-100 text-sm mt-1">Isi semua informasi produk</p>
            </div>

            <!-- Body -->
            <div class="p-6 space-y-4">
                <!-- Nama Produk -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Produk *</label>
                    <input type="text" wire:model="name" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                        placeholder="Contoh: Indomie Goreng">
                    @error('name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Kategori -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Kategori *</label>
                    <input type="text" wire:model="category" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                        placeholder="Contoh: Makanan, Minuman, Sabun">
                    @error('category') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <!-- Harga -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Harga Jual *</label>
                        <div class="relative">
                            <span class="absolute left-4 top-3 text-gray-500 font-semibold">Rp</span>
                            <input type="number" wire:model="price" 
                                class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                placeholder="0">
                        </div>
                        @error('price') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <!-- Stock Awal -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Stock Awal *</label>
                        <input type="number" wire:model.live="stock" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                            placeholder="0">
                        @error('stock') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Harga Beli (Muncul jika stock > 0) -->
                @if($stock > 0)
                <div class="bg-yellow-50 border-2 border-yellow-300 rounded-lg p-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                        <svg class="w-5 h-5 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        Harga Beli per Unit *
                    </label>
                    <div class="relative">
                        <span class="absolute left-4 top-3 text-gray-500 font-semibold">Rp</span>
                        <input type="number" wire:model="purchasePrice" 
                            class="w-full pl-12 pr-4 py-3 border border-yellow-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition bg-white"
                            placeholder="0">
                    </div>
                    @error('purchasePrice') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    
                    @if($stock && $purchasePrice)
                    <div class="mt-3 bg-white rounded-lg p-3 border border-yellow-200">
                        <p class="text-xs text-gray-600">Total Modal Stock Awal:</p>
                        <p class="text-lg font-bold text-yellow-700">Rp {{ number_format($stock * $purchasePrice, 0, ',', '.') }}</p>
                    </div>
                    @endif
                </div>
                @endif

                <!-- Gambar Produk -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Gambar Produk</label>
                    
                    @if($image)
                    <div class="mb-3 relative">
                        <img src="{{ $image->temporaryUrl() }}" alt="Preview" 
                            class="w-full h-48 object-cover rounded-lg border-2 border-blue-500">
                        <button type="button" wire:click="removeImage"
                            class="absolute top-2 right-2 bg-red-500 text-white rounded-full p-2 hover:bg-red-600 transition shadow-lg">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>
                    @endif

                    <div class="relative">
                        <input type="file" wire:model="image" accept="image/*" id="imageCreate"
                            class="hidden">
                        <label for="imageCreate" 
                            class="flex items-center justify-center gap-2 w-full px-4 py-3 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer hover:border-blue-500 hover:bg-blue-50 transition">
                            <svg class="w-6 h-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                            <span class="text-sm font-semibold text-gray-600">
                                {{ $image ? 'Ganti Gambar' : 'Pilih Gambar atau Input URL' }}
                            </span>
                        </label>
                    </div>
                    
                    @error('image') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    
                    <p class="text-xs text-gray-500 mt-2 flex items-start gap-1">
                        <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <span>Upload gambar (JPG, PNG, max 2MB) atau input URL gambar online langsung di database</span>
                    </p>
                </div>
            </div>

            <!-- Footer -->
            <div class="px-6 pb-6 flex gap-3">
                <button wire:click="save" 
                    class="flex-1 bg-gradient-to-r from-blue-500 to-blue-600 text-white font-bold py-3 rounded-lg hover:from-blue-600 hover:to-blue-700 transition transform hover:scale-105 shadow-lg flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                    Simpan Produk
                </button>
                <button wire:click="$set('isOpen', false)" 
                    class="px-6 py-3 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition">
                    Batal
                </button>
            </div>
        </div>
    </div>
    @endif
</div>