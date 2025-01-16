<?php

namespace App\Http\Controllers;

use DataTables;
use Carbon\Carbon;
use App\Models\Stok;
use App\Models\Produk;
use Illuminate\Http\Request;
use App\Traits\JsonResponder;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Validator;

class StokController extends Controller
{
    use JsonResponder;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            // Ambil parameter filter
            $tahun = $request->input('tahun');
            $bulan = $request->input('bulan');
            $tanggal = $request->input('tanggal');

            $stoks = Stok::with('produk')
                ->when($tahun, function ($query) use ($tahun) {
                    return $query->whereYear('tanggal', $tahun);
                })
                ->when($bulan, function ($query) use ($bulan) {
                    return $query->whereMonth('tanggal', $bulan);
                })
                ->when($tanggal, function ($query) use ($tanggal) {
                    return $query->whereDate('tanggal', $tanggal);
                })
                ->get();

            return DataTables::of($stoks)
                ->addColumn('action', function ($stok) {
                    return '<button class="btn btn-sm btn-warning">Edit</button>';
                })
                ->addColumn('tipe', function ($stok) {
                    return $stok->tipe == 'masuk'
                        ? '<span class="badge badge-success">Masuk</span>'
                        : '<span class="badge badge-danger">Keluar</span>';
                })
                ->addColumn('produk', function ($stok) {
                    return $stok->produk->nama;
                })
                ->addIndexColumn()
                ->rawColumns(['action', 'tipe'])
                ->make(true);
        }

        return view('pages.stok.index');
    }




    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tanggal' => 'required|date',
            'tipe' => 'required|in:masuk,keluar',
            'produk_id' => 'required|exists:produks,id',
            'stok' => 'required|numeric',
            'keterangan' => 'nullable',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 'Data tidak valid.', 422);
        }

        $cekStokProduk = Produk::find($request->produk_id);
        if ($cekStokProduk->stok < $request->stok) {
            return $this->errorResponse(null, 'Stok Tidak Cukup!');
        }

        $stok = Stok::create([
            'tanggal' => $request->tanggal,
            'produk_id' => $request->produk_id,
            'tipe' => $request->tipe,
            'stok' => $request->stok,
            'keterangan' => $request->keterangan,
        ]);

        return $this->successResponse($stok, 'Data stok Disimpan!', 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'tanggal' => 'required|date',
            'tipe' => 'required|in:masuk,keluar',
            'produk_id' => 'required|exists:produks,id',
            'stok' => 'required|numeric',
            'keterangan' => 'nullable',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 'Data tidak valid.', 422);
        }
        $stok = Stok::find($id);

        if (!$stok) {
            return $this->errorResponse(null, 'Data stok Tidak Ada!');
        }

        $stok->update([
            'tanggal' => $request->tanggal,
            'produk_id' => $request->produk_id,
            'tipe' => $request->tipe,
            'stok' => $request->stok,
            'keterangan' => $request->keterangan,
        ]);

        return $this->successResponse($stok, 'Data stok Diubah!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $stok = Stok::find($id);

        if (!$stok) {
            return $this->errorResponse(null, 'Data stok Tidak Ada!');
        }

        $stok->delete();

        return $this->successResponse(null, 'Data stok Dihapus!');
    }
    public function show($id, Request $request)
    {
        if ($id == "excel") {
            ob_end_clean();
            ob_start();
            return Excel::download(new CategoryExport(), 'Produk' . date('Y-m-d H:i:s') . '.xlsx');
        } elseif ($id == 'pdf') {
            $tahun = $request->input('tahun');
            $bulan = $request->input('bulan');
            $tanggal = $request->input('tanggal');

            $stoks = Stok::with('produk')
                ->when($tahun, function ($query) use ($tahun) {
                    return $query->whereYear('tanggal', $tahun);
                })
                ->when($bulan, function ($query) use ($bulan) {
                    return $query->whereMonth('tanggal', $bulan);
                })
                ->when($tanggal, function ($query) use ($tanggal) {
                    return $query->whereDate('tanggal', $tanggal);
                })
                ->get()
                ->map(function ($stok) {
                    $stok->formatted_tanggal = Carbon::parse($stok->tanggal)
                        ->locale('id')
                        ->translatedFormat('l, d F Y');
                    return $stok;
                });

            // Generate PDF
            $pdf = PDF::loadView('pages.stok.pdf', compact('stoks'));

            $options = [
                'margin_top' => 20,
                'margin_right' => 20,
                'margin_bottom' => 20,
                'margin_left' => 20,
            ];

            $pdf->setOptions($options);
            $pdf->setPaper('a4', 'landscape');

            $namaFile = 'stok' . date('Y-m-d H:i:s') . '.pdf';

            ob_end_clean();
            ob_start();
            return $pdf->stream($namaFile);
        } else {
            $stok = Stok::find($id);

            if (!$stok) {
                return $this->errorResponse(null, 'Data stok tidak ditemukan.', 404);
            }

            return $this->successResponse($stok, 'Data stok ditemukan.');
        }
    }

}
