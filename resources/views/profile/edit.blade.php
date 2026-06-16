<x-app-layout>
    <x-slot name="breadcrumb">
        <div class="breadcrumb-item">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg>
            <a href="#">Pengaturan</a>
        </div>
        <div class="breadcrumb-item active">
            <span class="separator">/</span>
            Profil
        </div>
    </x-slot>

    <x-slot name="header">
        Pengaturan Profil
    </x-slot>

    <div class="d-flex justify-content-center">
        <div style="width: 100%; max-width: 800px;">

            {{-- Informasi Profil --}}
            <div class="teluroom-card mb-4">
                <div class="card-body p-4 p-md-5">
                    <div class="mb-4 pb-3 border-bottom">
                        <h6 class="fw-bold text-dark mb-1" style="font-size: 0.8125rem; letter-spacing: 0.06em; text-transform: uppercase;">Informasi Profil</h6>
                        <p class="text-secondary mb-0" style="font-size: 0.875rem;">Perbarui nama dan alamat email akun Anda.</p>
                    </div>
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            {{-- Ubah Password --}}
            <div class="teluroom-card mb-4">
                <div class="card-body p-4 p-md-5">
                    <div class="mb-4 pb-3 border-bottom">
                        <h6 class="fw-bold text-dark mb-1" style="font-size: 0.8125rem; letter-spacing: 0.06em; text-transform: uppercase;">Ubah Kata Sandi</h6>
                        <p class="text-secondary mb-0" style="font-size: 0.875rem;">Pastikan akun Anda menggunakan kata sandi yang kuat dan unik.</p>
                    </div>
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            {{-- Danger Zone --}}
            <div class="teluroom-card mb-4" style="border: 1px solid #FCA5A5;">
                <div class="card-body p-4 p-md-5">
                    <div class="mb-4 pb-3" style="border-bottom: 1px solid #FCA5A5;">
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <svg width="16" height="16" fill="none" stroke="#DC2626" stroke-width="2" viewBox="0 0 24 24"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                            <h6 class="fw-bold mb-0" style="font-size: 0.8125rem; letter-spacing: 0.06em; text-transform: uppercase; color: #DC2626;">Zona Berbahaya</h6>
                        </div>
                        <p class="text-secondary mb-0" style="font-size: 0.875rem;">Tindakan ini bersifat permanen dan tidak dapat dibatalkan.</p>
                    </div>
                    @include('profile.partials.delete-user-form')
                </div>
            </div>

        </div>
    </div>
</x-app-layout>