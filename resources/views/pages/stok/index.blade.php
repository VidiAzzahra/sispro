@extends('layouts.app')

@section('title', 'Stok')

@push('style')
    <link rel="stylesheet" href="{{ asset('library/datatables/datatables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/datatables/Select-1.2.4/css/select.bootstrap4.min.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>@yield('title')</h1>
            </div>
            <div class="section-body">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title font-weight-bolder">
                            Data @yield('title')
                        </div>
                        <div class="ml-auto">
                            <a href="{{ route('stok.show', 'pdf') }}" class="btn btn-sm px-3 btn-danger mr-1"
                                target="_blank"><i class="fas fa-file-pdf mr-2"></i>Pdf</a>
                            <a href="{{ route('stok.show', 'excel') }}" class="btn btn-sm px-3 btn-info" target="_blank"><i
                                    class="fas fa-file-excel mr-2"></i>Excel</a>
                            <button class="btn btn-success" onclick="getModal('createModal')"><i
                                    class="fas fa-plus mr-2"></i>Tambah</button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">

                            {{-- filtering --}}
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <label for="filter_tahun">Filter Tahun</label>
                                    <select id="filter_tahun" class="form-control">
                                        <option value="">Pilih Tahun</option>
                                        @for ($year = date('Y'); $year >= 2000; $year--)
                                            <option value="{{ $year }}">{{ $year }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="filter_bulan">Filter Bulan</label>
                                    <select id="filter_bulan" class="form-control">
                                        <option value="">Pilih Bulan</option>
                                        @for ($month = 1; $month <= 12; $month++)
                                            <option value="{{ $month }}">{{ date('F', mktime(0, 0, 0, $month, 1)) }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="filter_tanggal">Filter Tanggal</label>
                                    <input type="date" id="filter_tanggal" class="form-control">
                                </div>
                            </div>


                            {{-- end filtering  --}}

                        </div>


                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="tabel-stok" width="100%">
                                <thead>
                                    <tr>
                                        <th scope="col" width="5%">#</th>
                                        <th scope="col">Tanggal</th>
                                        <th scope="col">Produk</th>
                                        <th scope="col">Tipe</th>
                                        <th scope="col">Stok</th>
                                        <th scope="col">Keterangan</th>
                                        <th scope="col" width="20%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    @include('pages.stok.modal')
@endsection

@push('scripts')
    <script src="{{ asset('library/sweetalert/dist/sweetalert.min.js') }}"></script>
    <script src="{{ asset('library/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('library/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('library/datatables/Select-1.2.4/js/dataTables.select.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            // Inisialisasi DataTable
            let table = $('#tabel-stok').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('stok.index') }}',
                    data: function(d) {
                        d.tahun = $('#filter_tahun').val(); // Kirim filter tahun
                        d.bulan = $('#filter_bulan').val(); // Kirim filter bulan
                        d.tanggal = $('#filter_tanggal').val(); // Kirim filter tanggal
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'tanggal',
                        name: 'tanggal'
                    },
                    {
                        data: 'produk',
                        name: 'produk'
                    },
                    {
                        data: 'tipe',
                        name: 'tipe'
                    },
                    {
                        data: 'stok',
                        name: 'stok'
                    },
                    {
                        data: 'keterangan',
                        name: 'keterangan'
                    },
                    {
                        data: 'action',
                        name: 'action'
                    },
                ],
                order: [
                    [1, 'desc']
                ],
                responsive: true,
                autoWidth: false
            });

            // Event ketika filter berubah
            $('#filter_tahun, #filter_bulan, #filter_tanggal').change(function() {
                table.ajax.reload(null, false); // Reload data tanpa reload halaman
            });
        });



        $(document).ready(function() {
            // datatableCall('tabel-stok', '{{ route('stok.index') }}', [{
            //         data: 'DT_RowIndex',
            //         name: 'DT_RowIndex'
            //     },
            //     {
            //         data: 'tanggal',
            //         name: 'tanggal'
            //     },
            //     {
            //         data: 'produk',
            //         name: 'produk'
            //     },
            //     {
            //         data: 'tipe',
            //         name: 'tipe'
            //     },
            //     {
            //         data: 'stok',
            //         name: 'stok'
            //     },
            //     {
            //         data: 'keterangan',
            //         name: 'keterangan'
            //     },
            //     {
            //         data: 'action',
            //         name: 'action'
            //     },
            // ]);

            // $('#filter_tahun, #filter_bulan').change(function() {
            //     const tahun = $('#filter_tahun').val();
            //     const bulan = $('#filter_bulan').val();
            //     table.ajax.url(`{{ route('stok.index') }}?tahun=${tahun}&bulan=${bulan}`).load();
            // });

            select2ToJson("#produk_id", "{{ route('produk.index') }}", "#createModal");

            $("#saveData").submit(function(e) {
                setButtonLoadingState("#saveData .btn.btn-success", true);
                e.preventDefault();
                const kode = $("#saveData #id").val();
                let url = "{{ route('stok.store') }}";
                const data = new FormData(this);

                if (kode !== "") {
                    data.append("_method", "PUT");
                    url = `/admin/produk/${kode}`;
                }

                const successCallback = function(response) {
                    setButtonLoadingState("#saveData .btn.btn-success", false);
                    handleSuccess(response, "tabel-stok", "createModal");
                };

                const errorCallback = function(error) {
                    setButtonLoadingState("#saveData .btn.btn-success", false);
                    handleValidationErrors(error, "saveData", ["nama"]);
                };

                ajaxCall(url, "POST", data, successCallback, errorCallback);
            });
        });
    </script>
@endpush
