<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __('app.admin_login_title') }}</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body style="background: linear-gradient(135deg, #8B1A2B 0%, #2B2B2B 100%); min-height: 100vh;">
    <div class="d-flex flex-column align-items-center justify-content-center min-vh-100 py-4">

        {{-- Brand --}}
        <div class="mb-4 text-center">
            <a href="/" class="text-decoration-none">
                <h1 class="fw-bold text-white mb-0">TelUroom</h1>
                <p class="text-white-50 small mb-0">{{ __('app.portal_admin') }} · Telkom University Surabaya</p>
            </a>
        </div>

        {{-- Language Switcher --}}
        <div class="d-flex justify-content-end mb-2" style="width: 26rem; max-width: calc(100vw - 2rem);">
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
        <div class="card shadow-lg" style="width: 26rem; border-radius: 0.75rem;">
            <div class="card-body p-4">

                <h5 class="fw-bold text-center mb-1">{{ __('app.login_admin') }}</h5>
                <p class="text-muted text-center mb-4" style="font-size: 0.85rem;">{{ __('app.tagline_admin') }}</p>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.login.post') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="email" class="form-label">{{ __('app.email_admin') }}</label>
                        <input id="email" type="email" name="email"
                            class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email') }}" placeholder="admin@teluroom.com" required autofocus>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">{{ __('app.password') }}</label>
                        <input id="password" type="password" name="password"
                            class="form-control @error('password') is-invalid @enderror"
                            placeholder="••••••••" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label">{{ __('app.login_sebagai') }}</label>
                        <div class="d-flex gap-3">
                            <div class="auth-role-option flex-fill @error('login_as') border-danger @enderror" id="optionSsc">
                                <input class="form-check-input" type="radio" name="login_as" id="loginSsc" value="ssc"
                                    {{ old('login_as') === 'ssc' ? 'checked' : '' }} required>
                                <label class="form-check-label w-100" for="loginSsc">
                                    <span class="fw-bold d-block">SSC</span>
                                    <small class="text-muted fw-normal">{{ __('app.ssc_full') }}</small>
                                </label>
                            </div>
                            <div class="auth-role-option flex-fill @error('login_as') border-danger @enderror" id="optionLogistik">
                                <input class="form-check-input" type="radio" name="login_as" id="loginLogistik" value="logistik"
                                    {{ old('login_as') === 'logistik' ? 'checked' : '' }}>
                                <label class="form-check-label w-100" for="loginLogistik">
                                    <span class="fw-bold d-block">{{ __('app.logistik_label') }}</span>
                                    <small class="text-muted fw-normal">{{ __('app.logistik_desc') }}</small>
                                </label>
                            </div>
                        </div>
                        @error('login_as')
                            <div class="text-danger mt-1" style="font-size: 0.875rem;">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
                        {{ __('app.btn_login_admin') }}
                    </button>
                </form>

                <hr style="border-color: #E4E7EF; margin: 1.25rem 0;">

                <div class="text-center">
                    <p class="text-muted mb-0" style="font-size: 0.85rem;">
                        {{ __('app.mahasiswa_label') }} <a href="{{ route('login') }}" class="text-primary fw-semibold">{{ __('app.login_disini') }}</a>
                    </p>
                </div>

            </div>
        </div>

        <p class="text-white-50 small mt-4 mb-0">&copy; {{ date('Y') }} TelUroom - Telkom University Surabaya</p>
    </div>

    <script>
        document.querySelectorAll('input[name="login_as"]').forEach(function(radio) {
            radio.addEventListener('change', function() {
                document.querySelectorAll('.auth-role-option').forEach(el => {
                    el.classList.remove('active');
                });
                this.closest('.auth-role-option').classList.add('active');
            });
            if (radio.checked) {
                radio.closest('.auth-role-option').classList.add('active');
            }
        });
    </script>
</body>
</html>
