<nav class="teluroom-navbar navbar navbar-expand-lg">
    <div class="container">
        {{-- Brand --}}
        <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('dashboard') }}">
            <img src="{{ asset('images/logo.png') }}" alt="TelUroom" style="height: 32px; width: auto; object-fit: contain;">
        </a>

        <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain" aria-controls="navbarMain" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarMain">
            @php
                $activeRole = Auth::user()->role === 'admin'
                    ? session('admin_role', 'ssc')
                    : Auth::user()->role;
            @endphp

            <ul class="navbar-nav me-auto align-items-lg-center">
                <li class="nav-item">
                    <a class="nav-link teluroom-nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                       href="{{ route('dashboard') }}">
                        {{ strtoupper(__('nav.dashboard')) }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link teluroom-nav-link {{ request()->routeIs('calendar.*') ? 'active' : '' }}"
                       href="{{ route('calendar.index') }}">
                        {{ strtoupper(__('nav.kalender')) }}
                    </a>
                </li>

                @if ($activeRole === 'mahasiswa')
                    <li class="nav-item">
                        <a class="nav-link teluroom-nav-link {{ request()->routeIs('mahasiswa.ruangan.*') ? 'active' : '' }}"
                           href="{{ route('mahasiswa.ruangan.index') }}">
                            {{ strtoupper(__('nav.daftar_ruangan')) }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link teluroom-nav-link {{ request()->routeIs('mahasiswa.reservasi.*') ? 'active' : '' }}"
                           href="{{ route('mahasiswa.reservasi.index') }}">
                            {{ strtoupper(__('nav.pengajuan_saya')) }}
                        </a>
                    </li>
                @elseif ($activeRole === 'ssc')
                    <li class="nav-item">
                        <a class="nav-link teluroom-nav-link {{ request()->routeIs('ssc.approval.*') ? 'active' : '' }}"
                           href="{{ route('ssc.approval.index') }}">
                            {{ strtoupper(__('nav.verifikasi_pengajuan')) }}
                        </a>
                    </li>
                @elseif ($activeRole === 'logistik')
                    <li class="nav-item">
                        <a class="nav-link teluroom-nav-link {{ request()->routeIs('logistik.ruangan.*') ? 'active' : '' }}"
                           href="{{ route('logistik.ruangan.index') }}">
                            {{ strtoupper(__('nav.data_ruangan')) }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link teluroom-nav-link {{ request()->routeIs('logistik.jadwal.*') ? 'active' : '' }}"
                           href="{{ route('logistik.jadwal.index') }}">
                            {{ strtoupper(__('nav.jadwal_akademik')) }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link teluroom-nav-link {{ request()->routeIs('logistik.approval.*') ? 'active' : '' }}"
                           href="{{ route('logistik.approval.index') }}">
                            {{ strtoupper(__('nav.persetujuan_peminjaman')) }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link teluroom-nav-link {{ request()->routeIs('log.*') ? 'active' : '' }}"
                           href="{{ route('log.index') }}">
                            {{ strtoupper(__('nav.log_ruangan')) }}
                        </a>
                    </li>
                @endif
            </ul>

            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item dropdown">
                    <a class="nav-link teluroom-user-btn dropdown-toggle d-flex align-items-center gap-2"
                       href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <span class="teluroom-avatar">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </span>
                        <span class="d-none d-lg-inline text-truncate" style="max-width: 140px; font-size: 0.875rem; font-weight: 500; color: #111111;">
                            {{ Auth::user()->name }}
                        </span>
                        @php
                            $displayRole = Auth::user()->role === 'admin'
                                ? strtoupper(session('admin_role', 'Admin'))
                                : ucfirst(Auth::user()->role);
                        @endphp
                        <span class="teluroom-role-badge">{{ $displayRole }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end teluroom-dropdown" aria-labelledby="navbarDropdown">
                        @if (Auth::user()->role !== 'admin')
                            <li>
                                <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                    <svg width="15" height="15" class="me-2 opacity-50" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                    {{ __('nav.profile') }}
                                </a>
                            </li>
                            <li><hr class="dropdown-divider my-1"></li>
                        @endif
                        <li>
                            @if (Auth::user()->role === 'admin')
                                <form method="POST" action="{{ route('admin.logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <svg width="15" height="15" class="me-2 opacity-75" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                                        {{ __('nav.logout') }}
                                    </button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <svg width="15" height="15" class="me-2 opacity-75" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                                        {{ __('nav.logout') }}
                                    </button>
                                </form>
                            @endif
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
