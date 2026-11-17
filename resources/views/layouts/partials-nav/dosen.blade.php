<li class="nav-section">
    <span class="sidebar-mini-icon">
        <i class="fa fa-ellipsis-h"></i>
    </span>
    <h4 class="text-section">Perkuliahan</h4>
</li>

@php
    $roles = (array) ($user['role'] ?? $user['roles'] ?? session('user.role', []));
    $isKaprodi = in_array('kaprodi', $roles, true);
    $isDosen = in_array('dosen', $roles, true) || (session('profile_type') === 'dosen');
@endphp

@if ($isKaprodi)
    <li class="nav-item {{ request()->routeIs('workspace.kaprodi') ? 'active' : '' }}">
        <a href="{{ route('workspace.kaprodi') }}" class="collapsed" aria-expanded="false">
            <i class="fas fa-briefcase"></i>
            <p>Workspace Kaprodi</p>
        </a>
    </li>
@endif

@if ($isDosen)
    <li class="nav-item {{ request()->routeIs('workspace.dosen-pengajar') ? 'active' : '' }}">
        <a href="{{ route('workspace.dosen-pengajar') }}" class="collapsed" aria-expanded="false">
            <i class="fas fa-chalkboard-teacher"></i>
            <p>Workspace Dosen</p>
        </a>
    </li>

    <li class="nav-item {{ request()->routeIs('workspace.pembimbing-akademik') ? 'active' : '' }}">
        <a href="{{ route('workspace.pembimbing-akademik') }}" class="collapsed" aria-expanded="false">
            <i class="fas fa-briefcase"></i>
            <p>Workspace Pembimbing</p>
        </a>
    </li>

    <li class="nav-item {{ request()->routeIs('dosenpa.krs.*') ? 'active' : '' }}">
        <a href="{{ route('dosenpa.krs.index') }}" class="collapsed" aria-expanded="false">
            <i class="fas fa-file-signature"></i>
            <p>KRS Bimbingan</p>
        </a>
    </li>

    {{-- <li class="nav-item {{ request()->routeIs('dosen.pertemuan-presensi.*') ? 'active' : '' }}">
        <a href="{{ route('dosen.pertemuan-presensi.index') }}" class="collapsed" aria-expanded="false">
            <i class="fas fa-chalkboard-teacher"></i>
            <p>Pertemuan & Presensi</p>
        </a>
    </li>

    <li class="nav-item {{ request()->routeIs('dosen.penilaian.*') ? 'active' : '' }}">
        <a href="{{ route('dosen.penilaian.index') }}" class="collapsed" aria-expanded="false">
            <i class="fas fa-calculator"></i>
            <p>Penilaian Kelas</p>
        </a>
    </li> --}}
@endif
