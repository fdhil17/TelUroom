<section>
    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" novalidate>
        @csrf
        @method('patch')

        <div class="mb-4">
            <label for="name" class="form-label">Nama Lengkap</label>
            <input id="name" name="name" type="text"
                class="form-control teluroom-input @error('name') is-invalid @enderror"
                value="{{ old('name', $user->name) }}"
                required autofocus autocomplete="name"
                placeholder="Nama lengkap Anda">
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-5">
            <label for="email" class="form-label">Alamat Email</label>
            <input id="email" name="email" type="email"
                class="form-control teluroom-input @error('email') is-invalid @enderror"
                value="{{ old('email', $user->email) }}"
                required autocomplete="username"
                placeholder="email@example.com">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-3 p-3 rounded" style="background-color: #FFFBEB; border: 1px solid #FDE68A;">
                    <p class="text-warning-emphasis mb-1" style="font-size: 0.875rem; font-weight: 600;">Email belum diverifikasi.</p>
                    <button form="send-verification" class="btn btn-link p-0 text-dark text-decoration-underline" style="font-size: 0.875rem;">
                        Kirim ulang email verifikasi
                    </button>
                    @if (session('status') === 'verification-link-sent')
                        <p class="text-success mb-0 mt-1" style="font-size: 0.8125rem;">
                            Link verifikasi baru telah dikirim ke email Anda.
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="d-flex align-items-center justify-content-between pt-3 border-top gap-3 flex-wrap">
            @if (session('status') === 'profile-updated')
                <div class="d-flex align-items-center gap-2 text-success" style="font-size: 0.875rem;">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                    Profil berhasil disimpan.
                </div>
            @else
                <span></span>
            @endif
            <button type="submit" class="btn btn-dark d-flex align-items-center gap-2 ms-auto" style="height: 48px; padding: 0 24px;">
                Simpan Perubahan
            </button>
        </div>

    </form>
</section>