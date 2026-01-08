<!-- <div>
    {{-- Care about people's approval and you will be their prisoner. --}}
</div> -->

<div class="min-h-screen bg-gray-50">
    
    <div class="relative bg-blue-700 pb-20 pt-10 rounded-b-[40px] shadow-xl overflow-hidden">
        
        <div class="absolute top-0 left-0 w-64 h-64 bg-white opacity-5 rounded-full blur-3xl -translate-x-10 -translate-y-10"></div>
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-blue-400 opacity-20 rounded-full blur-3xl translate-x-20 translate-y-20"></div>

        <div class="container mx-auto px-4 relative z-10 text-center">
            
            <h1 class="text-4xl md:text-5xl font-extrabold text-white mb-4 tracking-tight">
                Warung<span class="text-yellow-300">Kita.</span>
            </h1>
            <p class="text-blue-100 text-lg mb-8 max-w-xl mx-auto font-light">
                Solusi belanja harian tanpa ribet. Stok lengkap, harga tetangga, kualitas juara! 
            </p>

            <div class="max-w-xl mx-auto relative group">
                <div class="absolute inset-y-0 start-0 flex items-center ps-4 pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400 group-focus-within:text-blue-600" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                    </svg>
                </div>
                
                <input wire:model.live="search" 
                       type="text" 
                       class="block w-full p-4 ps-12 text-gray-900 border-none rounded-full bg-white shadow-lg focus:ring-4 focus:ring-blue-300 placeholder-gray-400 text-base transition-all transform hover:scale-[1.01]" 
                       placeholder="Mau cari apa hari ini, Bos? (Indomie, Kopi, Beras...)">
                
                <div wire:loading wire:target="search" class="absolute inset-y-0 right-4 flex items-center">
                    <svg class="animate-spin h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 -mt-10 relative z-20 pb-20">
        
        <div class="flex overflow-x-auto pb-4 gap-3 justify-center mb-6 no-scrollbar">
            <button class="px-5 py-2 rounded-full bg-yellow-400 text-yellow-900 font-bold shadow-md hover:bg-yellow-300 transition text-sm whitespace-nowrap"> Lagi Promo</button>
            <button class="px-5 py-2 rounded-full bg-white text-gray-600 font-medium shadow hover:bg-gray-50 transition text-sm whitespace-nowrap"> Makanan</button>
            <button class="px-5 py-2 rounded-full bg-white text-gray-600 font-medium shadow hover:bg-gray-50 transition text-sm whitespace-nowrap"> Minuman</button>
            <button class="px-5 py-2 rounded-full bg-white text-gray-600 font-medium shadow hover:bg-gray-50 transition text-sm whitespace-nowrap"> Sabun & Cuci</button>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
            @forelse($products as $product)
                <div class="bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 overflow-hidden group flex flex-col h-full relative">
                    
                    @if($product->stock <= 5)
                        <div class="absolute top-3 right-3 z-10 bg-red-500 text-white text-[10px] font-bold px-2 py-1 rounded-full animate-pulse shadow-sm">
                            Sisa {{ $product->stock }}!
                        </div>
                    @endif

                    <div class="h-40 md:h-48 bg-gray-100 overflow-hidden relative">
                         @if($product->image)
                            <img src="{{ $product->image }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-300 bg-gray-50">
                                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                        @endif
                        
                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-5 transition duration-300"></div>
                    </div>

                    <div class="p-4 flex flex-col flex-1">
                        <div class="mb-2">
                            <span class="text-[10px] font-bold tracking-wider text-blue-500 uppercase bg-blue-50 px-2 py-0.5 rounded-md">
                                {{ $product->category ?? 'Umum' }}
                            </span>
                        </div>
                        <h3 class="text-gray-800 font-bold text-base leading-snug mb-1 line-clamp-2 group-hover:text-blue-600 transition">
                            {{ $product->name }}
                        </h3>
                        
                        <div class="mt-auto pt-3 flex items-center justify-between">
                            <div class="flex flex-col">
                                <span class="text-xs text-gray-400 line-through">Rp {{ number_format($product->price * 1.1, 0, ',', '.') }}</span>
                                <span class="text-lg font-bold text-gray-900">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                            </div>
                            <button class="w-8 h-8 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center hover:bg-blue-600 hover:text-white transition shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-12 text-center">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-blue-50 rounded-full mb-4">
                        <svg class="w-10 h-10 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900">Yah, barangnya ngumpet! </h3>
                    <p class="text-gray-500 max-w-md mx-auto">
                        Kata kunci <strong>"{{ $search }}"</strong> gak ketemu nih. Coba cari nama lain atau cek ejaannya ya.
                    </p>
                </div>
            @endforelse
        </div>
        
        <div class="mt-10 text-center text-gray-400 text-sm">
            &copy; {{ date('Y') }} WarungKita Digital. Dibuat dengan 💙 dan Laravel Livewire.
        </div>
    </div>
</div>