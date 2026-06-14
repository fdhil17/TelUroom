<x-app-layout>
    <x-slot name="header">
        <h2 class="fs-4 fw-bold text-dark">Proses Persetujuan Akhir</h2>
    </x-slot>

    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">

                @php
                    $statusBadge = [
                        'menunggu_ssc'      => 'bg-warning text-dark',
                        'menunggu_logistik' => 'bg-info text-dark',
                        'disetujui'         => 'bg-success',
                        'ditolak_ssc'       => 'bg-danger',
                        'ditolak_logistik'  => 'bg-danger',
                    ];
                    $statusLabel = [
                        'menunggu_ssc'      => 'Menunggu Verifikasi SSC',
                        'menunggu_logistik' => 'Menunggu Persetujuan Logistik',
                        'disetujui'         => 'Disetujui',
                        'ditolak_ssc'       => 'Ditolak oleh SSC',
                        'ditolak_logistik'  => 'Ditolak oleh Logistik',
                    ];
                @endphp

                {{-- Info Reservasi --}}
                <div class="card mb-4">
                    <div class="card-header fw-bold card-header-dark">Informasi Pengajuan Peminjaman</div>
                    <div class="card-body p-4">
                        <div class="detail-grid">
                            <div class="detail-item">
                                <span class="detail-label">Nama Mahasiswa</span>
                                <span class="detail-value">{{ $reservasi->user->name }}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">NIM</span>
                                <span class="detail-value">{{ $reservasi->user->nim ?? '-' }}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Program Studi</span>
                                <span class="detail-value">{{ $reservasi->user->prodi ?? '-' }}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Email</span>
                                <span class="detail-value">{{ $reservasi->user->email }}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Ruangan</span>
                                <span class="detail-value">{{ $reservasi->ruangan->kode_ruangan }} — {{ $reservasi->ruangan->nama_ruangan }}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Tanggal</span>
                                <span class="detail-value">{{ $reservasi->tanggal_reservasi->format('d M Y') }}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Jam</span>
                                <span class="detail-value">{{ substr($reservasi->jam_mulai, 0, 5) }} – {{ substr($reservasi->jam_selesai, 0, 5) }}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Keperluan</span>
                                <span class="detail-value">{{ $reservasi->keperluan }}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Catatan SSC</span>
                                <span class="detail-value">{{ $reservasi->catatan_ssc ?? '-' }}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Status</span>
                                <span class="detail-value">
                                    <span class="badge {{ $statusBadge[$reservasi->status] }}">
                                        {{ $statusLabel[$reservasi->status] }}
                                    </span>
                                </span>
                            </div>
                        </div>

                        @if ($reservasi->status === 'disetujui' && $reservasi->qr_code)
                            <hr style="border-color: #E4E7EF;" class="my-4">
                            <div class="text-center">
                                <p class="fw-semibold mb-3 text-muted" style="font-size:0.85rem; text-transform:uppercase; letter-spacing:0.06em;">QR Code Peminjaman</p>
                                <img src="{{ asset('storage/' . $reservasi->qr_code) }}" alt="QR Code" style="width:180px; border-radius:0.5rem;">
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Form Approval --}}
                @if ($reservasi->status === 'menunggu_logistik')
                    <div class="card mb-4">
                        <div class="card-header fw-bold card-header-dark">Proses Persetujuan Final</div>
                        <div class="card-body p-4">

                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form method="POST" action="{{ route('logistik.approval.process', $reservasi) }}" id="formApproval">
                                @csrf
                                <input type="hidden" name="action" id="actionInput">

                                <div class="mb-4">
                                    <label for="catatan_logistik" class="form-label">Catatan <span class="text-muted fw-normal">(opsional jika setuju, wajib jika tolak)</span></label>
                                    <textarea name="catatan_logistik" id="catatan_logistik" rows="3"
                                        class="form-control @error('catatan_logistik') is-invalid @enderror">{{ old('catatan_logistik') }}</textarea>
                                    @error('catatan_logistik')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="d-flex justify-content-end gap-2">
                                    <button type="button" class="btn btn-danger"
                                        data-bs-toggle="modal" data-bs-target="#confirmModal"
                                        data-action="reject"
                                        data-title="Tolak Pengajuan"
                                        data-message="Apakah Anda yakin ingin menolak pengajuan peminjaman ini?"
                                        data-btn-class="btn-danger"
                                        data-btn-text="Tolak">
                                        Tolak
                                    </button>
                                    <button type="button" class="btn btn-success"
                                        data-bs-toggle="modal" data-bs-target="#confirmModal"
                                        data-action="approve"
                                        data-title="Setujui & Buat QR Code"
                                        data-message="QR Code peminjaman akan dibuat. Lanjutkan?"
                                        data-btn-class="btn-success"
                                        data-btn-text="Setujui">
                                        Setujui & Buat QR Code
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                @else
                    @if ($reservasi->catatan_logistik)
                        <div class="alert alert-info">
                            <strong>Catatan Logistik:</strong> {{ $reservasi->catatan_logistik }}
                        </div>
                    @endif
                @endif

                <div class="mt-2">
                    <a href="{{ route('logistik.approval.index') }}" class="btn btn-secondary">← Kembali</a>
                </div>

            </div>
        </div>
    </div>

    {{-- Modal Konfirmasi --}}
    <div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius:0.875rem;">
                <div class="modal-header" style="border-radius:0.875rem 0.875rem 0 0;">
                    <h5 class="modal-title fw-bold" id="confirmModalTitle">Konfirmasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="confirmModalMessage">Apakah Anda yakin?</div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn" id="confirmModalBtn">Ya</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.getElementById('confirmModal').addEventListener('show.bs.modal', function (event) {
                const btn = event.relatedTarget;
                document.getElementById('confirmModalTitle').textContent = btn.getAttribute('data-title');
                document.getElementById('confirmModalMessage').textContent = btn.getAttribute('data-message');
                const confirmBtn = document.getElementById('confirmModalBtn');
                confirmBtn.className = 'btn ' + btn.getAttribute('data-btn-class');
                confirmBtn.textContent = btn.getAttribute('data-btn-text');
                confirmBtn.onclick = function () {
                    document.getElementById('actionInput').value = btn.getAttribute('data-action');
                    document.getElementById('formApproval').submit();
                };
            });
        });
    </script>
</x-app-layout>
