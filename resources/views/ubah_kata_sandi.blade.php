@extends('layout.app')

@section('title')
    Ubah Password
@endsection

@section('content')
    <div id="layout-wrapper">
        <div id="layout-wrapper">
            @include('layout.header')
            @include('layout.sidebar')
            <div class="main-content">
                <div class="page-content">
                    <div class="container-fluid">
    
                        <!-- start page title -->
                        <div class="row mx-2">
                            <div class="col-12">
                                <div class="page-title-box align-items-center justify-content-between">
                                    <h5 class="mb-2">Ubah Kata Sandi Akun</h5>
                                    <h4 class="fs-base lh-base fw-medium text-muted mb-0">Silahkan buat kata sandi baru untuk keamanan akun Anda. Minimal 8 karakter, maksimal 16 karakter.</h4>
                                </div>
                            </div>
                        </div><!-- end page title -->
                        
                        <div>
                            <form method="POST" action="{{ route('submit_kata_sandi', Auth::user()->nip) }}">
                                @csrf
                                @method('PUT')
                                <div class="row bg-white rounded-3 pb-3 mb-3 mx-2">
                                    <div class="page-title-box bg-light-subtle rounded-3 d-flex align-items-center justify-content-between px-3 py-2">
                                        <h5 class="block-tittle">Form Buat Kata Sandi Baru Akun</h5>
                                    </div>
                                    <div class="block-content">
                                        <div class="row">
                                            <div class="col-md mb-3">
                                                <div class="input-group">
                                                    <div class="form-floating">
                                                        <input id="password_old" class="form-control form-control-alt form-control-lg" type="password" minlength="8" maxlength="16" name="password_old" placeholder=" " autocomplete="off" required>
                                                        <label for="password_old">
                                                            Kata Sandi Lama Anda
                                                            <small class="text-danger">*</small>
                                                        </label>
                                                    </div>
                                                    <span class="input-group-text toggle-password" onclick="togglePassword('password_old')">
                                                        <i class="fa fa-eye-slash"></i>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="col-md mb-3">
                                                <div class="input-group">
                                                    <div class="form-floating">
                                                        <input id="password_new" class="form-control form-control-alt form-control-lg" type="password" minlength="8" maxlength="16" name="password_new" placeholder=" " autocomplete="off" required>
                                                        <label for="password_new">
                                                            Kata Sandi Baru Anda
                                                            <small class="text-danger">*</small>
                                                        </label>
                                                    </div>
                                                    <span class="input-group-text toggle-password" onclick="togglePassword('password_new')">
                                                        <i class="fa fa-eye-slash"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>   
                                <div class="row justify-content pb-2 mb-2 mx-0">
                                    <div class="col-md-6">
                                        <div class="mb-3 mt-3">
                                            <button id="btn-simpan" class="btn btn-primary" type="submit">
                                                <i class="fa fa-save"></i>
                                                Simpan
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        function togglePassword(inputId) {
            var passwordInput = document.getElementById(inputId);
            var toggleIcon = passwordInput.parentElement.nextElementSibling.querySelector('.toggle-password i');
            
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            } else {
                passwordInput.type = "password";
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            }
        }
    </script>
@endsection