@extends('layout.app')

@section('title')
    Data Pegawai
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
                                    <li class="breadcrumb-item active">Pegawai</li>
                                </ol>
                            </div>
                        </div>
                    </div><!-- end page title -->

                    <div class="row bg-white rounded-3 pb-3 mb-3 mx-2">
                        <div class="page-title-box bg-light-subtle rounded-3 d-flex align-items-center justify-content-between px-3 py-2">
                            <h5>List Data Pegawai</h5>
                            <button data-bs-toggle="modal" data-bs-target="#tambahpegawaibaru" class="btn btn-primary">Tambah Data</button>
                        </div>
                        <div class="container-fluid table-responsive px-3 py-3">
                            <table class="table table-striped" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>NIP</th>
                                        <th>Nama</th>
                                        <th>Bidang</th>
                                        <th>Jabatan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pegawai as $data)
                                        <tr>
                                            <td>{{ $data->nip }}</td>
                                            <td>{{ $data->nama_pegawai }}</td>
                                            <td>{{ $data->nama_bidang }}</td>
                                            <td>{{ $data->nama_jabatan }}</td>
                                            <td>
                                                <div class="d-flex">
                                                    <a role="button" class="btn btn-warning me-2" title="Ubah Data" style="padding: 0.25rem 0.5rem; font-size: 18px;" data-bs-toggle="modal" data-bs-target="#modalUbah" data-bs-id="{{ $data->nip }}"><i class="bx bx-pencil"></i></a>
                                                    <form method="POST" action="{{ route('hapus_pegawai', $data->nip) }}" id="hapus-pegawai-{{ $data->nip }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <a role="button" id="btnHps-{{ $data->nip }}" class="btn btn-danger" title="Hapus Data"style="padding: 0.25rem 0.5rem; font-size: 18px;"><i class="bx bx-trash-alt"></i></a>
                                                    </form>
                                                </div>
                                                <script>
                                                    $('#btnHps-{{ $data->nip }}').click(function(event){
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
                                                                $('#hapus-pegawai-{{ $data->nip }}').submit();
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
    <div class="modal fade" id="tambahpegawaibaru" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Form Tambah Data Pegawai</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('tambah_pegawai') }}"> 
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nip" class="col-form-label" name="nip">NIP - (Pastikan input dengan tepat! NIP tidak dapat diubah!)</label>
                            <input type="text" class="form-control" id="nip" name="nip" required>
                        </div> 
                        <div class="mb-3">
                            <label for="nama_pegawai" class="col-form-label" name="nama_pegawai">Nama</label>
                            <input type="text" class="form-control" id="nama_pegawai" name="nama_pegawai" required>
                        </div>
                        <div class="mb-3">
                            <label for="nama_bidang" class="col-form-label">Bidang</label>
                            <select class="form-select" id="nama_bidang" name="nama_bidang" required>
                                <option value="" selected hidden>-- Pilih Bidang --</option>
                                    @foreach ($bidang as $data)
                                        <option value="{{ $data->nama_bidang }}">{{ $data->nama_bidang }}</option>
                                    @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="nama_jabatan" class="col-form-label">Jabatan</label>
                            <select class="form-select" id="nama_jabatan" name="nama_jabatan" required>
                                <option value="" selected hidden>-- Pilih Jabatan --</option>
                                    @foreach ($jabatan as $data)
                                        <option value="{{ $data->nama_jabatan }}">{{ $data->nama_jabatan }}</option>
                                    @endforeach
                            </select>
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
                <form method="POST" action="{{ route('ubah_data_pegawai') }}" id="form-peg">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="nip" id="nip2" required>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="ubah_nip" class="col-form-label" name="ubah_nip">NIP</label>
                            <input type="text" class="form-control" id="ubah_nip" name="ubah_nip" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="ubah_nama_pegawai" class="col-form-label" name="ubah_nama_pegawai">Nama</label>
                            <input type="text" class="form-control" id="ubah_nama_pegawai" name="ubah_nama_pegawai" required>
                        </div>
                        <div class="mb-3">
                            <label for="nama_bidang2" class="col-form-label">Bidang</label>
                            <select class="form-select" id="nama_bidang2" name="nama_bidang" required>
                                <option value="" selected hidden>-- Pilih Bidang --</option>
                                    @foreach ($bidang as $data)
                                        <option value="{{ $data->nama_bidang }}">{{ $data->nama_bidang }}</option>
                                    @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="nama_jabatan2" class="col-form-label">Jabatan</label>
                            <select class="form-select" id="nama_jabatan2" name="nama_jabatan" required>
                                <option value="" selected hidden>-- Pilih Jabatan --</option>
                                    @foreach ($jabatan as $data)
                                        <option value="{{ $data->nama_jabatan }}">{{ $data->nama_jabatan }}</option>
                                    @endforeach
                            </select>
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
                    { orderable: false, targets: [4] }
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
                url: '{{ route("get_data_pegawai") }}',
                type: 'POST',
                data: {
                    id: button.data('bs-id'),
                    _token: '{{ csrf_token() }}',
                },
                dataType: 'JSON',
                success: function(response) {
                    if (response.status == 'success') {
                        var jabatan = response.jabatan; // Ambil data json jabatan
                        var bidang = response.bidang; // Ambil data json bidang
                        $("#nip2").val(response.nip);
                        $("#ubah_nip").val(response.nip);
                        $("#ubah_nama_pegawai").val(response.nama);
                        $("#nama_bidang2").val(bidang.nama_bidang);
                        var firstOption = $('<option>', {
                            value: bidang.nama_bidang,
                            text: bidang.nama_bidang,
                            selected: true,
                            hidden: true,
                        });
                        $("#nama_bidang2 option:first").remove();
                        $("#nama_bidang2").prepend(firstOption);
                        $("#nama_jabatan2").val(jabatan.nama_jabatan);
                        var firstOption = $('<option>', {
                            value: jabatan.nama_jabatan,
                            text: jabatan.nama_jabatan,
                            selected: true,
                            hidden: true,
                        });
                        $("#nama_jabatan2 option:first").remove();
                        $("#nama_jabatan2").prepend(firstOption);
                    } else if (response.status == 'error') {
                        $("#ubah_nama_jabatan").val("");
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
                    var namaPegawai = document.getElementById("ubah_nama_pegawai");
                    if (!namaPegawai.value) {
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
                                $('#form-peg').submit();
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection