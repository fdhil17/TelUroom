<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <h5 class="fw-bold text-center mb-1">{{ __('app.login_mahasiswa') }}</h5>
    <p class="text-muted text-center mb-4" style="font-size: 0.85rem;">{{ __('app.tagline_mahasiswa') }}</p>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-3">
            <label for="email" class="form-label">{{ __('app.email') }}</label>
            <input id="email" type="email" name="email"
                class="form-control @error('email') is-invalid @enderror"
                value="{{ old('email') }}" placeholder="email@gmail.com" required autofocus>
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

        <div class="mb-4 form-check">
            <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
            <label class="form-check-label text-muted" for="remember_me" style="font-size: 0.875rem;">
                {{ __('app.ingat_saya') }}
            </label>
        </div>

        <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
            {{ __('app.masuk') }}
        </button>

        @if (Route::has('password.request'))
            <div class="text-center mt-3">
                <a class="text-muted" href="{{ route('password.request') }}" style="font-size: 0.85rem;">
                    {{ __('app.lupa_password') }}
                </a>
            </div>
        @endif

        <hr style="border-color: #E4E7EF; margin: 1.25rem 0;">

        <div class="text-center">
            <p class="text-muted mb-1" style="font-size: 0.85rem;">
                {{ __('app.belum_punya_akun') }} <a href="{{ route('register') }}" class="text-primary fw-semibold">{{ __('app.daftar_disini') }}</a>
            </p>
            <p class="text-muted mb-0" style="font-size: 0.85rem;">
                {{ __('app.admin_label') }} <a href="{{ route('admin.login') }}" class="text-primary fw-semibold">{{ __('app.admin_login_link') }}</a>
            </p>
        </div>

    </form>
</x-guest-layout>
