<x-app-layout>
    <x-slot name="breadcrumb">
        <div class="breadcrumb-item">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><path d="M9 15l2 2 4-4"></path></svg>
            <a href="#">Operasional</a>
        </div>
        <div class="breadcrumb-item">
            <span class="separator">/</span>
            <a href="{{ route('ssc.approval.index') }}">Verifikasi Pengajuan</a>
        </div>
        <div class="breadcrumb-item active">
            <span class="separator">/</span>
            Detail
        </div>
    </x-slot>

    <x-slot name="header">
        Tinjauan Pengajuan Peminjaman
    </x-slot>

    <div class="row g-4">
        {{-- Kiri: Detail Pengajuan (70%) --}}
        <div class="col-lg-8">
            <div class="teluroom-card mb-4">
                <div class="card-body p-4 p-md-5">
                    <h5 class="fw-bold text-dark mb-4 pb-3 border-bottom">Informasi Pengajuan</h5>
                    
                    <div class="row g-4 mb-4">
                        <div class="col-sm-6">
                            <span class="text-secondary d-block mb-1" style="font-size: 0.8125rem; font-weight: 600;">PEMINJAM</span>
                            <span class="fw-medium text-dark d-block">{{ $reservasi->user->name }}</span>
                            <span class="text-secondary" style="font-size: 0.875rem;">{{ $reservasi->user->nim ?? '-' }} &middot; {{ $reservasi->user->prodi ?? '-' }}</span>
                        </div>
                        <div class="col-sm-6">
                            <span class="text-secondary d-block mb-1" style="font-size: 0.8125rem; font-weight: 600;">STATUS SAAT INI</span>
                            <div class="mt-1">
                                <x-status-badge :status="$reservasi->status" />
                            </div>
                        </div>
                    </div>

                    <div class="row g-4 mb-4">
                        <div class="col-sm-6">
                            <span class="text-secondary d-block mb-1" style="font-size: 0.8125rem; font-weight: 600;">RUANGAN</span>
                            <span class="fw-medium text-dark d-block">{{ $reservasi->ruangan->kode_ruangan }}</span>
                            <span class="text-secondary" style="font-size: 0.875rem;">{{ $reservasi->ruangan->nama_ruangan }} (Lt. {{ $reservasi->ruangan->lantai }})</span>
                        </div>
                        <div class="col-sm-6">
                            <span class="text-secondary d-block mb-1" style="font-size: 0.8125rem; font-weight: 600;">WAKTU PEMINJAMAN</span>
                            <span class="fw-medium text-dark d-block">{{ $reservasi->tanggal_reservasi->format('d F Y') }}</span>
                            <span class="text-secondary" style="font-size: 0.875rem;">{{ substr($reservasi->jam_mulai, 0, 5) }} - {{ substr($reservasi->jam_selesai, 0, 5) }}</span>
                        </div>
                    </div>

                    <div class="mb-2">
                        <span class="text-secondary d-block mb-2" style="font-size: 0.8125rem; font-weight: 600;">NAMA KEGIATAN / KEPERLUAN</span>
                        <div class="p-3 bg-light rounded text-dark" style="font-size: 0.9375rem; border: 1px solid #E5E7EB;">
                            {{ $reservasi->kegiatan }}
                        </div>
                    </div>
                </div>
            </div>
            
            <a href="{{ route('ssc.approval.index') }}" class="btn btn-light border px-4 d-inline-flex align-items-center" style="height: 48px;">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="me-2"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                Kembali ke Antrian
            </a>
        </div>

        {{-- Kanan: Panel Verifikasi (30%) --}}
        <div class="col-lg-4">
            @if ($reservasi->status === 'menunggu_ssc')
                <div class="teluroom-card border-dark shadow-sm">
                    <div class="card-body p-4">
                        <h5 class="fw-bold text-dark mb-4 pb-3 border-bottom">Panel Verifikasi</h5>

                        <form method="POST" action="{{ route('ssc.approval.process', $reservasi) }}" id="formApproval" novalidate>
                            @csrf
                            <input type="hidden" name="action" id="actionInput">

                            <div class="mb-4">
                                <label for="catatan_ssc" class="form-label">Catatan SSC</label>
                                <textarea name="catatan_ssc" id="catatan_ssc" rows="4"
                                    class="form-control teluroom-input @error('catatan_ssc') is-invalid @enderror"
                                    placeholder="Tulis alasan jika menolak (Opsional jika setuju)">{{ old('catatan_ssc') }}</textarea>
                                @error('catatan_ssc')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex flex-column gap-2">
                                <button type="button" class="btn btn-dark w-100 d-flex justify-content-center align-items-center" style="height: 48px;"
                                    data-bs-toggle="modal" data-bs-target="#confirmModal"
                                    data-action="approve"
                                    data-title="Teruskan Pengajuan"
                                    data-message="Pengajuan ini akan diteruskan ke Logistik untuk persetujuan akhir. Lanjutkan?"
                                    data-btn-class="btn-dark"
                                    data-btn-text="Ya, Teruskan">
                                    Verifikasi & Teruskan
                                </button>
                                <button type="button" class="btn btn-outline-danger w-100 d-flex justify-content-center align-items-center" style="height: 48px;"
                                    data-bs-toggle="modal" data-bs-target="#confirmModal"
                                    data-action="reject"
                                    data-title="Tolak Pengajuan"
                                    data-message="Apakah Anda yakin ingin menolak pengajuan peminjaman ini secara permanen?"
                                    data-btn-class="btn-danger"
                                    data-btn-text="Tolak">
                                    Tolak Pengajuan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @else
                <div class="teluroom-card bg-light border-0">
                    <div class="card-body p-4">
                        <h5 class="fw-bold text-dark mb-3">Status Verifikasi</h5>
                        <p class="text-secondary" style="font-size: 0.875rem;">Pengajuan ini sudah tidak memerlukan verifikasi Anda.</p>
                        @if ($reservasi->catatan_ssc)
                            <div class="mt-4">
                                <span class="text-secondary d-block mb-2" style="font-size: 0.8125rem; font-weight: 600;">CATATAN SSC</span>
                                <div class="p-3 bg-white rounded border text-dark" style="font-size: 0.875rem;">
                                    {{ $reservasi->catatan_ssc }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- Modal Konfirmasi --}}
    <div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
                <div class="modal-body p-4 text-center">
                    <h5 class="fw-bold mb-2 text-dark" id="confirmModalTitle">Konfirmasi</h5>
                    <p class="text-secondary mb-4" id="confirmModalMessage" style="font-size: 0.875rem;">Apakah Anda yakin?</p>
                    <div class="d-flex gap-2 w-100">
                        <button type="button" class="btn btn-light w-50" data-bs-dismiss="modal">Batal</button>
                        <button type="button" class="btn w-50" id="confirmModalBtn">Ya</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const modal = document.getElementById('confirmModal');
            if(modal) {
                modal.addEventListener('show.bs.modal', function (event) {
                    const btn = event.relatedTarget;
                    document.getElementById('confirmModalTitle').textContent = btn.getAttribute('data-title');
                    document.getElementById('confirmModalMessage').textContent = btn.getAttribute('data-message');
                    const confirmBtn = document.getElementById('confirmModalBtn');
                    confirmBtn.className = 'btn ' + btn.getAttribute('data-btn-class') + ' w-50';
                    confirmBtn.textContent = btn.getAttribute('data-btn-text');
                    confirmBtn.onclick = function () {
                        document.getElementById('actionInput').value = btn.getAttribute('data-action');
                        document.getElementById('formApproval').submit();
                    };
                });
            }
        });
    </script>
</x-app-layout>