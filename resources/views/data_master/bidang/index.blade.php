@extends('layout.app')

@section('title')
    Data Bidang
@endsection

@section('head')
    <link rel="stylesheet" href="{{ asset('assets/css/dataTables.bootstrap5.min.css') }}">
    <script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/dataTables.bootstrap5.min.js') }}"></script>
@endsection

@section('content')
    <div id="layout-wrapper">
        @include('layout.header')
        @include('layout.sidebar')
        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">
                    <!-- start page title -->
                    <div class="row mx-2">
                        <div class="col-12">
                            <div class="page-title-box d-flex align-items-center justify-content-between">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item"><a href="javascript: void(0);">Data Master</a></li>
                                    <li class="breadcrumb-item active">Bidang</li>
                                </ol>
                            </div>
                        </div>
                    </div><!-- end page title -->

                    <div class="row bg-white rounded-3 pb-3 mb-3 mx-2">
                        <div class="page-title-box bg-light-subtle rounded-3 d-flex align-items-center justify-content-between px-3 py-2">
                            <h5>List Data Bidang</h5>
                            <button data-bs-toggle="modal" data-bs-target="#tambahbidangbaru" class="btn btn-primary">Tambah Data</button>
                        </div>
                        <div class="container-fluid table-responsive px-3 py-3">
                            <table class="table table-striped" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Nama Bidang</th>   
                                        <th>Aksi</th>                            
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($bidang as $data)
                                        <tr>
                                            <td>{{ $data->nama_bidang }}</td>      
                                            <td>
                                                <div class="d-flex">
                                                    <a role="button" class="btn btn-warning me-2" title="Ubah Data" style="padding: 0.25rem 0.5rem; font-size: 18px;" data-bs-toggle="modal" data-bs-target="#modalUbah" data-bs-id="{{ $data->id_bidang }}"><i class="bx bx-pencil"></i></a>
                                                    <form method="POST" action="{{ route('hapus_bidang', $data->id_bidang) }}" id="hapus-bidang-{{ $data->id_bidang }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <a role="button" id="btnHps-{{ $data->id_bidang }}" class="btn btn-danger" title="Hapus Data"style="padding: 0.25rem 0.5rem; font-size: 18px;"><i class="bx bx-trash-alt"></i></a>
                                                    </form>
                                                </div>
                                                <script>
                                                    $('#btnHps-{{ $data->id_bidang }}').click(function(event){
                                                        event.preventDefault();
                                                        Swal.fire({
                                                            icon: "info",
                                                            title: "Konfirmasi",
                                                            text: "Apakah Anda yakin ingin menghapus data ini?",
                                                            showCancelButton: true,
                                                            confirmButtonText: "Ya, Lanjutkan",
                                                            cancelButtonText: "Tidak, Batalkan",
                                                        }).then(function (result) {
                                                            if (result.isConfirmed) {
                                                                $('#hapus-bidang-{{ $data->id_bidang }}').submit();
                                                            }
                                                        });
                                                    });
                                                </script>
                                            </td>                              
                                        </tr> 
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div> <!-- container-fluid -->
            </div>
            @include('layout.footer')
        </div>
    </div>
@endsection

@section('modal')
    <div class="modal fade" id="tambahbidangbaru" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Form Tambah Data Bidang</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('tambah_bidang') }}"> 
                    @csrf
                    <div class="modal-body">
                        <div class="form-floating">
                            <input type="name" class="form-control" id="floatingInputValue" placeholder=" " name="nama_bidang">
                            <label for="floatingInputValue" id="nama_bidang" name="nama_bidang">Nama Bidang</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Tambah</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalUbah" tabindex="-1" aria-labelledby="modalUbahLabel" aria-hidden="true">
        <div class="modal-dialog modal-s">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalUbahLabel">Ubah Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <!-- Form untuk mengubah data -->
                <form method="POST" action="{{ route('ubah_data_bidang') }}" id="form-bid">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id_bidang" id="id_bidang" required>
                    <div class="modal-body">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="ubah_nama_bidang" placeholder=" " name="ubah_nama_bidang">
                            <label for="ubah_nama_bidang" id="ubah_nama_bidang">Nama Bidang</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-warning" id="simpanPerubahan">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('.table').DataTable({
                columnDefs: [
                    { orderable: false, targets: [1] }
                ],
                language: {
                    lengthMenu: "Tampilkan _MENU_ data per halaman",
                    zeroRecords: "Data tidak ditemukan.",
                    info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                    infoEmpty: "Menampilkan 0 - 0 dari 0 data",
                    infoFiltered: "(difilter dari _MAX_ total data)",
                    search: "Cari",
                    decimal: ",",
                    thousands: ".",
                    paginate: {
                        previous: "Sebelumnya",
                        next: "Selanjutnya"
                    }
                }
            });
        });

        $('#modalUbah').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget)
            $.ajax({
                url: '{{ route("get_data_bidang") }}',
                type: 'POST',
                data: {
                    id: button.data('bs-id'),
                    _token: '{{ csrf_token() }}',
                },
                dataType: 'JSON',
                success: function(response) {
                    if (response.status == 'success') {
                        var bidang = response.bidang; // Mengambil data bidang dari respons
                        // Mengisi bidang formulir dengan data yang diterima
                        $("#id_bidang").val(bidang.id_bidang);
                        $("#ubah_nama_bidang").val(bidang.nama_bidang);
                    } else if (response.status == 'error') {
                        $("#ubah_nama_bidang").val("");
                    }
                    $('#btn-submit').on('click', function(e) {
                        e.preventDefault();
                        // Action submit ada di dieu. <-- READ ME
                    }); // {{-- Submit Button --}}
                }, // {{-- Ajax Success --}}
            }); // {{-- Ajax Function --}}
            $(document).ready(function() {
                $('#simpanPerubahan').click(function(event){
                    event.preventDefault(); // Mencegah pengiriman formulir secara default
                    var namaBidang = document.getElementById("ubah_nama_bidang");
                    if (!namaBidang.value) {
                        // Tampilkan pesan kesalahan jika ada input yang kosong
                        Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: "Semua inputan wajib diisi!",
                        });
                    } else {
                        // Tampilkan pesan konfirmasi jika semua input telah diisi
                        Swal.fire({
                            icon: "info",
                            title: "Konfirmasi",
                            text: "Apakah Anda yakin data sudah benar?",
                            showCancelButton: true,
                            confirmButtonText: "Ya, Lanjutkan",
                            cancelButtonText: "Tidak, Batalkan",
                        }).then(function (result) {
                            if (result.isConfirmed) {
                                // Jika pengguna mengonfirmasi, lanjutkan dengan pengiriman formulir
                                $('#form-bid').submit();
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection