@extends('layout.app')

@section('title')
    Detail PKA
@endsection

@section('head')
    <link rel="stylesheet" href="{{ asset('assets/css/dataTables.bootstrap5.min.css') }}">
    <script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('assets/libs/moment/min/moment.min.js') }}"></script>
    

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
                                    <li class="breadcrumb-item active">Detail PKA</li>
                                </ol>
                            </div>
                        </div>
                    </div><!-- end page title -->

                    <div class="row bg-white rounded-3 pb-3 mb-3 mx-2">
                        <div class="page-title-box bg-light-subtle rounded-3 d-flex align-items-center justify-content-between px-3 py-2">
                            <h5>Detail PKA</h5>
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