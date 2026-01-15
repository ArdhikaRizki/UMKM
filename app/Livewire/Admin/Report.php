<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\StockPurchase;
use App\Models\Product;
use Livewire\Attributes\Layout;
use Carbon\Carbon;
use Carbon\CarbonPeriod; // Buat bikin urutan tanggal

class Report extends Component
{
    public $date;
    public $month; // Filter Bulan buat Grafik
    public $year;
    public $search = ''; // Search by invoice
    public $hourFilter = ''; // Filter by hour
    public $selectedTransaction = null;
    public $showPurchaseReport = false; // Toggle view pembelian

    public function mount()
    {
        $this->date = Carbon::today()->format('Y-m-d');
        $this->month = Carbon::now()->month; // Default bulan ini
        $this->year = Carbon::now()->year;
    }

    public function showDetail($id)
    {
        $this->selectedTransaction = Transaction::with('details.product')->find($id);
    }

    public function closeDetail()
    {
        $this->selectedTransaction = null;
    }
    
    public function updatedMonth() {
        $this->updateChart();
    }

    public function updatedYear() {
        $this->updateChart();
    }

    // Fungsi bantu buat ngirim event
    public function updateChart() {
        $bulan = (int) $this->month;
        $tahun = (int) $this->year;
        
        $start = Carbon::createFromDate($tahun, $bulan, 1);
        $end = $start->copy()->endOfMonth();
        $period = CarbonPeriod::create($start, $end);
        
        $monthlySales = Transaction::whereMonth('created_at', $bulan)
                        ->whereYear('created_at', $tahun)
                        ->get();

        $monthlyPurchases = StockPurchase::whereMonth('created_at', $bulan)
                        ->whereYear('created_at', $tahun)
                        ->get();

        $chartLabels = [];
        $chartSalesData = [];
        $chartPurchaseData = [];
        $chartProfitData = [];

        foreach ($period as $dt) {
            $dateString = $dt->format('Y-m-d');
            $shortDate = $dt->format('d M');
            
            $salesSum = $monthlySales->filter(function ($t) use ($dateString) {
                return $t->created_at->format('Y-m-d') === $dateString;
            })->sum('total_amount');

            $purchaseSum = $monthlyPurchases->filter(function ($p) use ($dateString) {
                return $p->created_at->format('Y-m-d') === $dateString;
            })->sum('total_cost');

            $chartLabels[] = $shortDate;
            $chartSalesData[] = $salesSum;
            $chartPurchaseData[] = $purchaseSum;
            $chartProfitData[] = $salesSum - $purchaseSum;
        }

        $this->dispatch('update-chart', 
            labels: $chartLabels, 
            salesData: $chartSalesData,
            purchaseData: $chartPurchaseData,
            profitData: $chartProfitData
        );
    }

    public function toggleView()
    {
        $this->showPurchaseReport = !$this->showPurchaseReport;
    }

    #[Layout('components.layouts.admin')]
    public function render()
    {
        //  FIX 1: Pastikan tipe data jadi INTEGER (Angka murni)
        $bulan = (int) $this->month; 
        $tahun = (int) $this->year;

        // 1. Data Tabel dengan search dan filter
        $dailyTransactions = Transaction::whereDate('created_at', $this->date)
                        ->when($this->search, function($query) {
                            $query->where('invoice_no', 'like', '%' . $this->search . '%');
                        })
                        ->when($this->hourFilter, function($query) {
                            $query->whereRaw('HOUR(created_at) = ?', [$this->hourFilter]);
                        })
                        ->latest()
                        ->get();

        // 2. LOGIC GRAFIK BULANAN
        $monthlySales = Transaction::whereMonth('created_at', $bulan)
                        ->whereYear('created_at', $tahun)
                        ->get();

        $monthlyPurchases = StockPurchase::whereMonth('created_at', $bulan)
                        ->whereYear('created_at', $tahun)
                        ->get();

        $grandTotal = $monthlySales->sum('total_amount');
        $totalBon = $monthlySales->count();
        $totalPurchase = $monthlyPurchases->sum('total_cost');
        $profit = $grandTotal - $totalPurchase;

        // 3. Siapkan Data Grafik
        $start = Carbon::createFromDate($tahun, $bulan, 1); 
        $end = $start->copy()->endOfMonth();
        
        $period = CarbonPeriod::create($start, $end);
        
        $chartLabels = [];
        $chartSalesData = [];
        $chartPurchaseData = [];
        $chartProfitData = [];

        foreach ($period as $dt) {
            $dateString = $dt->format('Y-m-d');
            $shortDate = $dt->format('d M');
            
            $salesSum = $monthlySales->filter(function ($t) use ($dateString) {
                return $t->created_at->format('Y-m-d') === $dateString;
            })->sum('total_amount');

            $purchaseSum = $monthlyPurchases->filter(function ($p) use ($dateString) {
                return $p->created_at->format('Y-m-d') === $dateString;
            })->sum('total_cost');

            $chartLabels[] = $shortDate;
            $chartSalesData[] = $salesSum;
            $chartPurchaseData[] = $purchaseSum;
            $chartProfitData[] = $salesSum - $purchaseSum;
        }

        // 4. Laporan Pembelian Stok
        $stockPurchases = StockPurchase::with(['product' => function($query) {
                            $query->withTrashed(); // Include soft deleted products
                        }, 'user'])
                        ->whereDate('created_at', $this->date)
                        ->latest()
                        ->get();
        
        $totalPurchaseCost = $stockPurchases->sum('total_cost');

        // 5. Laporan Per Produk (Gabungan Penjualan & Pembelian)
        $productReport = Product::withTrashed()
            ->with(['transactionDetails' => function($query) use ($bulan, $tahun) {
                $query->whereHas('transaction', function($q) use ($bulan, $tahun) {
                    $q->whereMonth('created_at', $bulan)
                      ->whereYear('created_at', $tahun);
                });
            }, 'stockPurchases' => function($query) use ($bulan, $tahun) {
                $query->whereMonth('created_at', $bulan)
                      ->whereYear('created_at', $tahun);
            }])
            ->get()
            ->map(function($product) {
                $totalSold = $product->transactionDetails->sum('qty');
                $totalRevenue = $product->transactionDetails->sum('subtotal');
                $totalPurchased = $product->stockPurchases->sum('quantity');
                $totalCost = $product->stockPurchases->sum('total_cost');
                $productProfit = $totalRevenue - $totalCost;
                
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'category' => $product->category,
                    'is_deleted' => $product->trashed(),
                    'total_sold' => $totalSold,
                    'total_revenue' => $totalRevenue,
                    'total_purchased' => $totalPurchased,
                    'total_cost' => $totalCost,
                    'profit' => $productProfit,
                    'profit_margin' => $totalRevenue > 0 ? ($productProfit / $totalRevenue) * 100 : 0,
                ];
            })
            ->filter(function($item) {
                // Hanya tampilkan produk yang ada transaksi
                return $item['total_sold'] > 0 || $item['total_purchased'] > 0;
            })
            ->sortByDesc('profit')
            ->values();

        return view('livewire.admin.report', [
            'transactions' => $dailyTransactions,
            'grandTotal' => $grandTotal, 
            'totalBon' => $totalBon,
            'totalPurchase' => $totalPurchase,
            'profit' => $profit,
            'chartLabels' => $chartLabels,
            'chartSalesData' => $chartSalesData,
            'chartPurchaseData' => $chartPurchaseData,
            'chartProfitData' => $chartProfitData,
            'stockPurchases' => $stockPurchases,
            'totalPurchaseCost' => $totalPurchaseCost,
            'productReport' => $productReport
        ]);
    }
}