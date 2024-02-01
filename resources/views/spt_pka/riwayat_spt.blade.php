@extends('layout.app')

@section('title')
    Riwayat SPT
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
                    <div class="row bg-white rounded-3 pb-3 mb-3 mx-2">
                        <div class="page-title-box bg-light-subtle rounded-3 d-flex align-items-center justify-content-between px-3 py-2">
                            <h5>Riwayat Surat Perjalanan Tugas</h5>
                        </div>
                        <div class="container-fluid table-responsive px-3 py-3">
                            <table class="table table-striped" style="width:100%">
                                <thead>
                                    <tr>
                                        <th class="col-md-2 text-center align-middle">Nama Keperluan</th>
                                        <th class="col-md-2 text-center align-middle">Nama Ketua</th>
                                        <th class="col-md-2 text-center align-middle">Aksi</th>                            
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($spt as $data)
                                        @if(
                                            (in_array(Auth::user()->nama_pegawai, explode(', ', $data->nama_anggota)) || $data->nama_ketua_tim == Auth::user()->nama_pegawai)
                                            || (Auth::user()->nama_jabatan == 'Inspektur Pembantu' && $data->nama_pengawas == Auth::user()->nama_pegawai)
                                        )
                                            <tr>
                                                <td>{{ $data->untuk_spt }}</td>      
                                                <td class="text-center align-middle">{{ $data->nama_ketua_tim }}</td>
                                                <td>
                                                    <div style="display: flex;">
                                                        <button type="button" data-bs-toggle="modal" data-bs-target="#modalDetail" data-bs-id="{{ $data->id_spt }}" class="btn btn-primary btn-sm" style="margin-right: 10px;">Detail SPT</button>
                                                        <button type="button" style="margin-right: 10px" class="btn btn-primary btn-sm" onclick="location.href='detail_pka/{{ $data->id_spt }}'">Detail PKA</button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @elseif(Auth::user()->nama_jabatan == 'Sekretaris Dinas')
                                            <tr>
                                                <td>{{ $data->untuk_spt }}</td>      
                                                <td class="text-center align-middle">{{ $data->nama_ketua_tim }}</td>
                                                <td class="text-center align-middle">
                                                    <div style="display: flex;" class="d-flex justify-content-center">
                                                        <a href="{{ route('cetak', $data->id_spt) }}" class="btn btn-primary btn-sm" target="_blank" style="margin-right: 10px;">Unduh SPT</a>

                                                        <a href="{{ route('cetak2', $data->id_spt) }}" class="btn btn-info btn-sm" target="_blank" style="margin-right: 10px;">Unduh PKA</a>

                                                        <button type="button" data-bs-toggle="modal" data-bs-target="#modalDetail" data-bs-id="{{ $data->id_spt }}" class="btn btn-success btn-sm" style="margin-right: 10px;">SPT</button>

                                                        <button type="button" class="btn btn-warning btn-sm" style="margin-right: 10px;" onclick="location.href='detail_pka/{{ $data->id_spt }}'">PKA</button>

                                                        <button type="button" data-bs-id="{{ $data->id_spt }}" class="btn btn-danger btn-sm" onclick="location.href='detail_spt/{{ $data->id_spt }}'" style="margin-right: 10px;">Penjadwalan</button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endif
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
                        <div class="col-md-9">:&nbsp;
                            <label class="col-form-label" id="detail_jenis_spt"></label>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-md-2 col-form-label">Dasar</label>
                        <div class="col-md-9">:&nbsp;
                            <label class="col-form-label" id="detail_dasar_spt"></label>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-md-2 col-form-label">Kurun Waktu</label>
                        <div class="col-md-9">:&nbsp;
                            <label class="col-form-label" id="detail_tanggal"></label>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-md-2 col-form-label">Untuk</label>
                        <div class="col-md-9">:&nbsp;
                            <label class="col-form-label" id="detail_untuk_spt"></label>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-md-2 col-form-label">Obyek Audit</label>
                        <div class="col-md-9">:&nbsp;
                            <label class="col-form-label" id="detail_obyek_audit"></label>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-md-2 col-form-label">Penanggungjawab</label>
                        <div class="col-md-9">:&nbsp;
                            <label class="col-form-label" id="detail_penanggungjawab"></label>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-md-2 col-form-label">Pengawas</label>
                        <div class="col-md-9">:&nbsp;
                            <label class="col-form-label" id="detail_pengawas"></label>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-md-2 col-form-label">Ketua</label>
                        <div class="col-md-9">:&nbsp;
                            <label class="col-form-label" id="detail_ketua"></label>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-md-2 col-form-label">Anggota</label>
                        <div class="col-md-9">:&nbsp;
                            <label class="col-form-label" id="detail_anggota"></label>
                        </div>
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
    <script>
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
                        detail_tanggal += ` - ${endDate.toLocaleDateString('id-ID', { day: 'numeric', month: 'long' })} ${endYear}`;
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
                        var daftarAnggota = anggotaNamas.join(', ');

                        // Tampilkan daftar anggota dalam elemen HTML dengan id "detail_anggota"
                        $("#detail_anggota").html(daftarAnggota);
                    }
                }, // {{-- Ajax Success --}}
            }); // {{-- Ajax Function --}}
        });
    </script>
@endsection
