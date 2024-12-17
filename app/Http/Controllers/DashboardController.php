<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Produk;
use App\Models\Stok;
use App\Traits\JsonResponder;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    use JsonResponder;

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $bulan = $request->bulan;
            $tahun = $request->tahun;
            $startDate = Carbon::create($tahun, $bulan, 1)->startOfMonth();
            $endDate = Carbon::create($tahun, $bulan, 1)->endOfMonth();

            $dataStokMasuk = Stok::where('tipe', 'masuk')
                ->whereBetween('tanggal', [$startDate, $endDate])
                ->groupBy('date')
                ->orderBy('date')
                ->get([
                    DB::raw('DATE(tanggal) as date'),
                    DB::raw('SUM(stok) as total_stok'),
                ])
                ->mapWithKeys(function ($item) {
                    return [$item->date => $item->total_stok];
                })->toArray();

            $dataStokKeluar = Stok::where('tipe', 'keluar')
                ->whereBetween('tanggal', [$startDate, $endDate])
                ->groupBy('date')
                ->orderBy('date')
                ->get([
                    DB::raw('DATE(tanggal) as date'),
                    DB::raw('SUM(stok) as total_stok'),
                ])
                ->mapWithKeys(function ($item) {
                    return [$item->date => $item->total_stok];
                })->toArray();

            $labels = [];
            $stokMasuk = [];
            $stokKeluar = [];

            $dates = $startDate->copy();
            while ($dates <= $endDate) {
                $dateString = $dates->toDateString();
                $labels[] = formatTanggal($dateString, 'd'); // Format tanggal (misalnya '01', '02')
                $stokMasuk[] = $dataStokMasuk[$dateString] ?? 0;
                $stokKeluar[] = $dataStokKeluar[$dateString] ?? 0;
                $dates->addDay();
            }

            return $this->successResponse([
                'labels' => $labels,
                'stokMasuk' => $stokMasuk,
                'stokKeluar' => $stokKeluar,
            ], 'Stok masuk dan keluar ditemukan.');
        }

        $kategori = Kategori::count();
        $produk = Produk::count();

        return view('pages.dashboard.index', compact('kategori', 'produk'));
    }
}
