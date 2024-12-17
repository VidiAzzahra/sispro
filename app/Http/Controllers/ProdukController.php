<?php

namespace App\Http\Controllers;

use DataTables;
use App\Models\Stok;
use App\Models\Produk;
use App\Models\Kategori;
use Illuminate\Http\Request;
use App\Traits\JsonResponder;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Validator;

class ProdukController extends Controller
{
    use JsonResponder;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $produks = Produk::all();
            if ($request->mode == "datatable") {
                return DataTables::of($produks)
                    ->addColumn('action', function ($produk) {
                        $detailButton = '<a href="produk/detail/'.$produk->id.'" class="btn btn-sm btn-info d-inline-flex  align-items-baseline  mr-1" ><i class="far fa-file mr-1"></i>Edit</a href="' . route('produk.show', $produk->id) . '">';
                        $editButton = '<button class="btn btn-sm btn-warning d-inline-flex  align-items-baseline  mr-1" onclick="getModal(`createModal`, `/admin/kategori/' . $produk->id . '`, [`id`, `nama`])"><i class="fas fa-edit mr-1"></i>Edit</button>';
                        $deleteButton = '<button class="btn btn-sm btn-danger d-inline-flex  align-items-baseline " onclick="confirmDelete(`/admin/kategori/' . $produk->id . '`, `category-table`)"><i class="fas fa-trash mr-1"></i>Hapus</button>';
                        return $detailButton . $editButton . $deleteButton;
                    })
                    ->addColumn('kategori', function ($produk) {
                        return $produk->kategori->nama;
                    })
                    ->addIndexColumn('')
                    ->rawColumns(['action','kategori'])
                    ->make(true);
            }

            return $this->successResponse($produks, 'Data produk ditemukan.');
        }

        return view('pages.produk.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|min:4',
            'kategori_id' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 'Data tidak valid.', 422);
        }

        $produk = Produk::create([
            'kategori_id' => $request->kategori_id,
            'nama' => $request->nama,
            'stok' => $request->stok ? $request->stok : 0 ,
        ]);

        return $this->successResponse($produk, 'Data produk Disimpan!', 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|min:4',
            'kategori_id' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 'Data tidak valid.', 422);
        }
        $produk = Produk::find($id);

        if (!$produk) {
            return $this->errorResponse(null, 'Data produk Tidak Ada!');
        }

        $produk->update($request->only('nama'));

        return $this->successResponse($produk, 'Data produk Diubah!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $produk = Produk::find($id);

        if (!$produk) {
            return $this->errorResponse(null, 'Data produk Tidak Ada!');
        }

        $produk->delete();

        return $this->successResponse(null, 'Data produk Dihapus!');
    }
    public function show($id)
    {
        if ($id == "excel") {
            ob_end_clean();
            ob_start();
            return Excel::download(new CategoryExport(), 'Produk'. date('Y-m-d H:i:s') .'.xlsx');
        } elseif ($id == 'pdf') {
            $produks = Produk::all();
            $pdf = PDF::loadView('pages.produk.pdf', compact('Kategori'));

            $options = [
                'margin_top' => 20,
                'margin_right' => 20,
                'margin_bottom' => 20,
                'margin_left' => 20,
            ];

            $pdf->setOptions($options);
            $pdf->setPaper('a4', 'landscape');

            $namaFile = 'produk'. date('Y-m-d H:i:s') .'.pdf';

            ob_end_clean();
            ob_start();
            return $pdf->stream($namaFile);
        } else {
            $produk = Produk::find($id);

            if (!$produk) {
                return $this->errorResponse(null, 'Data produk tidak ditemukan.', 404);
            }

            return $this->successResponse($produk, 'Data produk ditemukan.');
        }
    }
    public function detail($id, Request $request)
    {
        $produk = Produk::find($id);
        if ($request->ajax()) {
            $stoks = Stok::with('produk')->where('produk_id', $id)->get();
            if ($request->mode == "datatable") {
                return DataTables::of($stoks)
                    ->addColumn('action', function ($stok) {
                        $editButton = '<button class="btn btn-sm btn-warning d-inline-flex  align-items-baseline  mr-1" onclick="getModal(`createModalStok`, `/admin/stok/' . $stok->id . '`, [`id`, `tanggal`,`tipe`,`stok`,`keterangan`,`produk_id`])"><i class="fas fa-edit mr-1"></i>Edit</button>';
                        $deleteButton = '<button class="btn btn-sm btn-danger d-inline-flex  align-items-baseline " onclick="confirmDelete(`/admin/stok/' . $stok->id . '`, `tabel-stok`)"><i class="fas fa-trash mr-1"></i>Hapus</button>';
                        return $editButton . $deleteButton;
                    })
                    ->addColumn('tipe', function ($stok) {
                        if ($stok->tipe == 'masuk') {
                            return '<span class="badge badge-success">Masuk</span>';
                        } else {
                            return '<span class="badge badge-danger">Keluar</span>';
                        }
                    })
                    ->addIndexColumn('')
                    ->rawColumns(['action','tipe'])
                    ->make(true);
            }

            return $this->successResponse($stoks, 'Data stok ditemukan.');
        }

        return view('pages.produk.detail', compact('produk'));
    }
}
