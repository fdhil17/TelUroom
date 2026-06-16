@php
    $activeRole = Auth::user()->role === 'admin'
        ? session('admin_role', 'ssc')
        : Auth::user()->role;
@endphp

<aside class="teluroom-sidebar">
    <div class="sidebar-brand">
        <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none">
            <img src="{{ asset('images/logo.png') }}" alt="TelUroom Logo">
        </a>
    </div>

    <nav class="sidebar-nav">
        @if ($activeRole === 'mahasiswa')
            {{-- MAHASISWA ROLE --}}
            <div class="sidebar-heading">Menu Utama</div>
            <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                Beranda
            </a>
            <a href="{{ route('calendar.index') }}" class="sidebar-link {{ request()->routeIs('calendar.*') ? 'active' : '' }}">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                Kalender Ruangan
            </a>
            <a href="{{ route('mahasiswa.ruangan.index') }}" class="sidebar-link {{ request()->routeIs('mahasiswa.ruangan.*') ? 'active' : '' }}">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                Daftar Ruangan
            </a>
            
            <div class="sidebar-heading mt-4">Aktivitas</div>
            <a href="{{ route('mahasiswa.reservasi.index') }}" class="sidebar-link {{ request()->routeIs('mahasiswa.reservasi.*') ? 'active' : '' }}">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                Pengajuan Saya
            </a>

        @elseif ($activeRole === 'ssc')
            {{-- SSC ROLE --}}
            <div class="sidebar-heading">Menu Utama</div>
            <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                Beranda
            </a>
            
            <div class="sidebar-heading mt-4">Operasional</div>
            <a href="{{ route('ssc.approval.index') }}" class="sidebar-link {{ request()->routeIs('ssc.approval.*') ? 'active' : '' }}">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><path d="M9 15l2 2 4-4"></path></svg>
                Verifikasi Pengajuan
            </a>
            <a href="{{ route('calendar.index') }}" class="sidebar-link {{ request()->routeIs('calendar.*') ? 'active' : '' }}">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                Kalender Ruangan
            </a>

        @elseif ($activeRole === 'logistik')
            {{-- LOGISTIK ROLE --}}
            <div class="sidebar-heading">Menu Utama</div>
            <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                Beranda
            </a>
            
            <div class="sidebar-heading mt-4">Operasional</div>
            <a href="{{ route('logistik.approval.index') }}" class="sidebar-link {{ request()->routeIs('logistik.approval.*') ? 'active' : '' }}">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                Persetujuan Peminjaman
            </a>

            <div class="sidebar-heading mt-4">Data Master</div>
            <a href="{{ route('logistik.ruangan.index') }}" class="sidebar-link {{ request()->routeIs('logistik.ruangan.*') ? 'active' : '' }}">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                Kelola Ruangan
            </a>
            <a href="{{ route('logistik.jadwal.index') }}" class="sidebar-link {{ request()->routeIs('logistik.jadwal.*') ? 'active' : '' }}">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                Jadwal Akademik
            </a>
            
            <div class="sidebar-heading mt-4">Sistem</div>
            <a href="{{ route('log.index') }}" class="sidebar-link {{ request()->routeIs('log.*') ? 'active' : '' }}">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21.5 2v6h-6M2.13 15.57a10 10 0 1 0 1.49-9.14L2.5 8"></path><line x1="12" y1="12" x2="12" y2="16"></line><line x1="12" y1="12" x2="16" y2="12"></line></svg>
                Log Aktivitas
            </a>
        @endif
    </nav>
</aside>
