<x-guest-layout>
    <div class="teluroom-auth-card">
        <div class="text-center mb-4 pb-2">
            <h1 class="auth-title mb-2">Verifikasi Email</h1>
            <p class="auth-subheading mb-0">Terima kasih telah mendaftar! Sebelum memulai, mohon verifikasi alamat email Anda melalui tautan yang baru saja kami kirimkan.</p>
        </div>

        @if (session('status') == 'verification-link-sent')
            <div class="mb-4 font-medium text-sm text-green-600" style="color: #059669; font-size: 0.875rem;">
                Tautan verifikasi baru telah dikirim ke alamat email yang Anda berikan saat registrasi.
            </div>
        @else
            <p class="auth-subheading text-center mb-4">
                Jika Anda tidak menerima email tersebut, kami dapat mengirimkan ulang.
            </p>
        @endif

        <div class="d-flex flex-column gap-3 mt-2">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="btn btn-primary w-100">
                    Kirim Ulang Tautan Verifikasi
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}" class="text-center">
                @csrf
                <button type="submit" class="btn btn-link text-secondary text-decoration-none" style="font-size: 0.8125rem;">
                    Keluar (Log Out)
                </button>
            </form>
        </div>
    </div>
</x-guest-layout>