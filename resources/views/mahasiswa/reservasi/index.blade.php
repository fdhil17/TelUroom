<x-app-layout>
    <x-slot name="breadcrumb">
        <div class="breadcrumb-item">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline></svg>
            <a href="#">Aktivitas</a>
        </div>
        <div class="breadcrumb-item active">
            <span class="separator">/</span>
            Pengajuan Saya
        </div>
    </x-slot>

    <x-slot name="header">
        Riwayat Peminjaman
    </x-slot>

    <x-slot name="toolbar">
        <form method="GET" action="{{ route('mahasiswa.reservasi.index') }}" class="d-flex flex-column flex-md-row w-100 gap-3 align-items-md-center">
            <div class="toolbar-actions ms-md-auto d-flex flex-wrap gap-2 w-100 w-md-auto justify-content-start justify-content-md-end">
                <select name="status" class="form-select" onchange="this.form.submit()" style="min-width: 170px;">
                    <option value="">Semua Status</option>
                    @foreach (['menunggu_ssc', 'menunggu_logistik', 'disetujui', 'ditolak_ssc', 'ditolak_logistik', 'dibatalkan', 'selesai'] as $s)
                        <option value="{{ $s }}" @selected(request('status') === $s)>
                            {{ ucwords(str_replace('_', ' ', $s)) }}
                        </option>
                    @endforeach
                </select>
                <a href="{{ route('mahasiswa.ruangan.index') }}" class="btn btn-dark d-flex align-items-center gap-2">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    Buat Pengajuan
                </a>
            </div>
        </form>
    </x-slot>

    <div class="teluroom-card mb-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 border-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4 fw-medium text-secondary" style="font-size: 0.8125rem; border-bottom: 1px solid #E5E7EB;">RUANGAN</th>
                            <th class="fw-medium text-secondary" style="font-size: 0.8125rem; border-bottom: 1px solid #E5E7EB;">TANGGAL</th>
                            <th class="fw-medium text-secondary" style="font-size: 0.8125rem; border-bottom: 1px solid #E5E7EB;">JAM</th>
                            <th class="fw-medium text-secondary" style="font-size: 0.8125rem; border-bottom: 1px solid #E5E7EB;">KEPERLUAN</th>
                            <th class="fw-medium text-secondary" style="font-size: 0.8125rem; border-bottom: 1px solid #E5E7EB;">STATUS</th>
                            <th class="fw-medium text-secondary text-end pe-4" style="font-size: 0.8125rem; border-bottom: 1px solid #E5E7EB;">AKSI</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @forelse ($reservasis as $reservasi)
                            <tr>
                                <td class="ps-4 py-3">
                                    <span class="fw-semibold text-dark">{{ $reservasi->ruangan->kode_ruangan }}</span>
                                    <span class="text-secondary d-block" style="font-size:0.8125rem;">{{ $reservasi->ruangan->nama_ruangan }}</span>
                                </td>
                                <td class="py-3 text-dark">{{ $reservasi->tanggal_reservasi->format('d M Y') }}</td>
                                <td class="py-3 text-dark">
                                    <span class="fw-semibold">{{ substr($reservasi->jam_mulai, 0, 5) }}</span>
                                    <span class="text-secondary"> - </span>
                                    <span class="fw-semibold">{{ substr($reservasi->jam_selesai, 0, 5) }}</span>
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
                                    <a href="{{ route('mahasiswa.reservasi.show', $reservasi) }}" class="btn btn-sm btn-light text-secondary d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px; padding: 0;" title="Detail Pengajuan">
                                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="teluroom-empty-state py-0">
                                        <svg width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" class="mb-3"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>
                                        <h3 class="fs-6 text-dark fw-medium">Belum ada riwayat pengajuan</h3>
                                        <p class="text-secondary mb-4" style="font-size: 0.875rem;">Anda belum memiliki riwayat pengajuan peminjaman ruangan.</p>
                                        <a href="{{ route('mahasiswa.ruangan.index') }}" class="btn btn-dark btn-sm d-inline-flex align-items-center gap-2">
                                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                                            Buat Pengajuan Baru
                                        </a>
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