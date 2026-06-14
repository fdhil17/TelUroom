<x-app-layout>
    <x-slot name="header">
        <h2 class="fs-4 fw-bold text-dark">Data Ruangan</h2>
    </x-slot>

    <div class="container py-4">

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        {{-- Filter --}}
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('logistik.ruangan.index') }}" class="row g-2 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label">Cari Ruangan</label>
                        <input type="text" name="search" class="form-control"
                            placeholder="Kode atau nama ruangan..." value="{{ $search }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Lantai</label>
                        <select name="lantai" class="form-select">
                            <option value="">Semua Lantai</option>
                            @for ($i = 1; $i <= 2; $i++)
                                <option value="{{ $i }}" @selected((string) $lantai === (string) $i)>Lantai {{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">Filter</button>
                    </div>
                    <div class="col-md-3 text-end">
                        <a href="{{ route('logistik.ruangan.create') }}" class="btn btn-primary">+ Tambah Ruangan</a>
                    </div>
                </form>
            </div>
        </div>

        {{-- Tabel --}}
        <div class="card">
            <div class="card-header fw-bold card-header-dark">Daftar Ruangan</div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Kode</th>
                                <th>Nama Ruangan</th>
                                <th>Lantai</th>
                                <th>Kapasitas</th>
                                <th>Status</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $statusBadge = [
                                    'tersedia'          => 'bg-success',
                                    'digunakan_kuliah'  => 'bg-primary',
                                    'sudah_direservasi' => 'bg-warning text-dark',
                                    'maintenance'       => 'bg-danger',
                                ];
                                $statusLabel = [
                                    'tersedia'          => 'Tersedia',
                                    'digunakan_kuliah'  => 'Digunakan Kuliah',
                                    'sudah_direservasi' => 'Sedang Dipinjam',
                                    'maintenance'       => 'Dalam Perawatan',
                                ];
                            @endphp
                            @forelse ($ruangans as $ruangan)
                                <tr>
                                    <td class="fw-semibold">{{ $ruangan->kode_ruangan }}</td>
                                    <td>{{ $ruangan->nama_ruangan }}</td>
                                    <td>{{ $ruangan->lantai }}</td>
                                    <td>{{ $ruangan->kapasitas }} orang</td>
                                    <td>
                                        <span class="badge {{ $statusBadge[$ruangan->status] }}">
                                            {{ $statusLabel[$ruangan->status] }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('logistik.ruangan.edit', $ruangan) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                        <form action="{{ route('logistik.ruangan.destroy', $ruangan) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus ruangan ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">Tidak ada data ruangan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if ($ruangans->hasPages())
                    <div class="px-3 py-3 border-top">
                        {{ $ruangans->links() }}
                    </div>
                @endif
            </div>
        </div>

    </div>
</x-app-layout>
