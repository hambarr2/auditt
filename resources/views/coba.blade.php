@extends('layout.app')

@section('title')
    Buat SPT Baru
@endsection

@section('head')
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

                    <!-- start page title -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Formulir Surat Perintah Tugas (SPT)</h4>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3 row">
                                        <label class="col-md-2 col-form-label">Jenis SPT</label>
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="jenisSPT" id="regulerRadio" value="reguler" required>
                                                <label class="form-check-label" for="regulerRadio">REGULER</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="jenisSPT" id="khususRadio" value="khusus" required>
                                                <label class="form-check-label" for="khususRadio">KHUSUS</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <label for="nomorSurat" class="col-md-2 col-form-label">Nomor Surat SPT</label>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control" id="nomorSurat" name="nomorSurat" required>
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <label for="tanggalInterval" class="col-md-2 col-form-label">Pilih Tanggal Interval</label>
                                        <div class="col-md-5">
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="tanggalAwal" name="tanggalAwal" required readonly>
                                                <div class="input-group-text">s/d</div>
                                                <input type="text" class="form-control" id="tanggalAkhir" name="tanggalAkhir" required readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <a role="button" class="btn btn-outline-info" id="tanggalInterval">Pilih</a>
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <label for="dasar" class="col-md-2 col-form-label">Dasar</label>
                                        <div class="col-md-8">
                                            <textarea class="form-control" id="dasar" name="dasar"></textarea>
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <label class="col-md-2 col-form-label">Pilih Ketua</label>
                                        <div class="col-md-4" id="dynamicRow">
                                            <div class="input-group baru-data">
                                                <select class="form-select" name="pegawai_id[]" required>
                                                    <option value="" selected disabled>-- Pilih Pegawai --</option>
                                                    @foreach ($pegawais as $bidang => $pegawai)
                                                        <optgroup label="{{ $bidang }}">
                                                            @foreach ($pegawai as $pegawaiItem)
                                                                <option value="{{ $pegawaiItem->id }}">{{ $pegawaiItem->nama_pegawai }}</option>
                                                            @endforeach
                                                        </optgroup>
                                                    @endforeach
                                                </select>
                                                <a role="button" class="btn btn-outline-secondary ml-2 btn-tambah" title="Tambah Ketua">
                                                    <i class="fa fa-plus"></i>
                                                </a>
                                                <a role="button" class="btn btn-outline-secondary ml-2 btn-hapus" style="display: none;" title="Hapus Ketua">
                                                    <i class="fa fa-minus"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <label for="untuk" class="col-md-2 col-form-label">Untuk</label>
                                        <div class="col-md-8">
                                            <textarea class="form-control" id="untuk" name="untuk"></textarea>
                                        </div>
                                    </div>
                                    <div class="mb-3 mt-3">
                                        <button type="submit" class="btn btn-primary float-end">Simpan</button>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- container-fluid -->
                    </div>
                </div>
            @include('layout.footer')
        </div>
    </div>
@endsection

@section('script')
    <script>
        var editor = CKEDITOR.replace('dasar');
        var editor2 = CKEDITOR.replace('untuk');
        $(document).ready(function() {
            $("#tanggalInterval").daterangepicker({
                opens: 'left', // Posisi kalender
                locale: {
                    format: 'DD MMMM YYYY' // Format tanggal yang ditampilkan
                },
                startDate: moment().startOf('month'), // Tanggal awal
                endDate: moment().endOf('month'), // Tanggal akhir
            }, function(start, end) {
                // Callback saat rentang tanggal berubah
                $("#tanggalAwal").val(start.format('DD MMMM YYYY'));
                $("#tanggalAkhir").val(end.format('DD MMMM YYYY'));
            });
        });
        var no = 0;
        $("#dynamicRow").on("click", ".btn-tambah", function() {
            no++;
            addForm(no);
            $(this).css("display", "none");
            var valtes = $(this).parent().find(".btn-hapus").css("display", "");
        });
        $("#dynamicRow").on("click", ".btn-hapus", function() {
            $(this).parent('.baru-data').remove();
            var bykrow = $(".baru-data").length;
            if (bykrow == 1) {
                $(".btn-hapus").css("display", "none")
                $(".btn-tambah").css("display", "");
            } else {
                $('.baru-data').last().find('.btn-tambah').css("display", "");
            }
        });
        function addForm(no) {
            var addrow =
                '   <div class="input-group baru-data">\
                        <select class="form-select" name="pegawai_id[]" required>\
                            <option value="" selected disabled>-- Pilih Pegawai --</option>\
                            @foreach ($pegawais as $bidang => $pegawai)\
                                <optgroup label="{{ $bidang }}">\
                                    @foreach ($pegawai as $pegawaiItem)\
                                        <option value="{{ $pegawaiItem->id }}">{{ $pegawaiItem->nama_pegawai }}</option>\
                                    @endforeach\
                                </optgroup>\
                            @endforeach\
                        </select>\
                        <a role="button" class="btn btn-outline-secondary ml-2 btn-tambah" title="Tambah Ketua">\
                            <i class="fa fa-plus"></i>\
                        </a>\
                        <a role="button" class="btn btn-outline-secondary ml-2 btn-hapus" title="Hapus Ketua">\
                            <i class="fa fa-minus"></i>\
                        </a>\
                    </div>'
            $("#dynamicRow").append(addrow);
        }
    </script>
@endsection