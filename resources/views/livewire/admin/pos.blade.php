<div x-data="{ showCart: false }" class="flex flex-col md:flex-row gap-6 h-[calc(100vh-100px)] relative">
    
    <div :class="showCart ? 'hidden' : 'flex'" 
         class="flex-1 flex-col bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden md:flex">
        
        <div class="p-4 border-b border-gray-100 bg-gray-50/50">
            <div class="relative">
                <input wire:model.live.debounce.300ms="search" 
                       type="text" 
                       class="w-full pl-10 pr-4 py-3 rounded-xl border-none bg-white shadow-sm ring-1 ring-gray-200 focus:ring-2 focus:ring-blue-500 transition"
                       placeholder="Cari barang..."
                       autofocus>
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                </div>
            </div>
        </div>

        <div class="flex-1 overflow-y-auto p-4 bg-gray-50 pb-24 md:pb-4"> <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @forelse($this->products as $product)
                    <div wire:click="addToCart({{ $product->id }})" 
                         class="bg-white rounded-xl shadow-sm border border-gray-100 p-3 cursor-pointer hover:shadow-md hover:border-blue-400 transition group h-full flex flex-col">
                        
                        <div class="h-24 w-full bg-gray-100 rounded-lg mb-3 overflow-hidden">
                             @if($product->image)
                                <img src="{{ $product->image }}" class="w-full h-full object-cover group-hover:scale-105 transition">
                             @else
                                <div class="w-full h-full flex items-center justify-center text-gray-300">No IMG</div>
                             @endif
                        </div>
                        
                        <h4 class="font-bold text-gray-800 text-sm line-clamp-2 leading-tight mb-1">{{ $product->name }}</h4>
                        <div class="mt-auto flex justify-between items-center">
                            <span class="text-blue-600 font-bold text-sm">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                            <span class="text-[10px] bg-gray-100 text-gray-500 px-2 py-0.5 rounded">Stok: {{ $product->stock }}</span>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-10 text-gray-400">
                        Barang tidak ditemukan 
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <div :class="showCart ? 'flex' : 'hidden'" 
         class="w-full md:w-96 bg-white rounded-2xl shadow-sm border border-gray-100 flex-col h-full md:flex">
        
        <div class="p-4 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
            <h3 class="font-bold text-gray-700 flex items-center gap-2">
                <button @click="showCart = false" class="md:hidden mr-2 text-gray-500 hover:text-blue-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </button>
                
                <svg class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                Current Order
            </h3>
            <button wire:click="$set('cart', [])" class="text-xs text-red-500 hover:underline">Reset</button>
        </div>

        <div class="flex-1 overflow-y-auto p-4 space-y-3">
            @forelse($cart as $id => $item)
            
                <div wire:key="cart-item-{{ $item['id'] }}" class="flex justify-between items-start border-b border-gray-50 pb-3 last:border-0">
                    <div class="flex-1">
                        <h4 class="font-bold text-gray-800 text-sm">{{ $item['name'] }}</h4>
                        <p class="text-xs text-gray-400">Rp {{ number_format($item['price'], 0, ',', '.') }}</p>
                    </div>
                    
                    <div class="flex items-center gap-3">
                        <div class="flex items-center bg-gray-100 rounded-lg">
                            <button wire:click="decreaseItem({{ $id }})" class="w-8 h-8 flex items-center justify-center text-gray-500 hover:bg-gray-200 rounded-l-lg text-lg font-bold">-</button>
                            <span class="text-sm font-bold w-8 text-center">{{ $item['quantity'] }}</span>
                            <button wire:click="increaseItem({{ $id }})" class="w-8 h-8 flex items-center justify-center text-blue-600 hover:bg-blue-100 rounded-r-lg text-lg font-bold">+</button>
                        </div>
                        
                        <span class="font-bold text-sm w-20 text-right">
                            {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            @empty
                <div class="h-full flex flex-col items-center justify-center text-gray-300">
                    <svg class="w-12 h-12 mb-2 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                    <p class="text-sm">Keranjang Kosong</p>
                </div>
            @endforelse
        </div>

        <div class="p-5 bg-gray-50 border-t border-gray-100">
            <div class="flex justify-between items-center mb-4">
                <span class="text-gray-500">Total Tagihan</span>
                <span class="text-2xl font-extrabold text-blue-700">
                    Rp {{ empty($cart) ? '0' : number_format($this->total, 0, ',', '.') }}
                    </span>
            </div>

            <div class="mb-4">
                <label class="block text-xs font-bold text-gray-400 mb-1 uppercase">Uang Tunai (Cash)</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500 font-bold">Rp</span>
                    <input wire:model.live="pay" type="number" class="w-full pl-10 pr-4 py-2 rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 font-bold text-gray-800" placeholder="0">
                </div>
            </div>

            <div class="flex justify-between items-center mb-6 text-sm">
                <span class="text-gray-500">Kembalian</span>
                <span class="font-bold {{ $this->Change > 0 ? 'text-green-600' : 'text-gray-400' }}">
                    Rp {{ number_format($this->Change, 0, ',', '.') }}
                </span>
            </div>

            <button wire:click="saveTransaction" 
                    @if(empty($cart) || ($this->pay < $this->total )) disabled @endif
                    class="w-full py-3 bg-blue-600 text-white rounded-xl font-bold shadow-lg hover:bg-blue-700 transition disabled:bg-gray-300 disabled:cursor-not-allowed flex justify-center gap-2">
                <svg class="w-5 h-5" ...>...</svg>
                @if($this->pay < $this->total && !empty($cart))
                    UANG KURANG! 
                @else
                    PROSES BAYAR
                @endif
            </button>
        </div>
    </div>

    <div x-show="!showCart" 
         class="fixed bottom-4 inset-x-4 md:hidden z-50">
        <button @click="showCart = true" class="w-full bg-blue-600 text-white p-4 rounded-xl shadow-2xl flex justify-between items-center font-bold animate-bounce-slow">
            <span class="flex items-center gap-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                <span>Lihat Keranjang</span>
            </span>
            <span class="text-xl">Rp {{ number_format($this->total, 0, ',', '.') }}</span>
        </button>
    </div>

</div>