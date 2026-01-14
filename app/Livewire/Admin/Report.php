<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Transaction;
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
        // Kita hitung ulang chart data disini (copas logic dari render)
        $bulan = (int) $this->month;
        $tahun = (int) $this->year;
        
        $start = Carbon::createFromDate($tahun, $bulan, 1);
        $end = $start->copy()->endOfMonth();
        $period = CarbonPeriod::create($start, $end);
        
        $monthlySales = Transaction::whereMonth('created_at', $bulan)
                        ->whereYear('created_at', $tahun)
                        ->get();

        $chartLabels = [];
        $chartData = [];

        foreach ($period as $dt) {
            $dateString = $dt->format('Y-m-d');
            $shortDate = $dt->format('d M');
            
            $sum = $monthlySales->filter(function ($t) use ($dateString) {
                return $t->created_at->format('Y-m-d') === $dateString;
            })->sum('total_amount');

            $chartLabels[] = $shortDate;
            $chartData[] = $sum;
        }

        $this->dispatch('update-chart', labels: $chartLabels, data: $chartData);
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
        $monthlySales = Transaction::whereMonth('created_at', $bulan) // Pake variabel $bulan yg udah di-cast
                        ->whereYear('created_at', $tahun)
                        ->get();

        $grandTotal = $monthlySales->sum('total_amount');
        $totalBon = $monthlySales->count();

        // 3. Siapkan Data Grafik
        //  FIX 2: Di sini biang kerok errornya tadi, sekarang pake $bulan (int)
        $start = Carbon::createFromDate($tahun, $bulan, 1); 
        $end = $start->copy()->endOfMonth();
        
        $period = CarbonPeriod::create($start, $end);
        
        $chartLabels = [];
        $chartData = [];

        foreach ($period as $dt) {
            $dateString = $dt->format('Y-m-d');
            $shortDate = $dt->format('d M');
            
            $sum = $monthlySales->filter(function ($t) use ($dateString) {
                return $t->created_at->format('Y-m-d') === $dateString;
            })->sum('total_amount');

            $chartLabels[] = $shortDate;
            $chartData[] = $sum;
        }

        return view('livewire.admin.report', [
            'transactions' => $dailyTransactions,
            'grandTotal' => $grandTotal, 
            'totalBon' => $totalBon,
            'chartLabels' => $chartLabels,
            'chartData' => $chartData
        ]);
    }
}