<x-guest-layout>
    <div class="teluroom-auth-card">
        <div class="text-center mb-4 pb-2">
            <h1 class="auth-title mb-2">Lupa Kata Sandi</h1>
            <p class="auth-subheading mb-0">Masukkan alamat email Anda dan kami akan mengirimkan tautan untuk mengatur ulang kata sandi.</p>
        </div>

        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('password.email') }}" novalidate>
            @csrf

            <div class="mb-4">
                <label for="email" class="form-label">Alamat Email</label>
                <input id="email" type="email" name="email"
                    class="form-control @error('email') is-invalid @enderror"
                    value="{{ old('email') }}" required autofocus>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary w-100 mb-4">
                Kirim Tautan Reset
            </button>
            
            <div class="text-center">
                <a href="{{ route('login') }}" class="text-secondary text-decoration-none" style="font-size: 0.8125rem;">
                    &larr; Kembali ke Login
                </a>
            </div>
        </form>
    </div>
</x-guest-layout>