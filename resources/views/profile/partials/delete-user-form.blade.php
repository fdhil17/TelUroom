<section>
    <p class="text-muted mb-4" style="font-size: 0.9rem;">
        Setelah akun dihapus, semua data akan hilang secara permanen. Pastikan Anda sudah mengunduh data penting sebelum melanjutkan.
    </p>

    <button type="button" class="btn btn-danger"
        data-bs-toggle="modal" data-bs-target="#confirmDeleteModal">
        Hapus Akun
    </button>

    {{-- Modal Konfirmasi --}}
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 0.875rem; border: 1px solid #FECACA;">
                <div class="modal-header" style="background-color: #FFF5F5; border-bottom: 1px solid #FECACA; border-radius: 0.875rem 0.875rem 0 0;">
                    <h5 class="modal-title fw-bold" id="confirmDeleteModalLabel" style="color: #991B1B;">
                        Hapus Akun?
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="post" action="{{ route('profile.destroy') }}">
                    @csrf
                    @method('delete')
                    <div class="modal-body p-4">
                        <p class="text-muted mb-4" style="font-size: 0.9rem;">
                            Tindakan ini tidak dapat dibatalkan. Masukkan password Anda untuk mengkonfirmasi penghapusan akun.
                        </p>
                        <div>
                            <label for="delete_password" class="form-label">Password</label>
                            <input id="delete_password" name="password" type="password"
                                class="form-control @if($errors->userDeletion->get('password')) is-invalid @endif"
                                placeholder="Masukkan password Anda">
                            @if($errors->userDeletion->get('password'))
                                <div class="invalid-feedback">
                                    {{ $errors->userDeletion->get('password')[0] }}
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="modal-footer" style="border-top: 1px solid #FECACA;">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Ya, Hapus Akun Saya</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</section>
