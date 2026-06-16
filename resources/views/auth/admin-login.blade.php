<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="teluroom-auth-card">
        <div class="text-center mb-4 pb-2">
            <h1 class="auth-title mb-2">Login Pengelola</h1>
            <p class="auth-subheading mb-0">Masuk sebagai Admin atau SSC</p>
        </div>

        <form method="POST" action="{{ route('admin.login.post') }}" novalidate>
            @csrf

            <div class="mb-4">
                <label for="email" class="form-label">Alamat Email</label>
                <input id="email" type="email" name="email"
                    class="form-control @error('email') is-invalid @enderror"
                    value="{{ old('email') }}" placeholder="admin@teluroom.com" required autofocus>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label for="password" class="form-label">Kata Sandi</label>
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

            <div class="mb-5">
                <label class="form-label mb-3">Login Sebagai</label>
                <div class="d-flex gap-3">
                    <div class="teluroom-role-option" id="optionSsc">
                        <input type="radio" name="login_as" id="loginSsc" value="ssc"
                            {{ old('login_as') === 'ssc' ? 'checked' : '' }} required>
                        <label for="loginSsc" class="w-100">
                            <span class="role-name">SSC</span>
                            <span class="role-desc">{{ __('app.ssc_full') }}</span>
                        </label>
                    </div>
                    <div class="teluroom-role-option" id="optionLogistik">
                        <input type="radio" name="login_as" id="loginLogistik" value="logistik"
                            {{ old('login_as') === 'logistik' ? 'checked' : '' }}>
                        <label for="loginLogistik" class="w-100">
                            <span class="role-name">Logistik</span>
                            <span class="role-desc">Pengelola Fasilitas</span>
                        </label>
                    </div>
                </div>
                @error('login_as')
                    <div class="text-danger mt-2" style="font-size: 0.8rem;">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary w-100 mb-4">
                Masuk Pengelola
            </button>

            <hr>

            <div class="text-center">
                <a href="{{ route('login') }}" class="text-secondary text-decoration-none" style="font-size: 0.8125rem;">
                    &larr; Kembali ke Login Mahasiswa
                </a>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const radios = document.querySelectorAll('input[name="login_as"]');
            
            function updateActiveState() {
                document.querySelectorAll('.teluroom-role-option').forEach(el => {
                    el.classList.remove('active');
                });
                
                const checked = document.querySelector('input[name="login_as"]:checked');
                if (checked) {
                    checked.closest('.teluroom-role-option').classList.add('active');
                }
            }
            
            radios.forEach(radio => {
                radio.addEventListener('change', updateActiveState);
            });
            
            // Initialize
            updateActiveState();
        });
    </script>
</x-guest-layout>