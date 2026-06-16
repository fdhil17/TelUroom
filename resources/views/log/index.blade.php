<x-app-layout>
    <x-slot name="breadcrumb">
        <div class="breadcrumb-item">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21.5 2v6h-6M2.13 15.57a10 10 0 1 0 1.49-9.14L2.5 8"></path></svg>
            <a href="#">Sistem</a>
        </div>
        <div class="breadcrumb-item active">
            <span class="separator">/</span>
            Log Aktivitas
        </div>
    </x-slot>

    <x-slot name="header">
        Riwayat Penggunaan Ruang
    </x-slot>

    <x-slot name="toolbar">
        <form method="GET" action="{{ route('log.index') }}" class="d-flex flex-column flex-md-row w-100 gap-3 align-items-md-end">
            <div class="toolbar-actions d-flex flex-wrap gap-2 w-100">
                <div style="flex: 1; min-width: 200px;">
                    <select name="ruangan_id" class="form-select">
                        <option value="">Semua Ruangan</option>
                        @foreach ($ruangans as $r)
                            <option value="{{ $r->id }}" @selected((string) request('ruangan_id') === (string) $r->id)>
                                {{ $r->kode_ruangan }} - Lt. {{ $r->lantai }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div style="min-width: 150px;">
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="disetujui" @selected(request('status') === 'disetujui')>Disetujui</option>
                        <option value="ditolak_ssc" @selected(request('status') === 'ditolak_ssc')>Ditolak SSC</option>
                        <option value="ditolak_logistik" @selected(request('status') === 'ditolak_logistik')>Ditolak Logistik</option>
                        <option value="selesai" @selected(request('status') === 'selesai')>Selesai</option>
                    </select>
                </div>
                <div style="min-width: 150px;">
                    <input type="date" name="tanggal_dari" class="form-control" value="{{ request('tanggal_dari') }}" title="Tanggal Dari">
                </div>
                <div style="min-width: 150px;">
                    <input type="date" name="tanggal_sampai" class="form-control" value="{{ request('tanggal_sampai') }}" title="Tanggal Sampai">
                </div>
                <div class="d-flex gap-2 ms-md-auto">
                    <button type="submit" class="btn btn-dark">Filter</button>
                    <a href="{{ route('log.index') }}" class="btn btn-light border">Reset</a>
                </div>
            </div>
        </form>
    </x-slot>

    <div class="teluroom-card mb-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 border-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4 fw-medium text-secondary" style="font-size: 0.8125rem; border-bottom: 1px solid #E5E7EB;">TANGGAL</th>
                            <th class="fw-medium text-secondary" style="font-size: 0.8125rem; border-bottom: 1px solid #E5E7EB;">RUANGAN</th>
                            <th class="fw-medium text-secondary" style="font-size: 0.8125rem; border-bottom: 1px solid #E5E7EB;">MAHASISWA</th>
                            <th class="fw-medium text-secondary" style="font-size: 0.8125rem; border-bottom: 1px solid #E5E7EB;">JAM</th>
                            <th class="fw-medium text-secondary" style="font-size: 0.8125rem; border-bottom: 1px solid #E5E7EB;">KEPERLUAN</th>
                            <th class="fw-medium text-secondary pe-4" style="font-size: 0.8125rem; border-bottom: 1px solid #E5E7EB;">STATUS</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @forelse ($logs as $log)
                            <tr>
                                <td class="ps-4 py-3 text-dark">{{ $log->tanggal->format('d M Y') }}</td>
                                <td class="py-3">
                                    <span class="fw-semibold text-dark">{{ $log->ruangan->kode_ruangan }}</span>
                                    <span class="text-secondary d-block" style="font-size:0.8125rem;">{{ $log->ruangan->nama_ruangan }}</span>
                                </td>
                                <td class="py-3">
                                    <span class="fw-medium text-dark">{{ $log->user->name }}</span>
                                    <span class="text-secondary d-block" style="font-size:0.8125rem;">{{ $log->nim ?? '-' }} &middot; {{ $log->prodi ?? '-' }}</span>
                                </td>
                                <td class="py-3 text-dark">
                                    <span class="fw-semibold">{{ substr($log->jam_mulai, 0, 5) }}</span>
                                    <span class="text-secondary"> - </span>
                                    <span class="fw-semibold">{{ substr($log->jam_selesai, 0, 5) }}</span>
                                </td>
                                <td class="py-3 text-secondary">
                                    <span class="text-truncate d-inline-block" style="max-width:200px;" title="{{ $log->keperluan }}">
                                        {{ $log->keperluan }}
                                    </span>
                                </td>
                                <td class="py-3 pe-4">
                                    <x-status-badge :status="$log->status" />
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="teluroom-empty-state py-0">
                                        <svg width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" class="mb-3"><path d="M21.5 2v6h-6M2.13 15.57a10 10 0 1 0 1.49-9.14L2.5 8"></path></svg>
                                        <h3 class="fs-6 text-dark fw-medium">Belum ada log</h3>
                                        <p class="text-secondary mb-0" style="font-size: 0.875rem;">Tidak ada riwayat penggunaan ruangan yang sesuai filter.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($logs->hasPages())
                <div class="px-4 py-3 border-top d-flex justify-content-end">
                    {{ $logs->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>