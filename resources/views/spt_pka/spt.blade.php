@extends('layout.app')

@section('title')
    Data SPT
@endsection

@section('head')
    <link rel="stylesheet" href="{{ asset('assets/css/dataTables.bootstrap5.min.css') }}">
    <script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('assets/libs/moment/min/moment.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('assets/libs/daterangepicker/daterangepicker.css') }}">
    <script type="text/javascript" src="{{ asset('assets/libs/daterangepicker/daterangepicker.min.js') }}"></script>
@endsection

@section('content')
    <div id="layout-wrapper">
        @include('layout.header')
        @include('layout.sidebar')
        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">
                    
                    <div class="row bg-white rounded-3 pb-3 mb-3 mx-2">
                        <div class="page-title-box bg-light-subtle rounded-3 d-flex align-items-center justify-content-between px-3 py-2">
                            <h5>List Data SPT</h5>
                            @if(Auth::user()->nama_jabatan == 'Inspektur Pembantu')
                                <button data-bs-toggle="modal" data-bs-target="#tambahsptbaru" class="btn btn-primary">Tambah SPT</button>
                            @endif
                        </div>
                        <div class="container-fluid table-responsive px-3 py-3">
                            <table class="table table-striped" id="tabelSPT" style="width:100%">
                                <thead>
                                    <tr>
                                        <th class="col-md-2 text-center align-middle">Nama Keperluan</th>  
                                        <th class="col-md-2 text-center align-middle">Nama Ketua</th>
                                        <th class="col-md-2 text-center align-middle">Status</th>
                                        <th class="col-md-2 text-center align-middle">Aksi</th>                            
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- Inspektur Pembantu --}}
                                    @if(Auth::user()->nama_jabatan == 'Inspektur Pembantu')
                                        @foreach ($spt as $data)
                                            @if($data->nama_pengawas == Auth::user()->nama_pegawai && $data->status_spt != "Selesai")
                                                <tr>
                                                    <td>{{ $data->untuk_spt }}</td>      
                                                    <td class="text-center align-middle">{{ $data->nama_ketua_tim }}</td>
                                                    <td class="text-center align-middle">{{ $data->status_spt }}</td>
                                                    <td>
                                                        @if ($data->status_spt == "Draf")
                                                            <div style="display: flex;">
                                                                <button type="button" style="margin-right: 10px" data-bs-toggle="modal" data-bs-target="#modalUbah" data-bs-id="{{ $data->id_spt }}" class="btn btn-warning btn-sm">Ubah</button>
                                                                <form method="POST" action="{{ route('kirim_spt_irban', $data->id_spt) }}" id="kirim-spt-{{ $data->id_spt }}">
                                                                    @csrf
                                                                    @method('PUT')
                                                                    <button type="button" id="btnKirim-{{ $data->id_spt }}" class="btn btn-primary btn-sm">Kirim</button>
                                                                </form>
                                                            </div>
                                                            
                                                            <script>
                                                                $('#btnKirim-{{ $data->id_spt }}').click(function(event){
                                                                    event.preventDefault();
                                                                    Swal.fire({
                                                                        icon: "info",
                                                                        title: "Konfirmasi",
                                                                        text: "Apakah Anda yakin ingin mengirim data ini?",
                                                                        showCancelButton: true,
                                                                        confirmButtonText: "Ya, Lanjutkan",
                                                                        cancelButtonText: "Tidak, Batalkan",
                                                                    }).then(function (result) {
                                                                        if (result.isConfirmed) {
                                                                            // Jika pengguna mengonfirmasi, lanjutkan dengan pengiriman formulir
                                                                            $('#kirim-spt-{{ $data->id_spt }}').submit();
                                                                        }
                                                                    });
                                                                });
                                                            </script>
                                                        @elseif($data->status_spt == "Terkirim")
                                                            <button type="button" data-bs-toggle="modal" data-bs-target="#modalDetail" data-bs-id="{{ $data->id_spt }}" class="btn btn-primary btn-sm">Detail SPT</button>
                                                        @elseif($data->status_spt == "Menunggu Verifikasi Irban")
                                                            <div style="display: flex;">
                                                                <button type="button" data-bs-toggle="modal" data-bs-target="#modalDetail" data-bs-id="{{ $data->id_spt }}" class="btn btn-primary btn-sm" style="margin-right: 10px;">Detail SPT</button>
                                                                <button type="button" style="margin-right: 10px" class="btn btn-primary btn-sm" onclick="location.href='detail_pka/{{ $data->id_spt }}'">Detail PKA</button>
                                                            
                                                                <form method="POST" action="{{ route('kirim_spt_irban', $data->id_spt) }}" id="kirim-spt-{{ $data->id_spt }}">
                                                                    @csrf
                                                                    @method('PUT')
                                                                    <button type="button" id="btnKirim-{{ $data->id_spt }}" class="btn btn-success btn-sm">Verifikasi</button>
                                                                </form>
                                                            </div>
                                                            
                                                            <script>
                                                                $('#btnKirim-{{ $data->id_spt }}').click(function(event){
                                                                    event.preventDefault();
                                                                    Swal.fire({
                                                                        icon: "info",
                                                                        title: "Konfirmasi",
                                                                        text: "Apakah Anda yakin ingin verifikasi SPT ini?",
                                                                        showDenyButton: true,
                                                                        showCancelButton: true,
                                                                        confirmButtonText: "Setuju",
                                                                        denyButtonText: "Tolak",
                                                                        cancelButtonText: "Batal",
                                                                    }).then((result) => {
                                                                        /* Read more about isConfirmed, isDenied below */
                                                                        if (result.isConfirmed) {
                                                                            $('#kirim-spt-{{ $data->id_spt }}')
                                                                                .append('<input type="hidden" name="aksi" value="setuju" required>')
                                                                                .submit();
                                                                        } else if (result.isDenied) {
                                                                            $('#kirim-spt-{{ $data->id_spt }}')
                                                                                .append('<input type="hidden" name="aksi" value="tolak" required>')
                                                                                .submit();
                                                                        }
                                                                    });
                                                                });
                                                            </script>
                                                        @elseif($data->status_spt == "Menunggu Verifikasi Sekre" || $data->status_spt == "Mengatur Jadwal")
                                                            <div style="display: flex;">
                                                                <button type="button" data-bs-toggle="modal" data-bs-target="#modalDetail" data-bs-id="{{ $data->id_spt }}" class="btn btn-primary btn-sm" style="margin-right: 10px;">Detail SPT</button>
                                                                <button type="button" style="margin-right: 10px" class="btn btn-primary btn-sm" onclick="location.href='detail_pka/{{ $data->id_spt }}'">Detail PKA</button>
                                                            </div>
                                                        @elseif($data->status_spt == "Ditolak Irban")
                                                            <div style="display: flex;">
                                                                <button type="button" data-bs-toggle="modal" data-bs-target="#modalDetail" data-bs-id="{{ $data->id_spt }}" class="btn btn-primary btn-sm" style="margin-right: 10px;">Detail SPT</button>
                                                                <button type="button" style="margin-right: 10px" class="btn btn-primary btn-sm" onclick="location.href='detail_pka/{{ $data->id_spt }}'">Detail PKA</button>
                                                            </div>
                                                        @elseif($data->status_spt == "Ditolak Sekre")
                                                            <div style="display: flex;">
                                                                <button type="button" data-bs-toggle="modal" data-bs-target="#modalDetail" data-bs-id="{{ $data->id_spt }}" class="btn btn-primary btn-sm" style="margin-right: 10px;">Detail SPT</button>
                                                                <button type="button" style="margin-right: 10px" class="btn btn-primary btn-sm" onclick="location.href='detail_pka/{{ $data->id_spt }}'">Detail PKA</button>
                                                            </div>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    {{-- Sekretaris Dinas --}}
                                    @elseif(Auth::user()->nama_jabatan == 'Sekretaris Dinas')
                                        @foreach ($spt as $data)
                                            @if($data->status_spt == "Menunggu Verifikasi Irban")
                                                <tr>
                                                    <td>{{ $data->untuk_spt }}</td>      
                                                    <td class="text-center align-middle">{{ $data->nama_ketua_tim }}</td>
                                                    <td class="text-center align-middle">{{ $data->status_spt }}</td>
                                                    <td>
                                                        <div style="display: flex;">
                                                            <button type="button" data-bs-toggle="modal" data-bs-target="#modalDetail" data-bs-id="{{ $data->id_spt }}" class="btn btn-primary btn-sm" style="margin-right: 10px;">Detail SPT</button>
                                                            <button type="button" style="margin-right: 10px" class="btn btn-primary btn-sm" onclick="location.href='detail_pka/{{ $data->id_spt }}'">Detail PKA</button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @elseif($data->status_spt == 'Menunggu Verifikasi Sekre')
                                                <tr>
                                                    <td>{{ $data->untuk_spt }}</td>      
                                                    <td class="text-center align-middle">{{ $data->nama_ketua_tim }}</td>
                                                    <td class="text-center align-middle">{{ $data->status_spt }}</td>
                                                    <td>
                                                        <div style="display: flex;">
                                                            <button type="button" data-bs-toggle="modal" data-bs-target="#modalDetail" data-bs-id="{{ $data->id_spt }}" class="btn btn-primary btn-sm" style="margin-right: 10px">Detail SPT</button>
                                                            <button type="button" style="margin-right: 10px" class="btn btn-primary btn-sm" onclick="location.href='detail_pka/{{ $data->id_spt }}'">Detail PKA</button>
                                    
                                                            <form method="POST" action="{{ route('verifikasi_spt_sekre', $data->id_spt) }}" id="kirim-spt-{{ $data->id_spt }}">
                                                                @csrf
                                                                @method('PUT')
                                                                <button type="button" id="btnKirim-{{ $data->id_spt }}" class="btn btn-success btn-sm">Verifikasi</button>
                                                            </form>
                                                        </div>
                                                        <script>
                                                            $('#btnKirim-{{ $data->id_spt }}').click(function(event){
                                                                event.preventDefault();
                                                                Swal.fire({
                                                                    icon: "info",
                                                                    title: "Konfirmasi",
                                                                    text: "Apakah Anda yakin ingin verifikasi SPT ini?",
                                                                    showDenyButton: true,
                                                                    showCancelButton: true,
                                                                    confirmButtonText: "Setuju",
                                                                    denyButtonText: "Tolak",
                                                                    cancelButtonText: "Batal",
                                                                }).then((result) => {
                                                                    /* Read more about isConfirmed, isDenied below */
                                                                    if (result.isConfirmed) {
                                                                        $('#kirim-spt-{{ $data->id_spt }}')
                                                                            .append('<input type="hidden" name="aksi" value="setuju" required>')
                                                                            .submit();
                                                                    } else if (result.isDenied) {
                                                                        $('#kirim-spt-{{ $data->id_spt }}')
                                                                            .append('<input type="hidden" name="aksi" value="tolak" required>')
                                                                            .submit();
                                                                    }
                                                                });
                                                            });
                                                        </script>
                                                    </td>
                                                </tr>
                                            @elseif($data->status_spt == 'Mengatur Jadwal')
                                                <tr>
                                                    <td>{{ $data->untuk_spt }}</td>      
                                                    <td class="text-center align-middle">{{ $data->nama_ketua_tim }}</td>
                                                    <td class="text-center align-middle">{{ $data->status_spt }}</td>
                                                    <td>
                                                        <div style="display: flex;">
                                                            <button type="button" style="margin-right: 10px" class="btn btn-primary btn-sm" onclick="location.href='{{ route('detail_spt', $data->id_spt) }}'">Atur Jadwal</button>
                                                            <a href="{{ route('cetak', $data->id_spt) }}" target="_blank" class="btn btn-primary btn-sm" style="margin-right: 10px;">Unduh</a> 
                                                            
                                                            <form method="POST" action="{{ route('verifikasi_spt_selesai', $data->id_spt) }}" id="selesai-spt-{{ $data->id_spt }}">
                                                                @csrf
                                                                @method('PUT')
                                                                <button type="button" id="btnSelesai-{{ $data->id_spt }}" class="btn btn-primary btn-sm">Selesai</button>
                                                            </form>
                                                        </div>
                                                        <script>
                                                            $('#btnSelesai-{{ $data->id_spt }}').click(function(event){
                                                                event.preventDefault();
                                                                Swal.fire({
                                                                    icon: "info",
                                                                    title: "Konfirmasi",
                                                                    text: "Apakah Anda yakin ingin mengirim data ini?",
                                                                    showCancelButton: true,
                                                                    confirmButtonText: "Ya, Lanjutkan",
                                                                    cancelButtonText: "Tidak, Batalkan",
                                                                }).then(function (result) {
                                                                    if (result.isConfirmed) {
                                                                        // Jika pengguna mengonfirmasi, lanjutkan dengan pengiriman formulir
                                                                        $('#selesai-spt-{{ $data->id_spt }}').submit();
                                                                    }
                                                                });
                                                            });
                                                        </script>
                                                    </td>
                                                </tr>
                                            @elseif($data->status_spt == "Ditolak Sekre")
                                                <tr>
                                                    <td>{{ $data->untuk_spt }}</td>      
                                                    <td class="text-center align-middle">{{ $data->nama_ketua_tim }}</td>
                                                    <td class="text-center align-middle">{{ $data->status_spt }}</td>
                                                    <td>
                                                        <div style="display: flex;">
                                                            <button type="button" data-bs-toggle="modal" data-bs-target="#modalDetail" data-bs-id="{{ $data->id_spt }}" class="btn btn-primary btn-sm" style="margin-right: 10px;">Detail SPT</button>
                                                            <button type="button" style="margin-right: 10px" class="btn btn-primary btn-sm" onclick="location.href='detail_pka/{{ $data->id_spt }}'">Detail PKA</button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    @elseif(Auth::user()->nama_jabatan != 'Inspektur Pembantu' && Auth::user()->nama_jabatan != 'Sekretaris Dinas')
                                        @foreach ($spt as $data)
                                            @if($data->nama_ketua_tim == Auth::user()->nama_pegawai && $data->status_spt == 'Terkirim')
                                                <tr>
                                                    <td>{{ $data->untuk_spt }}</td>      
                                                    <td class="text-center align-middle">{{ $data->nama_ketua_tim }}</td>
                                                    <td class="text-center align-middle">Lengkapi Data SPT dan PKA</td>
                                                    <td>
                                                        <div style="display: flex;">
                                                            <button type="button" style="margin-right: 10px" data-bs-toggle="modal" data-bs-target="#modalUbah" data-bs-id="{{ $data->id_spt }}" class="btn btn-warning btn-sm">Ubah</button>
                                                            <button type="button" style="margin-right: 10px" class="btn btn-warning btn-sm" onclick="location.href='pka/{{ $data->id_spt }}'">Buat PKA</button>

                                                            <form method="POST" action="{{ route('kirim_spt_ketua', $data->id_spt) }}" id="kirim-spt-{{ $data->id_spt }}">
                                                                @csrf
                                                                @method('PUT')
                                                                <button type="button" id="btnKirim-{{ $data->id_spt }}" class="btn btn-primary btn-sm">Kirim</button>
                                                            </form>
                                                        </div>
                                                        <script>
                                                            $('#btnKirim-{{ $data->id_spt }}').click(function(event){
                                                                event.preventDefault();
                                                                Swal.fire({
                                                                    icon: "info",
                                                                    title: "Konfirmasi",
                                                                    text: "Apakah Anda yakin ingin mengirim data ini?",
                                                                    showCancelButton: true,
                                                                    confirmButtonText: "Ya, Lanjutkan",
                                                                    cancelButtonText: "Tidak, Batalkan",
                                                                }).then(function (result) {
                                                                    if (result.isConfirmed) {
                                                                        // Jika pengguna mengonfirmasi, lanjutkan dengan pengiriman formulir
                                                                        $('#kirim-spt-{{ $data->id_spt }}').submit();
                                                                    }
                                                                });
                                                            });
                                                        </script>
                                                    </td>
                                                </tr>
                                            @elseif ($data->nama_ketua_tim == Auth::user()->nama_pegawai && $data->status_spt == 'Menunggu Verifikasi Irban')
                                                <tr>
                                                    <td>{{ $data->untuk_spt }}</td>      
                                                    <td class="text-center align-middle">{{ $data->nama_ketua_tim }}</td>
                                                    <td class="text-center align-middle">{{ $data->status_spt }}</td>
                                                    <td>
                                                        <div style="display: flex;">
                                                            <button type="button" style="margin-right: 10px" data-bs-toggle="modal" data-bs-target="#modalDetail" data-bs-id="{{ $data->id_spt }}" class="btn btn-primary btn-sm">Detail SPT</button>
                                                            <button type="button" style="margin-right: 10px" class="btn btn-primary btn-sm" onclick="location.href='detail_pka/{{ $data->id_spt }}'">Detail PKA</button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @elseif($data->nama_ketua_tim == Auth::user()->nama_pegawai &&$data->status_spt == "Ditolak Irban")
                                                <tr>
                                                    <td>{{ $data->untuk_spt }}</td>      
                                                    <td class="text-center align-middle">{{ $data->nama_ketua_tim }}</td>
                                                    <td class="text-center align-middle">{{ $data->status_spt }}</td>
                                                    <td>
                                                        <div style="display: flex;">
                                                            <button type="button" style="margin-right: 10px" data-bs-toggle="modal" data-bs-target="#modalUbah" data-bs-id="{{ $data->id_spt }}" class="btn btn-warning btn-sm">Ubah</button>
                                                            <button type="button" style="margin-right: 10px" class="btn btn-warning btn-sm" onclick="location.href='pka/{{ $data->id_spt }}'">Buat PKA</button>

                                                            <form method="POST" action="{{ route('kirim_spt_ketua', $data->id_spt) }}" id="kirim-spt-{{ $data->id_spt }}">
                                                                @csrf
                                                                @method('PUT')
                                                                <button type="button" id="btnKirim-{{ $data->id_spt }}" class="btn btn-primary btn-sm">Kirim</button>
                                                            </form>
                                                        </div>
                                                        <script>
                                                            $('#btnKirim-{{ $data->id_spt }}').click(function(event){
                                                                event.preventDefault();
                                                                Swal.fire({
                                                                    icon: "info",
                                                                    title: "Konfirmasi",
                                                                    text: "Apakah Anda yakin ingin mengirim data ini?",
                                                                    showCancelButton: true,
                                                                    confirmButtonText: "Ya, Lanjutkan",
                                                                    cancelButtonText: "Tidak, Batalkan",
                                                                }).then(function (result) {
                                                                    if (result.isConfirmed) {
                                                                        // Jika pengguna mengonfirmasi, lanjutkan dengan pengiriman formulir
                                                                        $('#kirim-spt-{{ $data->id_spt }}').submit();
                                                                    }
                                                                });
                                                            });
                                                        </script>
                                                    </td>
                                                </tr>
                                            @elseif ($data->nama_ketua_tim == Auth::user()->nama_pegawai && $data->status_spt == 'Menunggu Verifikasi Sekre')
                                                <tr>
                                                    <td>{{ $data->untuk_spt }}</td>      
                                                    <td class="text-center align-middle">{{ $data->nama_ketua_tim }}</td>
                                                    <td class="text-center align-middle">{{ $data->status_spt }}</td>
                                                    <td>
                                                        <div style="display: flex;">
                                                            <button type="button" style="margin-right: 10px" data-bs-toggle="modal" data-bs-target="#modalDetail" data-bs-id="{{ $data->id_spt }}" class="btn btn-primary btn-sm">Detail SPT</button>
                                                            <button type="button" style="margin-right: 10px" class="btn btn-primary btn-sm" onclick="location.href='detail_pka/{{ $data->id_spt }}'">Detail PKA</button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @elseif($data->nama_ketua_tim == Auth::user()->nama_pegawai &&$data->status_spt == "Ditolak Sekre")
                                                <tr>
                                                    <td>{{ $data->untuk_spt }}</td>      
                                                    <td class="text-center align-middle">{{ $data->nama_ketua_tim }}</td>
                                                    <td class="text-center align-middle">{{ $data->status_spt }}</td>
                                                    <td>
                                                        <div style="display: flex;">
                                                            <button type="button" style="margin-right: 10px" data-bs-toggle="modal" data-bs-target="#modalUbah" data-bs-id="{{ $data->id_spt }}" class="btn btn-warning btn-sm">Ubah</button>
                                                            <button type="button" style="margin-right: 10px" class="btn btn-warning btn-sm" onclick="location.href='pka/{{ $data->id_spt }}'">Buat PKA</button>

                                                            <form method="POST" action="{{ route('kirim_spt_ketua', $data->id_spt) }}" id="kirim-spt-{{ $data->id_spt }}">
                                                                @csrf
                                                                @method('PUT')
                                                                <button type="button" id="btnKirim-{{ $data->id_spt }}" class="btn btn-primary btn-sm">Kirim</button>
                                                            </form>
                                                        </div>
                                                        <script>
                                                            $('#btnKirim-{{ $data->id_spt }}').click(function(event){
                                                                event.preventDefault();
                                                                Swal.fire({
                                                                    icon: "info",
                                                                    title: "Konfirmasi",
                                                                    text: "Apakah Anda yakin ingin mengirim data ini?",
                                                                    showCancelButton: true,
                                                                    confirmButtonText: "Ya, Lanjutkan",
                                                                    cancelButtonText: "Tidak, Batalkan",
                                                                }).then(function (result) {
                                                                    if (result.isConfirmed) {
                                                                        // Jika pengguna mengonfirmasi, lanjutkan dengan pengiriman formulir
                                                                        $('#kirim-spt-{{ $data->id_spt }}').submit();
                                                                    }
                                                                });
                                                            });
                                                        </script>
                                                    </td>
                                                </tr>
                                            @elseif($data->nama_ketua_tim == Auth::user()->nama_pegawai && $data->status_spt == 'Mengatur Jadwal')
                                                <tr>
                                                    <td>{{ $data->untuk_spt }}</td>      
                                                    <td class="text-center align-middle">{{ $data->nama_ketua_tim }}</td>
                                                    <td class="text-center align-middle">{{ $data->status_spt }}</td>
                                                    <td>
                                                        <div style="display: flex;">
                                                            <button type="button" style="margin-right: 10px" data-bs-toggle="modal" data-bs-target="#modalDetail" data-bs-id="{{ $data->id_spt }}" class="btn btn-primary btn-sm">Detail SPT</button>
                                                            <button type="button" style="margin-right: 10px" data-bs-toggle="modal" data-bs-target="#modalDetail" data-bs-id="{{ $data->id_spt }}" class="btn btn-primary btn-sm">Detail PKA</button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @elseif(in_array(Auth::user()->nama_pegawai, explode(', ', $data->nama_anggota)) && $data->status_spt != 'Draf' && $data->status_spt != 'Selesai')
                                                <tr>
                                                    <td>{{ $data->untuk_spt }}</td>      
                                                    <td class="text-center align-middle">{{ $data->nama_ketua_tim }}</td>
                                                    <td class="text-center align-middle">{{ $data->status_spt }}</td>
                                                    <td>
                                                        <div style="display: flex;">
                                                            <button type="button" data-bs-toggle="modal" data-bs-target="#modalDetail" data-bs-id="{{ $data->id_spt }}" class="btn btn-primary btn-sm" style="margin-right: 10px;">Detail SPT</button>
                                                            <button type="button" style="margin-right: 10px" class="btn btn-primary btn-sm" onclick="location.href='detail_pka/{{ $data->id_spt }}'">Detail PKA</button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    @endif
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
    <div class="modal fade" id="tambahsptbaru" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Formulir Tambah Surat Perintah Tugas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('buat_spt') }}"> 
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md mb-3">
                                <label class="col-form-label">Jenis SPT</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="jenis_spt" id="regulerRadio" value="reguler" required>
                                    <label class="form-check-label" for="regulerRadio">REGULER</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="jenis_spt" id="khususRadio" value="khusus" required>
                                    <label class="form-check-label" for="khususRadio">KHUSUS</label>
                                </div>
                            </div>
                            <div class="col-md mb-3">
                                <label class="col-form-label">Pilih Ketua</label>
                                <div class="input-group">
                                    <select class="form-select" name="nama" required>
                                        <option value="" selected hidden>-- Pilih Pegawai --</option>
                                        @foreach ($pegawais->unique('nama_bidang')->sortBy('nama_bidang') as $bidang)
                                            @if ($bidang->nama_bidang != 'Inspektorat' && $bidang->nama_bidang != 'Sekretariat')
                                                <optgroup label="{{ $bidang->nama_bidang }}&nbsp;">
                                                    @foreach ($pegawais->where('nama_bidang', $bidang->nama_bidang) as $pegawai)
                                                    @if ($pegawai->nama_jabatan != 'Inspektur Pembantu')
                                                        <option value="{{ $pegawai->nip }}">{{ $pegawai->nama_pegawai }}</option>
                                                    @endif
                                                    @endforeach
                                                </optgroup>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="dasar_spt" class="col-md-2 col-form-label">Dasar</label>
                            <div class="col-md-9">
                                <textarea class="form-control" id="dasar_spt" name="dasar_spt" required></textarea>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="untuk_spt" class="col-md-2 col-form-label">Untuk</label>
                            <div class="col-md-9">
                                <textarea class="form-control" id="untuk_spt" name="untuk_spt" required></textarea>
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

    <!-- modal ubah spt  -->
    <div class="modal fade" id="modalUbah" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalUbahLabel">Ubah Data SPT</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <!-- Form untuk mengubah data -->
                <form method="POST" action="{{ route('ubah_spt_irban') }}" id="formUbah">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id_spt" id="id_spt" required>
                    <input type="hidden" name="ketua_sebelumnya" id="ketua_sebelumnya" required>
                    <div class="modal-body">
                        <!-- Masukkan bidang-bidang yang ingin Anda ubah di sini -->
                        <div class="mb-3 row">
                            <label class="col-md-2 col-form-label">Jenis SPT</label>
                            <div class="col-md-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="ubah_jenis_spt" id="ubah_regulerRadio" value="reguler" required>
                                    <label class="form-check-label" for="ubah_regulerRadio">REGULER</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="ubah_jenis_spt" id="ubah_khususRadio" value="khusus" required>
                                    <label class="form-check-label" for="ubah_khususRadio">KHUSUS</label>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="dasar_spt" class="col-md-2 col-form-label">Dasar</label>
                            <div class="col-md-9">
                                <textarea class="form-control" id="ubah_dasar_spt" name="ubah_dasar_spt" required></textarea>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="ubah_untuk_spt" class="col-md-2 col-form-label">Untuk</label>
                            <div class="col-md-9">
                                <textarea class="form-control" id="ubah_untuk_spt" name="ubah_untuk_spt" required></textarea>
                            </div>
                        </div>
                        @if(Auth::user()->nama_jabatan != 'Inspektur Pembantu' && Auth::user()->nama_jabatan != 'Sekretaris Dinas')
                        <div class="mb-3 row">
                            <label for="ubah_obyek" class="col-md-2 col-form-label">Obyek Audit</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" id="ubah_obyek" name="ubah_obyek" required></inclass=>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="tanggalInterval" class="col-md-2 col-form-label">Tanggal Interval</label>
                            <div class="col-md-5">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="kurun_waktu_awal" name="kurun_waktu_awal" required readonly>
                                    <div class="input-group-text">s/d</div>
                                    <input type="text" class="form-control" id="kurun_waktu_akhir" name="kurun_waktu_akhir" required readonly>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <a role="button" class="btn btn-outline-info" id="tanggalInterval">Pilih</a>
                            </div>
                        </div>
                        @endif
                        <div class="mb-3 row">
                            <label for="ubah_penanggungjawab" class="col-md-2 col-form-label">Penanggungjawab</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" id="ubah_penanggungjawab" readonly>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="ubah_pengawas" class="col-md-2 col-form-label">Pengawas</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" id="ubah_pengawas" readonly>
                            </div>
                        </div>
                        @if(Auth::user()->nama_jabatan == 'Inspektur Pembantu')
                        <div class="mb-3 row">
                            <label for="ubah_ketua" class="col-md-2 col-form-label">Ketua</label>
                            <div class="col-md-9">
                                <div class="input-group">
                                    <select class="form-select" name="ubah_ketua" required>
                                        <option id="ubah_nama_ketua" selected hidden></option>
                                        @foreach ($pegawais->unique('nama_bidang')->sortBy('nama_bidang') as $bidang)
                                            @if ($bidang->nama_bidang != 'Inspektorat' && $bidang->nama_bidang != 'Sekretariat')
                                                <optgroup label="{{ $bidang->nama_bidang }}&nbsp;">
                                                    @foreach ($pegawais->where('nama_bidang', $bidang->nama_bidang) as $pegawai)
                                                    @if ($pegawai->nama_jabatan != 'Inspektur Pembantu')
                                                        <option value="{{ $pegawai->nip }}">{{ $pegawai->nama_pegawai }}</option>
                                                    @endif
                                                    @endforeach
                                                </optgroup>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        @elseif(Auth::user()->nama_jabatan != 'Inspektur Pembantu' && Auth::user()->nama_jabatan != 'Sekretaris Dinas')
                        <div class="mb-3 row">
                            <label for="ubah_ketuafix" class="col-md-2 col-form-label">Ketua</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" id="ubah_ketuafix" readonly>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-md-2 col-form-label">Anggota</label>
                            <div class="col-md-9">
                                <select class="select form-select" name="ubah_anggota[]" id="ubah_anggota" multiple multiselect-search="true" multiselect-select-all="true" multiselect-max-items="false" onchange="console.log(this.selectedOptions)" required>
                                    @foreach ($pegawais->unique('nama_bidang')->sortBy('nama_bidang') as $bidang)
                                        @if ($bidang->nama_bidang != 'Inspektorat' && $bidang->nama_bidang != 'Sekretariat')
                                            <optgroup label="{{ $bidang->nama_bidang }}">
                                                @foreach ($pegawais->where('nama_bidang', $bidang->nama_bidang) as $pegawai)
                                                @if ($pegawai->nama_jabatan != 'Inspektur Pembantu' && $pegawai->nama_pegawai != Auth::user()->nama_pegawai)
                                                    <option value="{{ $pegawai->nip }}">{{ $pegawai->nama_pegawai }}</option>
                                                @endif
                                                @endforeach
                                            </optgroup>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-warning" id="simpanPerubahan">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalDetail" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail SPT</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3 row">
                        <label class="col-md-2 col-form-label">Jenis SPT</label>
                        <span class="col-md-9">:&nbsp;
                            <label class="col-form-label" id="detail_jenis_spt"></label>
                        </span>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-md-2 col-form-label">Dasar</label>
                        <span class="col-md-9 col-form-label" style="padding-top: 0;display: flex;padding-top: calc(.47rem + var(--bs-border-width));">:&nbsp;
                            <label class="col-form-label" id="detail_dasar_spt" style="padding-top: 0;"></label>
                        </span>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-md-2 col-form-label">Kurun Waktu</label>
                        <span class="col-md-9">:&nbsp;
                            <label class="col-form-label" id="detail_tanggal"></label>
                        </span>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-md-2 col-form-label">Untuk</label>
                        <span class="col-md-9">:&nbsp;
                            <label class="col-form-label" id="detail_untuk_spt"></label>
                        </span>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-md-2 col-form-label">Obyek Audit</label>
                        <span class="col-md-9">:&nbsp;
                            <label class="col-form-label" id="detail_obyek_audit"></label>
                        </span>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-md-2 col-form-label">Penanggungjawab</label>
                        <span class="col-md-9">:&nbsp;
                            <label class="col-form-label" id="detail_penanggungjawab"></label>
                        </span>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-md-2 col-form-label">Pengawas</label>
                        <span class="col-md-9">:&nbsp;
                            <label class="col-form-label" id="detail_pengawas"></label>
                        </span>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-md-2 col-form-label">Ketua</label>
                        <span class="col-md-9">:&nbsp;
                            <label class="col-form-label" id="detail_ketua"></label>
                        </span>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-md-2 col-form-label">Anggota</label>
                        <span class="col-md-9">:&nbsp;
                            <label class="col-form-label" id="detail_anggota"></label>
                        </span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('multiselect-dropdown.js') }}"></script>
    <script>
        var editor = CKEDITOR.replace('dasar_spt');
        $(document).ready(function() {
            $('#tabelSPT').DataTable({
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
            if (CKEDITOR.instances.ubah_dasar_spt) {
                // Hapus editor yang sudah ada sebelum menggantikannya
                CKEDITOR.instances.ubah_dasar_spt.destroy(true);
            }
            var editor2 = CKEDITOR.replace('ubah_dasar_spt');
            $.ajax({
                url: '{{ route("get_data_spt_irban") }}',
                type: 'POST',
                data: {
                    id: button.data('bs-id'),
                    _token: '{{ csrf_token() }}',
                },
                dataType: 'JSON',
                success: function(response) {
                    if (response.status == 'success') {
                        var spt = response.spt; // Mengambil data spt dari respons
                        // Mengisi bidang formulir dengan data yang diterima
                        $('input[name="ubah_jenis_spt"]').prop('checked', false);
                        $('input[name="ubah_jenis_spt"][value="' + spt.jenis_spt + '"]').prop('checked', true);
                        $("#id_spt").val(spt.id_spt);
                        editor2.setData(spt.dasar_spt);
                        $("#ubah_untuk_spt").val(spt.untuk_spt);
                        $("#ubah_obyek").val(spt.obyek_audit);

                        var startDate;
                        var endDate;
                        if (spt.kurun_waktu_awal) {
                        startDate = moment(spt.kurun_waktu_awal);
                        $("#kurun_waktu_awal").val(startDate.format('DD MMMM YYYY'));
                        } else {
                        startDate = moment().startOf('month');
                        $("#kurun_waktu_awal").val('Belum memilih');
                        }
                        if (spt.kurun_waktu_akhir) {
                        endDate = moment(spt.kurun_waktu_akhir);
                        $("#kurun_waktu_akhir").val(endDate.format('DD MMMM YYYY'));
                        } else {
                        endDate = moment().endOf('month');
                        $("#kurun_waktu_akhir").val('Belum memilih');
                        }
                        
                        $("#tanggalInterval").daterangepicker({
                            opens: 'left', // Posisi kalender
                            locale: {
                                format: 'DD MMMM YYYY' // Format tanggal yang ditampilkan
                            },
                            startDate: startDate, // Tanggal awal
                            endDate: endDate, // Tanggal akhir
                            minDate: moment(), // Menonaktifkan tanggal sebelum hari ini
                        }, function(start, end) {
                            // Callback saat rentang tanggal berubah
                            $("#kurun_waktu_awal").val(start.format('DD MMMM YYYY'));
                            $("#kurun_waktu_akhir").val(end.format('DD MMMM YYYY'));
                        });
                        
                        var anggotaSPT = response.anggotaSPT;
                        var listNamaAngoota = '';
                        anggotaSPT.forEach(function(anggota) {
                            var idAnggota = anggota.id_anggota;
                            var nip = anggota.nip;
                            var keterangan = anggota.keterangan;
                            var namaPegawai = anggota.nama_pegawai;
                            
                            if(keterangan == 'Penanggungjawab') {
                                $("#ubah_penanggungjawab").val(namaPegawai);
                            }
                            if(keterangan == 'Pengawas') {
                                $("#ubah_pengawas").val(namaPegawai);
                            }
                            if(keterangan == 'Ketua Tim') {
                                $("#ketua_sebelumnya").val(nip);
                                $("#ubah_nama_ketua").val(nip);
                                $("#ubah_nama_ketua").text(namaPegawai);
                                $("#ubah_ketuafix").val(namaPegawai);
                            }
                            if(keterangan == 'Anggota') {
                                var anggotaNames = [];
                                anggotaSPT.forEach(function(anggota) {
                                    if (anggota.keterangan == 'Anggota') {
                                        anggotaNames.push(anggota.nama_pegawai);
                                    }
                                });
                                var namaPegawai = anggotaNames.join(', ');
                                listNamaAngoota = namaPegawai;
                            }
                        });
                        var anggotaNames = listNamaAngoota.split(', ');
                        var selectElement = document.getElementById('ubah_anggota');
                        selectElement.querySelectorAll('option').forEach(function(option) {
                            if (anggotaNames.includes(option.textContent)) {
                                option.selected = true;
                            }
                        });
                        selectElement.loadOptions();
                    } else if (response.status == 'error') {
                        $('input[name="ubah_jenis_spt"]').prop('checked', false);
                    }
                }, // {{-- Ajax Success --}}
            }); // {{-- Ajax Function --}}

            $(document).ready(function() {
                $('#simpanPerubahan').click(function(event){
                    event.preventDefault(); // Mencegah pengiriman formulir secara default

                    // Cek apakah semua input yang diperlukan telah diisi
                    var jenisSPT = document.querySelector('input[name="ubah_jenis_spt"]:checked');
                    var dasarSPTEditor = CKEDITOR.instances.ubah_dasar_spt;
                    var dasarSPTValue = dasarSPTEditor.getData();
                    var untukSPT = document.getElementById("ubah_untuk_spt");
                    var obyekAudit = document.getElementById("ubah_obyek");

                    if (!jenisSPT || !dasarSPTValue || !untukSPT.value) {
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
                                $('#formUbah').submit();
                            }
                        });
                    }
                });
            });
            
        }); // {{-- Modal Action --}}

        $('#modalDetail').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget)
            $.ajax({
                url: '{{ route("get_data_spt_irban") }}',
                type: 'POST',
                data: {
                    id: button.data('bs-id'),
                    _token: '{{ csrf_token() }}',
                },
                dataType: 'JSON',
                success: function(response) {
                    if (response.status == 'success') {
                        var spt = response.spt;
                        $("#detail_jenis_spt").html(spt.jenis_spt);
                        $("#detail_dasar_spt").html(spt.dasar_spt);
                        $("#detail_dasar_spt").find("ol").css({
                            "list-style-type": "decimal",
                            "padding": "0 20px",
                            
                        });
                        $("#detail_untuk_spt").html(spt.untuk_spt);
                        $("#detail_obyek_audit").html(spt.obyek_audit);
                        //panggil kurun waktu
                        const now = new Date();
                        const startDate = new Date(spt.kurun_waktu_awal);
                        const endDate = new Date(spt.kurun_waktu_akhir);
                        const startYear = startDate.getFullYear();
                        const endYear = endDate.getFullYear();
                        const startMonth = startDate.getMonth();
                        const endMonth = endDate.getMonth();
                        let detail_tanggal = startDate.toLocaleDateString('id-ID', { day: 'numeric'});
                        if(startYear !== endYear) {
                            detail_tanggal += ` ${startDate.toLocaleDateString('id-ID', { month: 'long' })} ${startYear}`;
                        } else if(startMonth !== endMonth) {
                            detail_tanggal += ` ${startDate.toLocaleDateString('id-ID', { month: 'long' })}`;
                        }
                        if(spt.status_spt>2) {
                            detail_tanggal += ` - ${endDate.toLocaleDateString('id-ID', { day: 'numeric', month: 'long' })} ${endYear}`;
                        } else {
                            detail_tanggal = '-';
                        }
                        $("#detail_tanggal").html(detail_tanggal);

                        var anggotaSPT = response.anggotaSPT;
                        var anggotaNamas = [];
                        anggotaSPT.forEach(function(anggota) {
                            var idAnggota = anggota.id_anggota;
                            var keterangan = anggota.keterangan;
                            var namaPegawai = anggota.nama_pegawai;
                            
                            if(keterangan == 'Penanggungjawab') {
                                $("#detail_penanggungjawab").html(namaPegawai);
                            }
                            if(keterangan == 'Pengawas') {
                                $("#detail_pengawas").html(namaPegawai);
                            }
                            if(keterangan == 'Ketua Tim') {
                                $("#detail_ketua").html(namaPegawai);
                            }
                            if(keterangan == 'Anggota') {
                                anggotaNamas.push(namaPegawai);
                            }
                        });
                        // Gabungkan daftar nama anggota menjadi satu string dengan koma sebagai pemisah
                        var daftarAnggota = '';
                        if(spt.status_spt>2) {
                            daftarAnggota = anggotaNamas.join(', ');
                        } else {
                            daftarAnggota = '-';
                        }

                        // Tampilkan daftar anggota dalam elemen HTML dengan id "detail_anggota"
                        $("#detail_anggota").html(daftarAnggota);
                    }
                }, // {{-- Ajax Success --}}
            }); // {{-- Ajax Function --}}
        });
    </script>
@endsection