<x-app-layout>
    <x-slot name="header">
        <h2 class="fs-4 fw-bold text-dark">Riwayat Pengajuan Peminjaman Saya</h2>
    </x-slot>

    <div class="container py-4">

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="d-flex justify-content-between align-items-center mb-4">
            <p class="text-muted mb-0">{{ __('pengajuan.subteks_riwayat') }}</p>
            <a href="{{ route('mahasiswa.reservasi.create') }}" class="btn btn-primary">{{ __('pengajuan.btn_ajukan_baru') }}</a>
            <div class="card-header fw-bold card-header-dark">{{ __('pengajuan.card_riwayat') }}</div>
            
        </div>

        <div class="card">
            <div class="card-header fw-bold card-header-dark">Riwayat Pengajuan Peminjaman</div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>No.</th>
                                <th>Ruangan</th>
                                <th>Tanggal</th>
                                <th>Jam</th>
                                <th>Keperluan</th>
                                <th>Status</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
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
                            @forelse ($reservasis as $reservasi)
                                <tr>
                                    <td class="text-muted" style="font-size: 0.85rem;">#{{ $reservasi->id }}</td>
                                    <td>
                                        <span class="fw-semibold">{{ $reservasi->ruangan->kode_ruangan }}</span>
                                        <span class="text-muted d-block" style="font-size: 0.82rem;">{{ $reservasi->ruangan->nama_ruangan }}</span>
                                    </td>
                                    <td>{{ $reservasi->tanggal_reservasi->format('d M Y') }}</td>
                                    <td>
                                        <span class="fw-semibold">{{ substr($reservasi->jam_mulai, 0, 5) }}</span>
                                        <span class="text-muted"> – </span>
                                        <span class="fw-semibold">{{ substr($reservasi->jam_selesai, 0, 5) }}</span>
                                    </td>
                                    <td>
                                        <span class="text-truncate d-inline-block" style="max-width: 160px;" title="{{ $reservasi->keperluan }}">
                                            {{ $reservasi->keperluan }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge {{ $statusBadge[$reservasi->status] }}">
                                            {{ $statusLabel[$reservasi->status] }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('mahasiswa.reservasi.show', $reservasi) }}"
                                            class="btn btn-sm btn-outline-primary">Detail</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">Belum ada riwayat pengajuan peminjaman.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($reservasis->hasPages())
                    <div class="px-3 py-3 border-top">
                        {{ $reservasis->links() }}
                    </div>
                @endif

            </div>
        </div>

    </div>
</x-app-layout>
