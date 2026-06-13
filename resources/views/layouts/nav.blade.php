<!-- Sidebar -->
<div class="sidebar" data-background-color="dark">
    <div class="sidebar-logo">
        <!-- Logo Header -->
        <div class="logo-header" data-background-color="dark">
            <a href="#" class="logo text-section">
                {{-- <img src="{{ asset('') }}template/assets/img/kaiadmin/logo_light.svg" alt="navbar brand"
                    class="navbar-brand" height="20" /> --}}
                <span style="display: inline-flex; align-items: center; gap: 9px; max-width: 100%;">
                    <img src="{{ asset('logo.png') }}" alt="Logo STIKES Dian Husada"
                        style="width: 36px; height: 36px; object-fit: contain; flex-shrink: 0;">
                    <span
                        style="color: #ffffff; text-shadow: 0 0 2px white; font-size: 14px; font-weight: 600; line-height: 1.15; white-space: normal;">
                        STIKES DIAN HUSADA
                    </span>
                </span>
            </a>
            <div class="nav-toggle">
                <button class="btn btn-toggle toggle-sidebar">
                    <i class="gg-menu-right"></i>
                </button>
                <button class="btn btn-toggle sidenav-toggler">
                    <i class="gg-menu-left"></i>
                </button>
            </div>
            <button class="topbar-toggler more">
                <i class="gg-more-vertical-alt"></i>
            </button>
        </div>
        <!-- End Logo Header -->
    </div>
    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
            <ul class="nav nav-secondary">
                <li class="nav-item {{ request()->routeIs('profile') ? 'active' : '' }}">
                    <a href="{{ route('profile') }}">
                        <i class="fas fa-home"></i>
                        <p>Beranda</p>
                    </a>
                </li>

                @if (in_array('baak', $user['role'] ?? []) || in_array('admin', $user['role'] ?? []))
                    {{-- Admin --}}
                    @include('layouts.partials-nav.admin')
                @endif

                @if (in_array('dosen', $user['role'] ?? []) || in_array('kaprodi', $user['role'] ?? []))
                    {{-- Dosen --}}
                    @include('layouts.partials-nav.dosen')
                @endif

                @if (in_array('mahasiswa', $user['role'] ?? []))
                    {{-- Mahasiswa --}}
                    @include('layouts.partials-nav.mahasiswa')
                @endif


                <li class="nav-section">
                    <span class="sidebar-mini-icon">
                        <i class="fa fa-ellipsis-h"></i>
                    </span>
                    <h4 class="text-section">Sign-Out</h4>
                </li>
                <li class="nav-item {{ request()->routeIs('logout') ? 'active' : '' }}">
                    <a href="#"
                        onclick="event.preventDefault(); document.getElementById('logout-form-nav').submit();">
                        <i class="fas fa-sign-out-alt"></i>
                        <p>Logout</p>
                    </a>
                    <form id="logout-form-nav" method="POST" action="{{ route('logout') }}" class="d-none">
                        @csrf
                    </form>
                </li>
            </ul>
        </div>
    </div>

</div>
<!-- End Sidebar -->
