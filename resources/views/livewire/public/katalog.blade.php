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
                            <button wire:click = "addToCart({{$product->id }})"
                            wire:loading.attr="disabled" 
                            class="w-8 h-8 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center hover:bg-blue-600 hover:text-white transition shadow-sm">
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
        
        <div class="fixed bottom-6 right-6 z-50">
    <a href="#" class="relative bg-blue-600 text-white p-4 rounded-full shadow-lg hover:bg-blue-700 transition flex items-center justify-center">
        
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
        </svg>

        @if($this->totalCart > 0)
            <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full animate-bounce">
                {{ $this->totalCart }}
            </span>
        @endif

    </a>
</div>
        
        <div class="mt-10 text-center text-gray-400 text-sm">
            &copy; {{ date('Y') }} WarungKita Digital. Dibuat dengan 💙 dan Laravel Livewire.
        </div>
    </div>

    <div class="fixed bottom-6 right-6 z-40">
        <button wire:click="toggleCart" class="relative bg-blue-600 text-white p-4 rounded-full shadow-lg hover:bg-blue-700 transition flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            @if($this->totalCart > 0)
                <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full animate-bounce">
                    {{ $this->totalCart }}
                </span>
            @endif
        </button>
    </div>

@if($showCart)
    <div class="fixed inset-0 bg-black bg-opacity-50 z-[60] transition-opacity" 
         wire:click="toggleCart"></div>

    <div class="fixed inset-y-0 right-0 w-full md:w-96 bg-white z-[70] shadow-2xl transform transition-transform duration-300 flex flex-col">
        
        <div class="p-5 border-b flex justify-between items-center bg-gray-50 shadow-sm z-10">
            <h2 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                Keranjang Belanja
            </h2>
            
            <button wire:click="toggleCart" class="p-2 bg-red-100 text-red-600 rounded-full hover:bg-red-200 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <div class="flex-1 overflow-y-auto p-5 space-y-4 pb-24">
            @forelse(session('cart', []) as $id => $details)
                <div class="flex gap-4 border-b border-gray-100 pb-4 last:border-0 animate-fadeIn">
                    <div class="w-20 h-20 bg-gray-100 rounded-lg overflow-hidden flex-shrink-0 border border-gray-200">
                        <img src="{{ $details['image'] }}" class="w-full h-full object-cover">
                    </div>

                    <div class="flex-1 flex flex-col justify-between">
                        <div>
                            <h3 class="font-bold text-gray-800 line-clamp-2 text-base">{{ $details['name'] }}</h3>
                            <p class="text-blue-600 font-semibold text-sm">Rp {{ number_format($details['price'], 0, ',', '.') }}</p>
                        </div>
                        
                        <div class="flex items-center justify-between mt-2">
                            <div class="flex items-center bg-gray-100 rounded-full px-1 py-1">
                                <button wire:click="decreaseQuantity({{ $id }})" class="w-8 h-8 rounded-full bg-white text-gray-600 shadow-sm hover:bg-gray-200 flex items-center justify-center font-bold text-lg active:scale-90 transition">-</button>
                                <span class="text-gray-800 font-bold w-8 text-center text-sm">{{ $details['quantity'] }}</span>
                                <button wire:click="addQuantity({{ $id }})" class="w-8 h-8 rounded-full bg-blue-600 text-white shadow-sm hover:bg-blue-700 flex items-center justify-center font-bold text-lg active:scale-90 transition">+</button>
                            </div>
                            
                            <button wire:click="removeFromCart({{ $id }})" class="text-red-500 hover:bg-red-50 p-2 rounded-lg transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="h-full flex flex-col items-center justify-center text-gray-400 space-y-4">
                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center">
                        <svg class="w-12 h-12 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <p class="font-medium">Keranjang masih kosong, Bos!</p>
                    <button wire:click="toggleCart" class="text-blue-600 font-bold hover:underline">Mulai Belanja</button>
                </div>
            @endforelse
        </div>

        <div class="p-5 border-t bg-white shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.1)] z-20">
            <div class="flex justify-between items-center mb-4">
                <span class="text-gray-600 font-medium">Total Pembayaran:</span>
                <span class="text-2xl font-extrabold text-blue-700">Rp {{ number_format($this->totalPrice, 0, ',', '.') }}</span>
            </div>
            
            @if($this->totalCart > 0)
                <button wire:click="checkout" 
                    wire:loading.attr="disabled"
                    class="w-full py-4 bg-green-500 text-white rounded-xl font-bold text-lg hover:bg-green-600 shadow-lg active:scale-[0.98] transition flex items-center justify-center gap-2 disabled:bg-gray-400 disabled:cursor-not-allowed">
                
                <svg wire:loading.remove wire:target="checkout" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                
                <svg wire:loading wire:target="checkout" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>

                <span wire:loading.remove wire:target="checkout">Pesan Sekarang (WA)</span>
                <span wire:loading wire:target="checkout">Membuka WhatsApp...</span>
                </button>
            @else
                 <button disabled class="w-full py-4 bg-gray-200 text-gray-400 rounded-xl font-bold cursor-not-allowed">
                    Pilih Barang Dulu
                </button>
            @endif
        </div>
    </div>
@endif
    </div> 


</div>