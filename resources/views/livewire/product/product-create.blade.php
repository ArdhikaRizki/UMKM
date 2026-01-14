<div>
    <button wire:click="$set('isOpen', true)" class="bg-blue-600 text-white px-4 py-2 rounded">
        + Tambah Produk
    </button>

    @if($isOpen)
    <div class="fixed inset-0 bg-black/50 flex items-center justify-center">
        <div class="bg-white p-6 rounded shadow-lg w-96">
            <h2 class="font-bold mb-4">Tambah Baru</h2>
            
            <input type="text" wire:model="name" class="border w-full p-2 mb-4" placeholder="Nama Produk">
            
            <button wire:click="save" class="bg-green-600 text-white px-4 py-2 rounded">Simpan</button>
            <button wire:click="$set('isOpen', false)" class="text-gray-500 ml-2">Batal</button>
        </div>
    </div>
    @endif
</div>