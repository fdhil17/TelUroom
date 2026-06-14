<section>
    <p class="text-muted mb-4" style="font-size: 0.9rem;">
        Pastikan akun Anda menggunakan password yang panjang dan acak agar tetap aman.
    </p>

    <form method="post" action="{{ route('password.update') }}">
        @csrf
        @method('put')

        <div class="mb-3">
            <label for="update_password_current_password" class="form-label">Password Saat Ini</label>
            <input id="update_password_current_password" name="current_password" type="password"
                class="form-control @if($errors->updatePassword->get('current_password')) is-invalid @endif"
                autocomplete="current-password">
            @if($errors->updatePassword->get('current_password'))
                <div class="invalid-feedback">
                    {{ $errors->updatePassword->get('current_password')[0] }}
                </div>
            @endif
        </div>

        <div class="mb-3">
            <label for="update_password_password" class="form-label">Password Baru</label>
            <input id="update_password_password" name="password" type="password"
                class="form-control @if($errors->updatePassword->get('password')) is-invalid @endif"
                autocomplete="new-password">
            @if($errors->updatePassword->get('password'))
                <div class="invalid-feedback">
                    {{ $errors->updatePassword->get('password')[0] }}
                </div>
            @endif
        </div>

        <div class="mb-4">
            <label for="update_password_password_confirmation" class="form-label">Konfirmasi Password Baru</label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password"
                class="form-control @if($errors->updatePassword->get('password_confirmation')) is-invalid @endif"
                autocomplete="new-password">
            @if($errors->updatePassword->get('password_confirmation'))
                <div class="invalid-feedback">
                    {{ $errors->updatePassword->get('password_confirmation')[0] }}
                </div>
            @endif
        </div>

        <div class="d-flex align-items-center gap-3">
            <button type="submit" class="btn btn-primary">Ubah Password</button>
            @if (session('status') === 'password-updated')
                <span class="text-success" style="font-size: 0.875rem;">
                    ✓ Password berhasil diubah.
                </span>
            @endif
        </div>

    </form>
</section>
