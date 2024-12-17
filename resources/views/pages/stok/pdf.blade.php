@extends('layouts.pdf')

@section('title', 'Stok')

@push('style')
@endpush

@section('main')
    <div>
        <center>
            <u>
                <h3>Data Stok</h3>
            </u>
        </center>
        <br>
        <table width="100%" border="1" cellpadding="2.5" cellspacing="0" style="text-align: center">
            <thead>
                <tr>
                    <th width="3%">No</th>
                    <th width="10%">Produk</th>
                    <th width="20%">Tanggal</th>
                    <th width="15%">Jenis</th>
                    <th width="17%">Stok</th>
                    <th width="15%">Keterangan</th>
                </tr>
            </thead>
            <tbody valign="top">
                @php
                    $total = 0;
                @endphp
                @forelse ($stoks as $stok)
                    <tr>
                        <td style="text-align: center;">{{ $loop->iteration }}</td>
                        <td>{{ $stok->produk->nama }}</td>
                        <td>{{ $stok->formatted_tanggal }}</td>
                        <td>{{ ucfirst($stok->tipe) }}</td>
                        <td>{{ $stok->stok }}</td>
                        @php
                            if ($stok->tipe == 'masuk') {
                                $total += $stok->stok;
                            } else {
                                $total -= $stok->stok;
                            }
                        @endphp
                        <td>{{ $stok->keterangan }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" align="center">Data @yield('title') Kosong</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection

@push('scripts')
@endpush
