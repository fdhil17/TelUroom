<x-app-layout>
    <x-slot name="breadcrumb">
        <div class="breadcrumb-item">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
            <a href="#">Operasional</a>
        </div>
        <div class="breadcrumb-item active">
            <span class="separator">/</span>
            Persetujuan Peminjaman
        </div>
    </x-slot>

    <x-slot name="header">
        Persetujuan Peminjaman (Logistik)
    </x-slot>

    <x-slot name="toolbar">
        <form method="GET" action="{{ route('logistik.approval.index') }}" class="d-flex flex-column flex-md-row w-100 gap-3 align-items-md-center">
            <div class="toolbar-actions ms-md-auto d-flex flex-wrap gap-2 w-100 w-md-auto justify-content-start justify-content-md-end">
                <select name="status" class="form-select" onchange="this.form.submit()" style="min-width: 170px;">
                    <option value="">Semua Status</option>
                    <option value="menunggu_logistik" @selected(request('status') === 'menunggu_logistik')>Menunggu Persetujuan</option>
                    <option value="disetujui" @selected(request('status') === 'disetujui')>Disetujui</option>
                    <option value="ditolak_logistik" @selected(request('status') === 'ditolak_logistik')>Ditolak Logistik</option>
                </select>
            </div>
        </form>
    </x-slot>

    <div class="teluroom-card mb-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 border-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4 fw-medium text-secondary" style="font-size: 0.8125rem; border-bottom: 1px solid #E5E7EB;">PEMINJAM</th>
                            <th class="fw-medium text-secondary" style="font-size: 0.8125rem; border-bottom: 1px solid #E5E7EB;">RUANGAN</th>
                            <th class="fw-medium text-secondary" style="font-size: 0.8125rem; border-bottom: 1px solid #E5E7EB;">TANGGAL & WAKTU</th>
                            <th class="fw-medium text-secondary" style="font-size: 0.8125rem; border-bottom: 1px solid #E5E7EB;">KEPERLUAN</th>
                            <th class="fw-medium text-secondary" style="font-size: 0.8125rem; border-bottom: 1px solid #E5E7EB;">STATUS</th>
                            <th class="fw-medium text-secondary text-end pe-4" style="font-size: 0.8125rem; border-bottom: 1px solid #E5E7EB;">AKSI</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @forelse ($reservasis as $reservasi)
                            <tr>
                                <td class="ps-4 py-3">
                                    <span class="fw-semibold text-dark">{{ $reservasi->user->name }}</span>
                                    <span class="text-secondary d-block" style="font-size:0.8125rem;">{{ $reservasi->user->nim ?? '-' }} &middot; {{ $reservasi->user->prodi ?? '-' }}</span>
                                </td>
                                <td class="py-3">
                                    <span class="fw-semibold text-dark">{{ $reservasi->ruangan->kode_ruangan }}</span>
                                    <span class="text-secondary d-block" style="font-size:0.8125rem;">Lt. {{ $reservasi->ruangan->lantai }}</span>
                                </td>
                                <td class="py-3 text-dark">
                                    <div>{{ $reservasi->tanggal_reservasi->format('d M Y') }}</div>
                                    <div class="text-secondary" style="font-size:0.8125rem;">
                                        {{ substr($reservasi->jam_mulai, 0, 5) }} - {{ substr($reservasi->jam_selesai, 0, 5) }}
                                    </div>
                                </td>
                                <td class="py-3 text-secondary">
                                    <span class="text-truncate d-inline-block" style="max-width:160px;" title="{{ $reservasi->keperluan }}">
                                        {{ $reservasi->keperluan }}
                                    </span>
                                </td>
                                <td class="py-3">
                                    <x-status-badge :status="$reservasi->status" />
                                </td>
                                <td class="py-3 text-end pe-4">
                                    <a href="{{ route('logistik.approval.show', $reservasi) }}" class="btn btn-sm btn-dark d-inline-flex align-items-center gap-2">
                                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                        Tinjau
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="teluroom-empty-state py-0">
                                        <svg width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" class="mb-3"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                                        <h3 class="fs-6 text-dark fw-medium">Antrian Kosong</h3>
                                        <p class="text-secondary mb-0" style="font-size: 0.875rem;">Tidak ada pengajuan yang membutuhkan persetujuan.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($reservasis->hasPages())
                <div class="px-4 py-3 border-top d-flex justify-content-end">
                    {{ $reservasis->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>