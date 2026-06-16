<section>
    <form method="post" action="{{ route('password.update') }}" novalidate>
        @csrf
        @method('put')

        <div class="mb-4">
            <label for="update_password_current_password" class="form-label">Kata Sandi Saat Ini</label>
            <input id="update_password_current_password" name="current_password" type="password"
                class="form-control teluroom-input @if($errors->updatePassword->get('current_password')) is-invalid @endif"
                autocomplete="current-password"
                placeholder="Kata sandi aktif Anda">
            @if($errors->updatePassword->get('current_password'))
                <div class="invalid-feedback">{{ $errors->updatePassword->get('current_password')[0] }}</div>
            @endif
        </div>

        <div class="mb-4">
            <label for="update_password_password" class="form-label">Kata Sandi Baru</label>
            <input id="update_password_password" name="password" type="password"
                class="form-control teluroom-input @if($errors->updatePassword->get('password')) is-invalid @endif"
                autocomplete="new-password"
                placeholder="Minimal 8 karakter">
            @if($errors->updatePassword->get('password'))
                <div class="invalid-feedback">{{ $errors->updatePassword->get('password')[0] }}</div>
            @endif
        </div>

        <div class="mb-5">
            <label for="update_password_password_confirmation" class="form-label">Konfirmasi Kata Sandi Baru</label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password"
                class="form-control teluroom-input @if($errors->updatePassword->get('password_confirmation')) is-invalid @endif"
                autocomplete="new-password"
                placeholder="Ulangi kata sandi baru">
            @if($errors->updatePassword->get('password_confirmation'))
                <div class="invalid-feedback">{{ $errors->updatePassword->get('password_confirmation')[0] }}</div>
            @endif
        </div>

        <div class="d-flex align-items-center justify-content-between pt-3 border-top gap-3 flex-wrap">
            @if (session('status') === 'password-updated')
                <div class="d-flex align-items-center gap-2 text-success" style="font-size: 0.875rem;">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                    Kata sandi berhasil diubah.
                </div>
            @else
                <span></span>
            @endif
            <button type="submit" class="btn btn-dark d-flex align-items-center gap-2 ms-auto" style="height: 48px; padding: 0 24px;">
                Ubah Kata Sandi
            </button>
        </div>

    </form>
</section>