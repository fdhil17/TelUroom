<x-guest-layout>
    <div class="teluroom-auth-card">
        <div class="text-center mb-4 pb-2">
            <h1 class="auth-title mb-2">Konfirmasi Kata Sandi</h1>
            <p class="auth-subheading mb-0">Area ini merupakan bagian aman dari aplikasi. Silakan konfirmasi kata sandi Anda sebelum melanjutkan.</p>
        </div>

        <form method="POST" action="{{ route('password.confirm') }}" novalidate>
            @csrf

            <div class="mb-4">
                <label for="password" class="form-label">Kata Sandi</label>
                <div class="input-group">
                    <input id="password" type="password" name="password"
                        class="form-control @error('password') is-invalid @enderror"
                        required autocomplete="current-password" autofocus>
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

            <button type="submit" class="btn btn-primary w-100">
                Konfirmasi
            </button>
        </form>
    </div>
</x-guest-layout>