<div class="vertical-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <a href="index.html" class="logo">
            <span class="logo-lg">
                <img src="{{ asset('assets/images/logo-kab-magetan.png') }}" alt="" height="22"> <span class="logo-txt">E-Audit</span>
            </span>
            <span class="logo-sm">
                <img src="{{ asset('assets/images/logo-kab-magetan.png') }}" alt="" height="22">
            </span>
        </a>
    </div>
    <button type="button" class="btn btn-sm px-3 font-size-16 header-item vertical-menu-btn">
        <i class="fa fa-fw fa-bars"></i>
    </button>
    <div data-simplebar class="sidebar-menu-scroll">
        <div id="sidebar-menu">
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title" data-key="t-applications">Menu</li>
                <li>
                    <a href="{{ route('spt_pka.spt') }}">
                        <i class="bx bx-envelope icon nav-icon"></i>
                        <span class="menu-item" data-key="t-calendar">SPT</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('spt_pka.riwayat_spt') }}">
                        <i class="bx bx-list-ul icon nav-icon"></i>
                        <span class="menu-item" data-key="t-email">Riwayat SPT</span>
                    </a>
                </li>

                @if(Auth::user()->nama_jabatan == 'Sekretaris Dinas')
                <li class="menu-title" data-key="t-applications">DATA MASTER</li>

                <li>
                    <a href="{{ route('data_master.pegawai') }}">
                        <i class=" mdi mdi-checkbox-blank-circle-outline"></i>
                        <span class="menu-item" data-key="t-calendar">Pegawai</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('data_master.bidang') }}">
                        <i class="mdi mdi-checkbox-blank-circle-outline"></i>
                        <span class="menu-item" data-key="t-calendar">Bidang</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('data_master.jabatan') }}">
                        <i class="mdi mdi-checkbox-blank-circle-outline"></i>
                        <span class="menu-item" data-key="t-calendar">Jabatan</span>
                    </a>
                </li>
                @endif
            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>