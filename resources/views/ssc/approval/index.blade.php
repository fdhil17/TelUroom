<x-app-layout>
    <x-slot name="header">
        <h2 class="fs-4 fw-bold text-dark">Verifikasi Pengajuan Peminjaman</h2>
    </x-slot>

    <div class="container py-4">

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        {{-- Filter --}}
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('ssc.approval.index') }}" class="row g-2 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label">Filter Status</label>
                        @php
                            $statusOptions = [
                                ''                  => 'Semua Status',
                                'menunggu_ssc'      => 'Menunggu Persetujuan SSC',
                                'menunggu_logistik' => 'Menunggu Persetujuan Logistik',
                                'disetujui'         => 'Disetujui',
                                'ditolak_ssc'       => 'Ditolak SSC',
                                'ditolak_logistik'  => 'Ditolak Logistik',
                            ];
                        @endphp
                        <select name="status" class="form-select">
                            @foreach ($statusOptions as $value => $label)
                                <option value="{{ $value }}" @selected($status === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">Filter</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Tabel --}}
        <div class="card">
            <div class="card-header fw-bold card-header-dark">Daftar Pengajuan Peminjaman</div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Mahasiswa</th>
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
                                    <td>{{ $reservasi->user->name }}</td>
                                    <td>
                                        <span class="fw-semibold">{{ $reservasi->ruangan->kode_ruangan }}</span>
                                        <span class="text-muted d-block" style="font-size:0.82rem;">{{ $reservasi->ruangan->nama_ruangan }}</span>
                                    </td>
                                    <td>{{ $reservasi->tanggal_reservasi->format('d M Y') }}</td>
                                    <td>
                                        <span class="fw-semibold">{{ substr($reservasi->jam_mulai, 0, 5) }}</span>
                                        <span class="text-muted"> – </span>
                                        <span class="fw-semibold">{{ substr($reservasi->jam_selesai, 0, 5) }}</span>
                                    </td>
                                    <td>
                                        <span class="text-truncate d-inline-block" style="max-width:160px;" title="{{ $reservasi->keperluan }}">
                                            {{ $reservasi->keperluan }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge {{ $statusBadge[$reservasi->status] ?? 'bg-secondary' }}">
                                            {{ $statusLabel[$reservasi->status] ?? $reservasi->status }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('ssc.approval.show', $reservasi) }}" class="btn btn-sm btn-outline-primary">Detail</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">Tidak ada data pengajuan peminjaman.</td>
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
