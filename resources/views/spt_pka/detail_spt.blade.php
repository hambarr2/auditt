@extends('layout.app')

@section('title')
    Detail SPT
@endsection

@section('head')
    <script src="{{ asset('assets/libs/moment/min/moment.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('assets/css/datepicker.css') }}">
    <script src="{{ asset('assets/js/datepicker.js') }}"></script>    
    <script type="text/javascript" src="{{ asset('assets/libs/daterangepicker/daterangepicker.min.js') }}"></script>    
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
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
                    <div class="row mx-2">
                        <div class="col-12">
                            <div class="page-title-box d-flex align-items-center justify-content-between">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item"><a href="{{ route('spt_pka.spt') }}">SPT</a></li>
                                    <li class="breadcrumb-item active">Detail SPT</li>
                                </ol>
                            </div>
                        </div>
                    </div><!-- end page title -->

                    <div class="row bg-white rounded-3 pb-3 mb-3 mx-2">
                        <div class="page-title-box bg-light-subtle rounded-3 d-flex align-items-center justify-content-between px-3 py-2">                                
                            <h5>{{ $spt->untuk_spt }}</h5>
                        </div>
                        <div class="container-fluid table-responsive px-3 py-3">
                            <table class="table table-striped" style="width:100%">
                                <thead>
                                    <tr>
                                        <th class="text-center">Nama Anggota</th>
                                        <th class="text-center">Keterangan</th>  
                                        <th class="text-center">Atur Tanggal</th>                            
                                    </tr>
                                </thead>
                                <form action="{{ route('simpan_tanggal') }}" method="POST">
                                @csrf
                                    <tbody>
                                    @foreach ($anggota_spt as $data)
                                        <tr>
                                            <td class="align-middle col-md-4">{{ $data->relasi_pegawai->nama_pegawai}}</td>
                                            <td class="text-center align-middle col-md-2">{{ $data->keterangan }}</td>
                                            <td class="text-center align-middle">
                                                <div class="mb-8 row">
                                                    <label for="tanggalInterval" class="align-middle col-md-2 col-form-label"></label>
                                                    <div class="col-md-8">
                                                        <div class="input-group">
                                                            <input type="text" name="id_anggota{{ $data->id_anggota }}" value="{{ $data->id_anggota }}" hidden>
                                                            <input type="text" class="form-control tanggal_awal" name="tanggal_awal{{ $data->id_anggota }}" placeholder="{{ !empty($data->tanggal_awal) ? \Carbon\Carbon::parse($data->tanggal_awal)->format('j F Y') : '' }}">
                                                            <div class="input-group-text">s/d</div>
                                                            <input type="text" class="form-control tanggal_akhir" name="tanggal_akhir{{ $data->id_anggota }}" placeholder="{{ !empty($data->tanggal_akhir) ? \Carbon\Carbon::parse($data->tanggal_akhir)->format('j F Y') : '' }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <a role="button" class="btn btn-outline-info tanggalInterval">Pilih</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>                                                             
                                </table>
                                <button type="submit" class="btn btn-success mr-5" style="float: right">Simpan</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @include('layout.footer')
        </div>
    </div>
@endsection

@section('script')
    <script>
        var startDate;
        var endDate;

        $("#tanggal_awal, #tanggal_akhir").on('change', function() {
            startDate = moment($("#tanggal_awal").val(), 'DD MMMM YYYY');
            endDate = moment($("#tanggal_akhir").val(), 'DD MMMM YYYY');
            console.log("Start Date:", startDate.format('DD MMMM YYYY'));
            console.log("End Date:", endDate.format('DD MMMM YYYY'));
        });

        $(document).ready(function() {
            $(".tanggalInterval").each(function(index) {
                var startDate;
                var endDate;

                $(this).daterangepicker({
                    opens: 'left',
                    locale: {
                        format: 'DD MMMM YYYY'
                    },
                    startDate: moment(), 
                    endDate: moment(), 
                    minDate: moment()
                }, function(start, end) {
                    $(".tanggal_awal").eq(index).val(start.format('DD MMMM YYYY'));
                    $(".tanggal_akhir").eq(index).val(end.format('DD MMMM YYYY'));
                });
            });
        });
    </script>
@endsection
