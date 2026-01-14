<div class="p-6 space-y-8">

    <div class="flex flex-col md:flex-row justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-800">Pembukuan Warung</h1>
            <p class="text-gray-500">Laporan Penjualan</p>
        </div>
    </div>

    <!-- SECTION 1: RINCIAN HARI INI (ATAS) -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-4 border-b border-gray-100 bg-gradient-to-r from-blue-500 to-blue-600 flex justify-between items-center">
            <div>
                <h3 class="font-bold text-white flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                    Rincian Hari Ini
                </h3>   
                <p class="text-blue-100 text-sm mt-1">{{ \Carbon\Carbon::parse($this->date)->locale('id')->isoFormat('dddd, D MMMM Y') }}</p>
            </div>
            <div class="flex items-center gap-2">
                <button wire:click="toggleView" class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg text-sm font-semibold transition">
                    {{ $showPurchaseReport ? 'Penjualan' : 'Pembelian Stok' }}
                </button>
                <input type="date" wire:model.live="date" class="border-none bg-white/20 text-white rounded-lg text-sm placeholder-blue-200">
            </div>
        </div>

        @if(!$showPurchaseReport)
        <!-- Filter dan Search Penjualan -->
        <div class="p-4 bg-gray-50 border-b border-gray-100 flex flex-col md:flex-row gap-3">
            <div class="flex-1">
                <div class="relative">
                    <input wire:model.live.debounce.300ms="search" 
                           type="text" 
                           class="w-full pl-10 pr-4 py-2 rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500" 
                           placeholder="Cari no. nota...">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                    </div>
                </div>
            </div>
            <div class="w-full md:w-48">
                <select wire:model.live="hourFilter" class="w-full px-4 py-2 rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Semua Jam</option>
                    @foreach(range(0, 23) as $hour)
                        <option value="{{ $hour }}">{{ str_pad($hour, 2, '0', STR_PAD_LEFT) }}:00 - {{ str_pad($hour, 2, '0', STR_PAD_LEFT) }}:59</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Total Revenue Hari Ini -->
        <div class="p-6 bg-gradient-to-br from-green-50 to-emerald-50 border-b border-gray-100">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-sm text-gray-600 font-semibold uppercase mb-1">Total Revenue Hari Ini</p>
                    <h2 class="text-3xl font-extrabold text-green-600">
                        Rp {{ number_format($transactions->sum('total_amount'), 0, ',', '.') }}
                    </h2>
                </div>
                <div class="p-4 bg-green-500 rounded-2xl">
                    <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
            </div>
            <p class="text-sm text-gray-500 mt-2">Dari {{ $transactions->count() }} transaksi</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-100 text-gray-500 uppercase font-bold text-xs">
                    <tr>
                        <th class="px-6 py-3">Jam</th>
                        <th class="px-6 py-3">No. Nota</th>
                        <th class="px-6 py-3 text-right">Total Belanja</th>
                        <th class="px-6 py-3 text-center">Detail</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($transactions as $trx)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">{{ $trx->created_at->format('H:i') }}</td>
                            <td class="px-6 py-4 font-mono font-bold text-blue-600">#{{ $trx->invoice_no }}</td>
                            <td class="px-6 py-4 text-right font-bold text-gray-800">Rp {{ number_format($trx->total_amount, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-center">
                                <button wire:click="showDetail({{ $trx->id }})" class="text-blue-500 hover:underline text-xs font-bold">Lihat</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-10 text-center text-gray-400">Tidak ada penjualan di tanggal ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @else
        <!-- Laporan Pembelian Stok -->
        <div class="p-6 bg-gradient-to-br from-orange-50 to-orange-50 border-b border-gray-100">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-sm text-gray-600 font-semibold uppercase mb-1">Total Pengeluaran Pembelian Stok</p>
                    <h2 class="text-4xl font-extrabold text-red-600">Rp {{ number_format($totalPurchaseCost, 0, ',', '.') }}</h2>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-500">Total Transaksi</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $stockPurchases->count() }}</p>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr class="text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        <th class="px-6 py-3">Waktu</th>
                        <th class="px-6 py-3">Produk</th>
                        <th class="px-6 py-3 text-center">Qty</th>
                        <th class="px-6 py-3 text-right">Harga Beli</th>
                        <th class="px-6 py-3 text-right">Total</th>
                        <th class="px-6 py-3">Catatan</th>
                        <th class="px-6 py-3">Oleh</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($stockPurchases as $purchase)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">{{ $purchase->created_at->format('H:i') }}</td>
                            <td class="px-6 py-4">
                                <span class="font-bold text-gray-800">{{ $purchase->product->name ?? '(Produk Tidak Diketahui)' }}</span>
                                @if($purchase->product && $purchase->product->trashed())
                                    <span class="ml-2 bg-red-100 text-red-700 text-xs px-2 py-0.5 rounded-full font-semibold"> Dihapus</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full font-bold">{{ $purchase->quantity }}</span>
                            </td>
                            <td class="px-6 py-4 text-right text-gray-600">Rp {{ number_format($purchase->purchase_price, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-right font-bold text-red-600">Rp {{ number_format($purchase->total_cost, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $purchase->notes ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $purchase->user->name }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-10 text-center text-gray-400">Tidak ada pembelian stok di tanggal ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @endif
    </div>

    <!-- SECTION 2: LAPORAN BULANAN (BAWAH) -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex flex-col md:flex-row justify-between items-center mb-6">
            <div>
                <h3 class="text-xl font-bold text-gray-800">Laporan Bulanan</h3>
                <p class="text-gray-500">Ringkasan bulan {{ \Carbon\Carbon::create()->month((int) $this->month)->locale('id')->monthName }} {{ (int) $this->year }}</p>
            </div>
            
            <select wire:model.live="month" class="mt-2 md:mt-0 bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 font-bold">
                @foreach(range(1, 12) as $m)
                    <option value="{{ $m }}">{{ \Carbon\Carbon::create()->month($m)->locale('id')->monthName }}</option>
                @endforeach
            </select>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <!-- Pemasukan -->
            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-5 text-white shadow-lg">
                <div class="flex justify-between items-start mb-3">
                    <div class="p-2 bg-white/20 rounded-lg">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12" /></svg>
                    </div>
                </div>
                <p class="text-green-100 text-xs font-semibold uppercase mb-1">Pemasukan</p>
                <h3 class="text-2xl font-extrabold">Rp {{ number_format($grandTotal, 0, ',', '.') }}</h3>
                <p class="text-xs text-green-100 mt-1">{{ $totalBon }} transaksi</p>
            </div>

            <!-- Pengeluaran -->
            <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-xl p-5 text-white shadow-lg">
                <div class="flex justify-between items-start mb-3">
                    <div class="p-2 bg-white/20 rounded-lg">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6" /></svg>
                    </div>
                </div>
                <p class="text-red-100 text-xs font-semibold uppercase mb-1">Pengeluaran</p>
                <h3 class="text-2xl font-extrabold">Rp {{ number_format($totalPurchase, 0, ',', '.') }}</h3>
                <p class="text-xs text-red-100 mt-1">Pembelian stok</p>
            </div>

            <!-- Profit/Rugi -->
            <div class="bg-gradient-to-br from-{{ $profit >= 0 ? 'blue' : 'orange' }}-500 to-{{ $profit >= 0 ? 'blue' : 'orange' }}-600 rounded-xl p-5 text-white shadow-lg">
                <div class="flex justify-between items-start mb-3">
                    <div class="p-2 bg-white/20 rounded-lg">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
                    </div>
                </div>
                <p class="text-{{ $profit >= 0 ? 'blue' : 'orange' }}-100 text-xs font-semibold uppercase mb-1">{{ $profit >= 0 ? 'Profit' : 'Rugi' }}</p>
                <h3 class="text-2xl font-extrabold">Rp {{ number_format(abs($profit), 0, ',', '.') }}</h3>
                <p class="text-xs text-{{ $profit >= 0 ? 'blue' : 'orange' }}-100 mt-1">{{ $profit >= 0 ? 'Untung!' : 'Merugi' }}</p>
            </div>

            <!-- Margin -->
            <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-5 text-white shadow-lg">
                <div class="flex justify-between items-start mb-3">
                    <div class="p-2 bg-white/20 rounded-lg">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" /></svg>
                    </div>
                </div>
                <p class="text-purple-100 text-xs font-semibold uppercase mb-1">Margin</p>
                <h3 class="text-2xl font-extrabold">{{ $grandTotal > 0 ? number_format(($profit / $grandTotal) * 100, 1) : 0 }}%</h3>
                <p class="text-xs text-purple-100 mt-1">Profit margin</p>
            </div>
        </div>

        <h3 class="font-bold text-gray-700 mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" /></svg>
            Grafik Keuangan Harian
        </h3>
        
        <div id="chartPenjualan" class="w-full h-80 bg-white rounded-xl border border-gray-100 p-4"></div>
    </div>

    @if($selectedTransaction)
       <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
            <div class="bg-white w-full max-w-lg rounded-2xl shadow-2xl overflow-hidden">
                <div class="bg-blue-600 p-4 text-white flex justify-between items-center">
                    <h3 class="font-bold">Nota #{{ $selectedTransaction->invoice_no }}</h3>
                    <button wire:click="closeDetail" class="text-white hover:bg-blue-700 rounded-full p-1">X</button>
                </div>
                <div class="p-6 max-h-[60vh] overflow-y-auto">
                    <table class="w-full text-sm">
                        <tbody class="divide-y divide-gray-50">
                            @foreach($selectedTransaction->details as $detail)
                                <tr>
                                    <td class="py-3 font-bold text-gray-700">
                                        {{ $detail->product->name ?? 'Produk Dihapus' }} <br>
                                        <span class="text-xs text-gray-400 font-normal">{{ $detail->qty }} x Rp {{ number_format($detail->price, 0, ',', '.') }}</span>
                                    </td>
                                    <td class="py-3 text-right font-bold">
                                        Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="p-4 bg-gray-50 text-center">
                    <button wire:click="closeDetail" class="px-6 py-2 bg-blue-600 text-white rounded-lg font-bold hover:bg-blue-700">Tutup</button>
                </div>
            </div>
        </div>
    @endif

</div>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script>
    let chart;

    function renderChart(labels, salesData, purchaseData, profitData) {
        if (chart) {
            chart.destroy();
        }

        var options = {
            series: [
                {
                    name: 'Pemasukan',
                    data: salesData,
                    type: 'area'
                },
                {
                    name: 'Pengeluaran',
                    data: purchaseData,
                    type: 'area'
                },
                {
                    name: 'Profit',
                    data: profitData,
                    type: 'line'
                }
            ],
            chart: {
                height: 320,
                type: 'line',
                fontFamily: 'inherit',
                toolbar: { 
                    show: true,
                    tools: {
                        download: true,
                        selection: false,
                        zoom: true,
                        zoomin: true,
                        zoomout: true,
                        pan: false,
                        reset: true
                    }
                },
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 800
                }
            },
            colors: ['#10B981', '#EF4444', '#3B82F6'],
            dataLabels: { enabled: false },
            stroke: { 
                curve: 'smooth', 
                width: [2, 2, 3],
                dashArray: [0, 0, 0]
            },
            fill: {
                type: ['gradient', 'gradient', 'solid'],
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.4,
                    opacityTo: 0.05,
                    stops: [0, 90, 100]
                }
            },
            xaxis: {
                categories: labels,
                axisBorder: { show: false },
                axisTicks: { show: false },
                labels: { 
                    style: { fontSize: '10px', colors: '#9CA3AF' },
                    rotate: -45,
                    rotateAlways: false
                }
            },
            yaxis: {
                labels: {
                    formatter: function (value) {
                        return "Rp " + new Intl.NumberFormat('id-ID').format(value);
                    },
                    style: { fontSize: '10px', colors: '#9CA3AF' }
                }
            },
            tooltip: {
                shared: true,
                intersect: false,
                y: {
                    formatter: function (val) {
                        return "Rp " + new Intl.NumberFormat('id-ID').format(val);
                    }
                }
            },
            legend: {
                position: 'bottom',
                horizontalAlign: 'center',
                fontSize: '12px',
                markers: {
                    width: 10,
                    height: 10,
                    radius: 12
                },
                itemMargin: {
                    horizontal: 15,
                    vertical: 5
                }
            },
            grid: {
                borderColor: '#f3f4f6',
                strokeDashArray: 3,
                xaxis: { lines: { show: false } },
                yaxis: { lines: { show: true } }
            }
        };

        chart = new ApexCharts(document.querySelector("#chartPenjualan"), options);
        chart.render();
    }

    // 1. Initial Render (Saat halaman pertama dibuka)
    document.addEventListener('livewire:initialized', () => {
        renderChart(
            {{ \Illuminate\Support\Js::from($chartLabels) }},
            {{ \Illuminate\Support\Js::from($chartSalesData) }},
            {{ \Illuminate\Support\Js::from($chartPurchaseData) }},
            {{ \Illuminate\Support\Js::from($chartProfitData) }}
        );
    });

    // 2. Event Listener (Saat user ganti bulan/tahun)
    document.addEventListener('livewire:initialized', () => {
        Livewire.on('update-chart', (event) => {
            renderChart(event.labels, event.salesData, event.purchaseData, event.profitData); 
        });
    });
</script>