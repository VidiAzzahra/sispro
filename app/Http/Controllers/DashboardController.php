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
            // mengambil parameter bulan, tahun
            $bulan = $request->bulan;
            $tahun = $request->tahun;
            $startDate = Carbon::create($tahun, $bulan, 1)->startOfMonth();
            $endDate = Carbon::create($tahun, $bulan, 1)->endOfMonth();

            // mengambil data stok tipe masuk
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

            // mengambil data stok tipe keluar
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

            // menghitung jumlah stok masuk dan keluar
            $dates = $startDate->copy();
            while ($dates <= $endDate) {
                $dateString = $dates->toDateString();
                $labels[] = formatTanggal($dateString, 'd'); // Format tanggal (misalnya '01', '02')
                $stokMasuk[] = $dataStokMasuk[$dateString] ?? 0;
                $stokKeluar[] = $dataStokKeluar[$dateString] ?? 0;
                $dates->addDay();
            }

            // mengambalikan respons dan data
            return $this->successResponse([
                'labels' => $labels,
                'stokMasuk' => $stokMasuk,
                'stokKeluar' => $stokKeluar,
            ], 'Stok masuk dan keluar ditemukan.');
        }

        // menghitung jumlah kategori dan produk
        $kategori = Kategori::count();
        $produk = Produk::count();

        // mengembalikan view dashboard dengan jumlah kategori dan produk
        return view('pages.dashboard.index', compact('kategori', 'produk'));
    }
}
