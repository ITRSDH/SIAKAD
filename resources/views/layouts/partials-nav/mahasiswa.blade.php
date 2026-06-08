<li class="nav-section">
    <span class="sidebar-mini-icon">
        <i class="fa fa-ellipsis-h"></i>
    </span>
    <h4 class="text-section">Akademik Saya</h4>
</li>

@if (session('profile_type') === 'mahasiswa')
    <li class="nav-item {{ request()->routeIs('student.pembayaran.index') ? 'active' : '' }}">
        <a href="{{ route('student.pembayaran.index') }}" class="collapsed" aria-expanded="false">
            <i class="fas fa-home"></i>
            <p>Pembayaran</p>
        </a>
    </li>

    <li class="nav-item {{ request()->routeIs('krs.index') ? 'active' : '' }}">
        <a href="{{ route('krs.index') }}" class="collapsed" aria-expanded="false">
            <i class="fas fa-book-open"></i>
            <p>KRS</p>
        </a>
    </li>

    <li class="nav-item {{ request()->routeIs('student.khs.*') ? 'active' : '' }}">
        <a href="{{ route('student.khs.index') }}" class="collapsed" aria-expanded="false">
            <i class="fas fa-chart-bar"></i>
            <p>KHS</p>
        </a>
    </li>

    {{-- <li class="nav-item {{ request()->routeIs('student.transkrip.*') ? 'active' : '' }}">
        <a href="{{ route('student.transkrip.index') }}" class="collapsed" aria-expanded="false">
            <i class="fas fa-file-alt"></i>
            <p>Transkrip</p>
        </a>
    </li> --}}
@endif
