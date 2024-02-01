<header id="page-topbar" class="isvertical-topbar">
    <div class="navbar-header">
        <div class="d-flex">
            <!-- LOGO -->
            <div class="navbar-brand-box">
                <a href="index.html" class="logo">
                    <span class="logo-sm">
                        <img src="{{ asset('assets/images/logo-sm.svg') }}" alt="" height="22">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ asset('assets/images/logo-sm.svg') }}" alt="" height="22"> <span class="logo-txt">E-Audit</span>
                    </span>
                </a>
            </div>

            <button type="button" class="btn btn-sm px-3 font-size-16 header-item vertical-menu-btn">
                <i class="fa fa-fw fa-bars"></i>
            </button>
        </div>

        <div class="d-flex">
            <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item user text-start d-flex align-items-center" id="page-header-user-dropdown"
                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="bx bx-user"></i>&nbsp;{{ Auth::user()->nama_pegawai }}
                </button>
                <div class="dropdown-menu dropdown-menu-end pt-0">
                    <a class="dropdown-item" href="{{ route('ubah_kata_sandi') }}"><i class='bx bx-lock text-muted font-size-18 align-middle me-1'></i> <span class="align-middle">Ubah Kata Sandi</span></a>
                    <a class="dropdown-item" href="{{ route("keluar.akun") }}"><i class='bx bx-log-out text-muted font-size-18 align-middle me-1'></i> <span class="align-middle">Keluar</span></a>
                </div>
            </div>
        </div>
    </div>
</header>