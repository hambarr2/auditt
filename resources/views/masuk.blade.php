@extends('layout.app')

@section('title')
    Masuk
@endsection

@section('content')
    <div class="authentication-bg min-vh-100">
        <div class="bg-overlay"></div>
        <div class="container">
            <div class="d-flex flex-column min-vh-100 px-3 pt-4">
                <div class="row justify-content-center my-auto">
                    <div class="col-md-8 col-lg-6 col-xl-5">
                       <div class="text-center mb-4">
                            <a href="{{ route('dasbor') }}">
                                <img src="{{ asset('assets/images/logo-kab-magetan.png') }}" alt="" height="35"> <span class="logo-txt">E-Audit</span>
                            </a>
                       </div>

                        <div class="card">
                            <div class="card-body p-4"> 
                                <div class="text-center mt-2">
                                    <h5 class="text-primary">Selamat Datang</h5>
                                </div>
                                <div class="p-2 mt-4">
                                    <form method="POST" action="{{ route('masuk.akun') }}">
                                        @csrf
                                        <div class="mb-3">
                                            <label class="form-label" for="username">NIP</label>
                                            <input type="text" class="form-control" name="masuk_nip" id="nip" placeholder="Masukkan NIP">
                                        </div>
                                        <div class="mb-3">
                                                <label class="form-label" for="kata-sandi">Kata Sandi</label>
                                                <div class="input-group">
                                                    <input type="password" class="form-control" name="masuk_kata_sandi" id="kata-sandi" placeholder="Masukkan password">
                                                    <span class="input-group-text toggle-password" onclick="togglePassword()">
                                                        <i class="fa fa-eye-slash"></i>
                                                    </span>
                                                </div>
                                        </div>
                                        <div class="mt-3 text-end">
                                            <button class="btn btn-primary w-sm waves-effect waves-light" type="submit">Masuk</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div><!-- end col -->
                </div><!-- end row -->

                <div class="row">
                    <div class="col-lg-12">
                        <div class="text-center text-muted p-4">
                            <p class="text-white-50">© <script>document.write(new Date().getFullYear())</script> E-Audit</p>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- end container -->
    </div>
@endsection

@section('script')
    <script>
        function togglePassword() {
            var passwordInput = document.getElementById("kata-sandi");
            var toggleIcon = document.querySelector('.toggle-password i');
            
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