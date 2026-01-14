<div>
    @if($isOpen)
    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md">
            <!-- Header -->
            <div class="bg-gradient-to-r from-yellow-500 to-orange-500 p-5 rounded-t-2xl">
                <h2 class="text-xl font-bold text-white flex items-center gap-2">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                    Edit Produk
                </h2>
                <p class="text-yellow-100 text-sm mt-1">Perbarui informasi produk (Stock tidak bisa diubah di sini)</p>
            </div>

            <!-- Body -->
            <div class="p-6 space-y-4">
                <!-- Nama Produk -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Produk</label>
                    <input type="text" wire:model="name" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition"
                        placeholder="Masukkan nama produk">
                    @error('name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Kategori -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Kategori</label>
                    <input type="text" wire:model="category" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition"
                        placeholder="Contoh: Makanan, Minuman, dll">
                    @error('category') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Harga -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Harga Jual</label>
                    <div class="relative">
                        <span class="absolute left-4 top-3 text-gray-500 font-semibold">Rp</span>
                        <input type="number" wire:model="price" 
                            class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition"
                            placeholder="0">
                    </div>
                    @error('price') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Gambar Produk -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Gambar Produk</label>
                    
                    <!-- Preview Gambar Lama -->
                    @if($oldImage && !$image)
                    <div class="mb-3 relative group">
                        <img src="{{ str_starts_with($oldImage, 'http') ? $oldImage : Storage::url($oldImage) }}" alt="Current Image" 
                            class="w-full h-48 object-cover rounded-lg border-2 border-gray-200">
                        <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition flex items-center justify-center rounded-lg">
                            <p class="text-white text-sm font-semibold">Gambar saat ini</p>
                        </div>
                    </div>
                    @endif

                    <!-- Preview Gambar Baru -->
                    @if($image)
                    <div class="mb-3 relative">
                        <img src="{{ $image->temporaryUrl() }}" alt="Preview" 
                            class="w-full h-48 object-cover rounded-lg border-2 border-green-500">
                        <button type="button" wire:click="removeImage"
                            class="absolute top-2 right-2 bg-red-500 text-white rounded-full p-2 hover:bg-red-600 transition shadow-lg">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                        <div class="absolute bottom-2 left-2 bg-green-500 text-white px-3 py-1 rounded-full text-xs font-bold flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                            Gambar Baru
                        </div>
                    </div>
                    @endif

                    <!-- Input Upload -->
                    <div class="relative">
                        <input type="file" wire:model="image" accept="image/*" id="imageEdit"
                            class="hidden">
                        <label for="imageEdit" 
                            class="flex items-center justify-center gap-2 w-full px-4 py-3 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer hover:border-yellow-500 hover:bg-yellow-50 transition">
                            <svg class="w-6 h-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                            <span class="text-sm font-semibold text-gray-600">
                                {{ $image ? 'Ganti Gambar Lagi' : ($oldImage ? 'Ganti Gambar' : 'Pilih Gambar') }}
                            </span>
                        </label>
                    </div>
                    
                    @error('image') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    
                    <p class="text-xs text-gray-500 mt-2 flex items-start gap-1">
                        <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                        <span>Format: JPG, PNG (Maks. 2MB). Kosongkan jika tidak ingin mengganti gambar.</span>
                    </p>
                </div>

                <!-- Info Stock -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-blue-600 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <div>
                            <p class="text-sm font-semibold text-blue-900">Catatan Stock</p>
                            <p class="text-xs text-blue-700 mt-1">Stock tidak bisa diubah di form edit. Gunakan tombol <strong>"Tambah Stock"</strong> untuk menambah stock. Jika salah input, bisa dibatalkan lewat riwayat stock.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="px-6 pb-6 flex gap-3">
                <button wire:click="update" 
                    class="flex-1 bg-gradient-to-r from-yellow-500 to-orange-500 text-white font-bold py-3 rounded-lg hover:from-yellow-600 hover:to-orange-600 transition transform hover:scale-105 shadow-lg flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" /></svg>
                    Simpan Perubahan
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