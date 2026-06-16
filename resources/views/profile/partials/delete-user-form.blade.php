<section>
    <div class="d-flex align-items-start gap-4 flex-wrap flex-md-nowrap">
        <div class="flex-grow-1">
            <p class="text-secondary mb-0" style="font-size: 0.875rem; line-height: 1.7;">
                Setelah akun dihapus, semua data akan hilang secara permanen termasuk seluruh riwayat pengajuan peminjaman.
                Pastikan Anda telah mengunduh semua data penting sebelum melanjutkan.
            </p>
        </div>
        <div class="flex-shrink-0">
            <button type="button" class="btn btn-outline-danger d-flex align-items-center gap-2"
                style="height: 44px; white-space: nowrap;"
                data-bs-toggle="modal" data-bs-target="#confirmDeleteAccountModal">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                Hapus Akun Saya
            </button>
        </div>
    </div>

    {{-- Modal Konfirmasi Hapus Akun --}}
    <div class="modal fade" id="confirmDeleteAccountModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
                <div class="modal-body p-4">
                    <div class="text-center mb-4">
                        <div class="d-inline-flex align-items-center justify-content-center bg-danger bg-opacity-10 text-danger rounded-circle mb-3" style="width: 56px; height: 56px;">
                            <svg width="28" height="28" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                        </div>
                        <h5 class="fw-bold text-dark mb-1">Hapus Akun?</h5>
                        <p class="text-secondary mb-0" style="font-size: 0.8125rem;">Tindakan ini permanen dan tidak dapat dibatalkan. Masukkan kata sandi untuk melanjutkan.</p>
                    </div>

                    <form method="post" action="{{ route('profile.destroy') }}" novalidate>
                        @csrf
                        @method('delete')

                        <div class="mb-4">
                            <label for="delete_password" class="form-label">Kata Sandi</label>
                            <input id="delete_password" name="password" type="password"
                                class="form-control teluroom-input @if($errors->userDeletion->get('password')) is-invalid @endif"
                                placeholder="Masukkan kata sandi Anda">
                            @if($errors->userDeletion->get('password'))
                                <div class="invalid-feedback">{{ $errors->userDeletion->get('password')[0] }}</div>
                            @endif
                        </div>

                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-light border w-50" data-bs-dismiss="modal" style="height: 44px;">Batal</button>
                            <button type="submit" class="btn btn-danger w-50" style="height: 44px;">Ya, Hapus</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>