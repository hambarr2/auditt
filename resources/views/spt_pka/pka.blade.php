@extends('layout.app')

@section('title')
    PKA
@endsection

@section('head')
    <link rel="stylesheet" href="{{ asset('assets/css/dataTables.bootstrap5.min.css') }}">
    <script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('assets/libs/moment/min/moment.min.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    

    {{-- <link rel="stylesheet" href="{{ asset('assets/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/libs/daterangepicker/daterangepicker.css') }}">
    <script type="text/javascript" src="{{ asset('assets/libs/daterangepicker/daterangepicker.min.js') }}"></script>
    <script src="{{ asset('ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('assets/libs/moment/min/moment.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/dataTables.bootstrap5.min.js') }}"></script> --}}
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
                                    <li class="breadcrumb-item"><a href="{{ route('spt_pka.spt') }}">SPT</a></li>
                                    <li class="breadcrumb-item active">PKA</li>
                                </ol>
                            </div>
                        </div>
                    </div><!-- end page title -->

                    <div class="row bg-white rounded-3 pb-3 mb-3 mx-2">
                        <div class="page-title-box bg-light-subtle rounded-3 d-flex align-items-center justify-content-between px-3 py-2">
                            <h5>PKA</h5>
                            <div class="mb-2 row">
                                    <button data-bs-toggle="modal" data-bs-target="#tambahitem" class="btn btn-primary">Tambah Item</button>
                            </div> 
                        </div>
                        <div class="container-fluid table-responsive px-3 py-3">
                            <table class="table table-bordered border-black" style="width:100%">
                                <thead>
                                    <tr>
                                        <th class="text-center align-middle" style="width: 20px;">Tujuan/Sasaran</th>  
                                        <th class="text-center align-middle" style="width: 20px;">Langkah-langkah kerja</th>
                                        <th class="text-center align-middle" style="width: 10px;">Dilaksanakan oleh</th>
                                        <th class="text-center align-middle" style="width: 5px;">Waktu yang diperlukan</th> 
                                        <th class="text-center align-middle" style="width: 5px;">Nomor KKA</th> 
                                        <th class="text-center align-middle" style="width: 20px;">Catatan</th>  
                                        <th class="text-center align-middle" style="width: 20px;">Aksi</th>                          
                                    </tr>
                                </thead>
                                <tbody>   
                                    @foreach ($pka as $data)
                                        <tr>
                                            <td>{{ $data->tujuan }}</td>      
                                            <td>{!!$data->langkah_kerja!!}</td>
                                            {{-- <td><ol type="1"><li>satu</li></ol></td> --}}
                                            <td>{{ $data->pelaksana }}</td>
                                            <td>{{ $data->waktu }}</td>       
                                            <td>{{ $data->no_kka }}</td> 
                                            <td>{{ $data->catatan }}</td> 
                                            <td>
                                                <a role="button" class="btn btn-warning me-2" title="Ubah Data" style="padding: 0.25rem 0.5rem; font-size: 18px;" data-bs-toggle="modal" data-bs-target="#modalUbahPKA" data-bs-id="{{ $data->id_pka}}"><i class="bx bx-pencil"></i></a>
                                                <form method="POST" action="{{ route('hapus_pka', $data->id_pka) }}" id="hapus-pka-{{ $data->id_pka }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <a role="button" id="btnHps-{{ $data->id_pka }}" class="btn btn-danger" title="Hapus Data"style="padding: 0.25rem 0.5rem; font-size: 18px;"><i class="bx bx-trash-alt"></i></a>
                                                </form>
                                                <script>
                                                    $('#btnHps-{{ $data->id_pka }}').click(function(event){
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
                                                                $('#hapus-pka-{{ $data->id_pka }}').submit();
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
    <div class="modal fade" id="tambahitem" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Formulir Tambah Program Kerja Audit</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('buat_pka') }}">
                    @csrf
                    <input type="hidden" name="id_spt" value="{{ $idSPT }}">
                    <div class="modal-body">
                        <div class="mb-3 row">
                            <label for="example-text-input" class="col-md-2 col-form-label">Tujuan/Sasaran</label>
                            <div class="col-md-9">
                                <textarea class="form-control" rows="2" id="tujuan" name="tujuan"></textarea>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="langkah" class="col-md-2 col-form-label">Langkah-langkah kerja</label>
                            <div class="col-md-9">
                                <textarea class="form-control" id="langkah" name="langkah"></textarea>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="example-text-input" class="col-md-2 col-form-label">Dilaksanakan oleh</label>
                            <div class="col-md-9">
                                <textarea class="form-control" rows="3" id="dilaksanakan" name="dilaksanakan"></textarea>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="num-teams" class="col-md-2 col-form-label">Waktu yang diperlukan</label>
                                <div class="col-md-1">
                                    <input type="number" class="form-control" id="num-teams" name="num-teams" min="1" value="1">
                                </div>
                                <div class="col-md-2">
                                    <label for="example-text-input" class="col-form-label">hari</label>
                                </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="example-text-input" class="col-md-2 col-form-label">Nomor KKA (Kertas Kerja Audit)</label>
                            <div class="col-md-5">
                                <input type="text" class="form-control" id="nomor" name="nomor" required>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="example-text-input" class="col-md-2 col-form-label">Catatan</label>
                            <div class="col-md-9">
                                <textarea class="form-control" rows="3" id="catatan" name="catatan"></textarea>
                            </div>
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


    <div class="modal fade" id="modalUbahPKA" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalUbahPKAA">Ubah Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <!-- Form untuk mengubah data -->
                <form method="POST" action="{{ route('ubah_data_pka') }}" id="formUbahPKA">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id_pka" id="id_pka" required>
                    <div class="modal-body">
                        <div class="mb-3 row">
                            <label for="ubah_tujuan" class="col-md-2 col-form-label">Tujuan</label>
                            <div class="col-md-9">
                                <textarea class="form-control" id="ubah_tujuan" name="ubah_tujuan" required></textarea>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="ubah_langkah" class="col-md-2 col-form-label">Langkah-langkah kerja</label>
                            <div class="col-md-9">
                                <textarea class="form-control" id="ubah_langkah" name="ubah_langkah" required></textarea>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="ubah_pelaksana" class="col-md-2 col-form-label">Dilaksanakan Oleh</label>
                            <div class="col-md-9">
                                <textarea class="form-control" id="ubah_pelaksana" name="ubah_pelaksana" required></textarea>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="ubah_waktu" class="col-md-2 col-form-label">Waktu yang diperlukan</label>
                            <div class="col-md-9">
                                <textarea class="form-control" id="ubah_waktu" name="ubah_waktu" required></textarea>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="ubah_nomor" class="col-md-2 col-form-label">Nomor KKA</label>
                            <div class="col-md-9">
                                <textarea class="form-control" id="ubah_nomor" name="ubah_nomor" required></textarea>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="ubah_catatan" class="col-md-2 col-form-label">Catatan</label>
                            <div class="col-md-9">
                                <textarea class="form-control" id="ubah_catatan" name="ubah_catatan" required></textarea>
                            </div>
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

{{-- Tambahan Baru --}}
@section('script')
    <script>
        var editor = CKEDITOR.replace('langkah');

        $(document).ready(function() {
            $('.table').DataTable({
                columnDefs: [
                    { orderable: false, targets: [2] }
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

        $(document).ready(function () {
            $('.open-ubah-modal').click(function () {
                var pka_id = $(this).data('id');
                $('#id_pka').val(pka_id);
                $('#modalUbahPKA').modal('show');
            });
        });

        $('#modalUbahPKA').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget)
            if (CKEDITOR.instances.ubah_langkah) {
                // Hapus editor yang sudah ada sebelum menggantikannya
                CKEDITOR.instances.ubah_langkah.destroy(true);
            }
            var editor = CKEDITOR.replace('ubah_langkah');
            var id_pka = button.data('id_pka');

            $("#id_pka").val(id_pka);

            $.ajax({
                url: '{{ route("get_data_pka") }}',
                type: 'POST',
                data: {
                    id: button.data('bs-id'),
                    _token: '{{ csrf_token() }}',
                },
                dataType: 'JSON',
                success: function(response) {
                    if (response.status == 'success') {
                        var pka = response.pka; 

                        $("#id_pka").val(pka.id_pka);
                        $("#ubah_tujuan").val(pka.tujuan);
                        editor.setData(pka.langkah_kerja);
                        $("#ubah_pelaksana").val(pka.pelaksana);
                        $("#ubah_waktu").val(pka.waktu);
                        $("#ubah_nomor").val(pka.no_kka);
                        $("#ubah_catatan").val(pka.catatan);
                    } else if (response.status == 'error') {
                        $("#ubah_tujuan").val("");
                    }
                }, 
            }); 
            $(document).ready(function() {
                $('#simpanPerubahan').click(function(event){
                    event.preventDefault(); // Mencegah pengiriman formulir secara default
                    var tujuan = document.getElementById("ubah_tujuan");
                    var langkahEditor = CKEDITOR.instances.ubah_langkah;
                    var langkahValue = langkahEditor.getData();
                    var pelaksanaPKA = document.getElementById("ubah_pelaksana");
                    var waktuPKA = document.getElementById("ubah_waktu");
                    var nomorPKA = document.getElementById("ubah_nomor");
                    var catatanPKA = document.getElementById("ubah_catatan");

                    if (!tujuan.value) {
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
                                $('#formUbahPKA').submit();
                            }
                        });
                    }
                });
            });
        });
        
    </script>

    <script>
        $(document).on('click', '.btn-edit', function(e){
            e.preventDefault();
            var pka_id = $(this).val();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "GET",
                url: "/ubah_isi_pka/"+pka_id,
                success: function (response) {
                console.log(response);
                    $('#ubah_tujuan').val(response.pka.tujuan);
                    $('#ubah_langkah').val(response.pka.langkah_kerja);
                    $('#ubah_pelaksana').val(response.pka.pelaksana);
                    $('#ubah_waktu').val(response.pka.waktu);
                    $('#ubah_nomor').val(response.pka.no_kka);
                    $('#ubah_catatan').val(response.pka.catatan);
                    $('#modalUbahPKA').modal('show');                
                }
            });
                
            });
    </script>
      
@endsection