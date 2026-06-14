<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'TelUroom') }}</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body style="background: linear-gradient(135deg, #8B1A2B 0%, #2B2B2B 100%); min-height: 100vh;">
    <div class="d-flex flex-column align-items-center justify-content-center min-vh-100 py-4">

        {{-- Brand --}}
        <div class="mb-4 text-center">
            <a href="/" class="text-decoration-none">
                <h1 class="fw-bold text-white mb-0">TelUroom</h1>
                <p class="text-white-50 small mb-0">{{ __('app.tagline_login') }}</p>
            </a>
        </div>

        {{-- Language Switcher --}}
        <div class="d-flex justify-content-end mb-2" style="width: 24rem; max-width: calc(100vw - 2rem);">
            <div class="dropdown">
                <a class="dropdown-toggle d-flex align-items-center gap-2 text-white text-decoration-none" href="#" role="button" data-bs-toggle="dropdown">
                    @if(app()->getLocale() === 'id')
                        <img src="https://flagcdn.com/w20/id.png" width="20" alt="ID" style="border-radius:2px;">
                        <span style="font-size:0.85rem; font-weight:600;">ID</span>
                    @else
                        <img src="https://flagcdn.com/w20/gb.png" width="20" alt="EN" style="border-radius:2px;">
                        <span style="font-size:0.85rem; font-weight:600;">EN</span>
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
            </div>
        </div>

        {{-- Card --}}
        <div class="card shadow-lg" style="width: 24rem; border-radius: 0.75rem;">
            <div class="card-body p-4">
                {{ $slot }}
            </div>
        </div>

        <p class="text-white-50 small mt-4 mb-0">&copy; {{ date('Y') }} TelUroom - Telkom University Surabaya</p>
    </div>
</body>
</html>
