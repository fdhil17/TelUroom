<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
    <div class="container">
        <a class="navbar-brand" href="{{ route('dashboard') }}">
            <img src="{{ asset('images/logo.png') }}" alt="TelUroom" style="height: 36px; width: auto; object-fit: contain;">
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarMain">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active fw-bold' : '' }}" href="{{ route('dashboard') }}">
                        {{ __('nav.dashboard') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('calendar.*') ? 'active fw-bold' : '' }}" href="{{ route('calendar.index') }}">
                        {{ __('nav.kalender') }}
                    </a>
                </li>

                @php
                    $activeRole = Auth::user()->role === 'admin'
                        ? session('admin_role', 'ssc')
                        : Auth::user()->role;
                @endphp

                @if ($activeRole === 'mahasiswa')
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('mahasiswa.ruangan.*') ? 'active fw-bold' : '' }}" href="{{ route('mahasiswa.ruangan.index') }}">
                            {{ __('nav.daftar_ruangan') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('mahasiswa.reservasi.*') ? 'active fw-bold' : '' }}" href="{{ route('mahasiswa.reservasi.index') }}">
                            {{ __('nav.pengajuan_saya') }}
                        </a>
                    </li>
                @elseif ($activeRole === 'ssc')
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('ssc.approval.*') ? 'active fw-bold' : '' }}" href="{{ route('ssc.approval.index') }}">
                            {{ __('nav.verifikasi_pengajuan') }}
                        </a>
                    </li>
                @elseif ($activeRole === 'logistik')
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('logistik.ruangan.*') ? 'active fw-bold' : '' }}" href="{{ route('logistik.ruangan.index') }}">
                            {{ __('nav.data_ruangan') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('logistik.jadwal.*') ? 'active fw-bold' : '' }}" href="{{ route('logistik.jadwal.index') }}">
                            {{ __('nav.jadwal_akademik') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('logistik.approval.*') ? 'active fw-bold' : '' }}" href="{{ route('logistik.approval.index') }}">
                            {{ __('nav.persetujuan_peminjaman') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('log.*') ? 'active fw-bold' : '' }}" href="{{ route('log.index') }}">
                            {{ __('nav.log_ruangan') }}
                        </a>
                    </li>
                @endif
            </ul>

            <ul class="navbar-nav ms-auto align-items-center">

                {{-- Language Switcher --}}
                <li class="nav-item dropdown me-2">
                    <a class="nav-link dropdown-toggle d-flex align-items-center gap-2 px-2" href="#" role="button" data-bs-toggle="dropdown">
                        @if(app()->getLocale() === 'id')
                            <img src="https://flagcdn.com/w20/id.png" width="20" alt="ID" style="border-radius:2px;">
                            <span style="font-size:0.85rem; font-weight:600; color:#374151;">ID</span>
                        @else
                            <img src="https://flagcdn.com/w20/gb.png" width="20" alt="EN" style="border-radius:2px;">
                            <span style="font-size:0.85rem; font-weight:600; color:#374151;">EN</span>
                        @endif
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <form method="POST" action="{{ route('locale.switch', 'id') }}">
                                @csrf
                                <button type="submit" class="dropdown-item d-flex align-items-center gap-2 {{ app()->getLocale() === 'id' ? 'active' : '' }}">
                                    <img src="https://flagcdn.com/w20/id.png" width="20" alt="ID" style="border-radius:2px;">
                                    Bahasa Indonesia
                                </button>
                            </form>
                        </li>
                        <li>
                            <form method="POST" action="{{ route('locale.switch', 'en') }}">
                                @csrf
                                <button type="submit" class="dropdown-item d-flex align-items-center gap-2 {{ app()->getLocale() === 'en' ? 'active' : '' }}">
                                    <img src="https://flagcdn.com/w20/gb.png" width="20" alt="EN" style="border-radius:2px;">
                                    English
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>

                {{-- User Dropdown --}}
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                        {{ Auth::user()->name }}
                        @php
                            $displayRole = Auth::user()->role === 'admin'
                                ? strtoupper(session('admin_role', 'Admin'))
                                : ucfirst(Auth::user()->role);
                        @endphp
                        <span class="badge bg-secondary ms-1">{{ $displayRole }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        @if (Auth::user()->role !== 'admin')
                            <li><a class="dropdown-item" href="{{ route('profile.edit') }}">{{ __('nav.profile') }}</a></li>
                            <li><hr class="dropdown-divider"></li>
                        @endif
                        <li>
                            @if (Auth::user()->role === 'admin')
                                <form method="POST" action="{{ route('admin.logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">{{ __('nav.logout') }}</button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">{{ __('nav.logout') }}</button>
                                </form>
                            @endif
                        </li>
                    </ul>
                </li>

            </ul>
        </div>
    </div>
</nav>
