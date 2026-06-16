<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="teluroom-auth-card">
        <div class="text-center mb-4 pb-2">
            <h1 class="auth-title mb-2">Login Mahasiswa</h1>
            <p class="auth-subheading mb-0">Masuk ke akun TelURoom Anda</p>
        </div>

        <form method="POST" action="{{ route('login') }}" novalidate>
            @csrf

            <div class="mb-4">
                <label for="email" class="form-label">Alamat Email</label>
                <input id="email" type="email" name="email"
                    class="form-control @error('email') is-invalid @enderror"
                    value="{{ old('email') }}" placeholder="mahasiswa@student.telkomuniversity.ac.id" required autofocus>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <label for="password" class="form-label mb-0">Kata Sandi</label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" tabindex="-1" class="text-secondary text-decoration-none" style="font-size: 0.8125rem;">
                            Lupa Sandi?
                        </a>
                    @endif
                </div>
                <div class="input-group">
                    <input id="password" type="password" name="password"
                        class="form-control @error('password') is-invalid @enderror"
                        placeholder="••••••••" required>
                    <button class="input-group-text password-toggle" type="button" tabindex="-1">
                        <svg class="eye-icon" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                    </button>
                </div>
                @error('password')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4 form-check">
                <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
                <label class="form-check-label text-secondary" style="font-size: 0.8125rem;" for="remember_me">
                    Ingat Saya
                </label>
            </div>

            <button type="submit" class="btn btn-primary w-100 mb-4">
                Masuk
            </button>

            <div class="text-center" style="font-size: 0.875rem;">
                <span class="text-secondary">Belum punya akun?</span>
                <a href="{{ route('register') }}" class="fw-semibold text-primary text-decoration-none" style="color: #111827 !important;">Daftar Sekarang</a>
            </div>
            
            <hr>

            <div class="text-center">
                <a href="{{ route('admin.login') }}" class="text-secondary text-decoration-none" style="font-size: 0.8125rem; display: inline-flex; align-items: center; gap: 4px;">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    Login Pengelola (Admin/SSC)
                </a>
            </div>
        </form>
    </div>
</x-guest-layout>