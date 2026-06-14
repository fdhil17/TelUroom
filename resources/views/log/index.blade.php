<x-app-layout>
    <x-slot name="header">
        <h2 class="fs-4 fw-bold text-dark">Riwayat Penggunaan Ruang</h2>
    </x-slot>

    <div class="container py-4">

        {{-- Filter --}}
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('log.index') }}" class="row g-2 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label">Ruangan</label>
                        <select name="ruangan_id" class="form-select">
                            <option value="">Semua Ruangan</option>
                            @foreach ($ruangans as $r)
                                <option value="{{ $r->id }}" @selected((string) $ruanganId === (string) $r->id)>
                                    {{ $r->kode_ruangan }} — Lantai {{ $r->lantai }} · Kapasitas {{ $r->kapasitas }} orang
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="disetujui" @selected($status === 'disetujui')>Disetujui</option>
                            <option value="ditolak_ssc" @selected($status === 'ditolak_ssc')>Ditolak SSC</option>
                            <option value="ditolak_logistik" @selected($status === 'ditolak_logistik')>Ditolak Logistik</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Tanggal Dari</label>
                        <input type="date" name="tanggal_dari" class="form-control" value="{{ $tanggalDari }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Tanggal Sampai</label>
                        <input type="date" name="tanggal_sampai" class="form-control" value="{{ $tanggalSampai }}">
                    </div>
                    <div class="col-md-1">
                        <button type="submit" class="btn btn-primary w-100">Filter</button>
                    </div>
                    <div class="col-md-2">
                        <a href="{{ route('log.index') }}" class="btn btn-secondary w-100">Reset</a>
                    </div>
                </form>
            </div>
        </div>

        {{-- Tabel --}}
        <div class="card">
            <div class="card-header fw-bold card-header-dark">Data Riwayat Penggunaan</div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Tanggal</th>
                                <th>Ruangan</th>
                                <th>Mahasiswa</th>
                                <th>NIM</th>
                                <th>Prodi</th>
                                <th>Jam</th>
                                <th>Keperluan</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $statusBadge = [
                                    'disetujui'        => 'bg-success',
                                    'ditolak_ssc'      => 'bg-danger',
                                    'ditolak_logistik' => 'bg-danger',
                                ];
                                $statusLabel = [
                                    'disetujui'        => 'Disetujui',
                                    'ditolak_ssc'      => 'Ditolak oleh SSC',
                                    'ditolak_logistik' => 'Ditolak oleh Logistik',
                                ];
                            @endphp
                            @forelse ($logs as $log)
                                <tr>
                                    <td>{{ $log->tanggal->format('d M Y') }}</td>
                                    <td>
                                        <span class="fw-semibold">{{ $log->ruangan->kode_ruangan }}</span>
                                        <span class="text-muted d-block" style="font-size:0.82rem;">{{ $log->ruangan->nama_ruangan }}</span>
                                    </td>
                                    <td>{{ $log->user->name }}</td>
                                    <td>{{ $log->nim ?? '-' }}</td>
                                    <td>{{ $log->prodi ?? '-' }}</td>
                                    <td>
                                        <span class="fw-semibold">{{ substr($log->jam_mulai, 0, 5) }}</span>
                                        <span class="text-muted"> – </span>
                                        <span class="fw-semibold">{{ substr($log->jam_selesai, 0, 5) }}</span>
                                    </td>
                                    <td>
                                        <span class="text-truncate d-inline-block" style="max-width:140px;" title="{{ $log->keperluan }}">
                                            {{ $log->keperluan }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge {{ $statusBadge[$log->status] ?? 'bg-secondary' }}">
                                            {{ $statusLabel[$log->status] ?? $log->status }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-4">Belum ada riwayat penggunaan ruang.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if ($logs->hasPages())
                    <div class="px-3 py-3 border-top">
                        {{ $logs->links() }}
                    </div>
                @endif
            </div>
        </div>

    </div>
</x-app-layout>
