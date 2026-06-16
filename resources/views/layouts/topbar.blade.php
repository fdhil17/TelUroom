<header class="teluroom-topbar">
    <div class="topbar-left">
        @if (isset($breadcrumb))
            <div class="teluroom-breadcrumb">
                {{ $breadcrumb }}
            </div>
        @endif
    </div>

    <div class="topbar-right">
        @php
            $displayRole = Auth::user()->role === 'admin'
                ? strtoupper(session('admin_role', 'Admin'))
                : ucfirst(Auth::user()->role);
        @endphp
        
        <div class="dropdown teluroom-user-dropdown">
            <a href="#" class="dropdown-toggle" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <div class="user-info text-end d-none d-sm-block">
                    <span class="name">{{ Auth::user()->name }}</span>
                    <span class="role">{{ $displayRole }}</span>
                </div>
                <div class="avatar">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
            </a>
            
            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0" aria-labelledby="userDropdown">
                @if (Auth::user()->role !== 'admin')
                    <li>
                        <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="{{ route('profile.edit') }}">
                            <svg width="15" height="15" class="opacity-50" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            Pengaturan Profil
                        </a>
                    </li>
                    <li><hr class="dropdown-divider my-1"></li>
                @endif
                
                <li>
                    <form method="POST" action="{{ Auth::user()->role === 'admin' ? route('admin.logout') : route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item d-flex align-items-center gap-2 py-2 text-danger">
                            <svg width="15" height="15" class="opacity-75" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                            Keluar (Log Out)
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</header>
