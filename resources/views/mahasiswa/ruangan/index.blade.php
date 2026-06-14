<x-app-layout>
    <x-slot name="header">
        <h2 class="fs-4 fw-bold text-dark">Daftar Ruangan</h2>
    </x-slot>

    <div class="container py-4">

        {{-- Filter --}}
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('mahasiswa.ruangan.index') }}" class="row g-2 align-items-end">
                    <div class="col-md-5">
                        <label class="form-label">Cari Ruangan</label>
                        <input type="text" name="search" class="form-control"
                            placeholder="Kode atau nama ruangan..." value="{{ $search }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Lantai</label>
                        <select name="lantai" class="form-select">
                            <option value="">Semua Lantai</option>
                            @for ($i = 1; $i <= 2; $i++)
                                <option value="{{ $i }}" @selected((string) $lantai === (string) $i)>Lantai {{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary w-100">Filter</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Grid Ruangan --}}
        <div class="row">
            @php
                $statusBadge = [
                    'tersedia'          => 'bg-success',
                    'digunakan_kuliah'  => 'bg-primary',
                    'sudah_direservasi' => 'bg-warning text-dark',
                    'maintenance'       => 'bg-danger',
                ];
                $statusLabel = [
                    'tersedia'          => 'Tersedia',
                    'digunakan_kuliah'  => 'Digunakan untuk Kuliah',
                    'sudah_direservasi' => 'Sedang Dipinjam',
                    'maintenance'       => 'Dalam Perawatan',
                ];
            @endphp

            @forelse ($ruangans as $ruangan)
                <div class="col-md-4 mb-3">
                    <div class="card h-100 ruangan-card">
                        <div class="card-body d-flex flex-column">

                            {{-- Header info ruangan --}}
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <h5 class="card-title fw-bold mb-0">{{ $ruangan->kode_ruangan }}</h5>
                                    <p class="text-muted mb-0" style="font-size: 0.85rem;">{{ $ruangan->nama_ruangan }}</p>
                                </div>
                                <span class="badge {{ $statusBadge[$ruangan->status] }}">
                                    {{ $statusLabel[$ruangan->status] }}
                                </span>
                            </div>

                            {{-- Detail ruangan --}}
                            <div class="ruangan-meta mb-3">
                                <div class="ruangan-meta-item">
                                    <span class="ruangan-meta-label">Lantai</span>
                                    <span class="ruangan-meta-value">{{ $ruangan->lantai }}</span>
                                </div>
                                <div class="ruangan-meta-item">
                                    <span class="ruangan-meta-label">Kapasitas</span>
                                    <span class="ruangan-meta-value">{{ $ruangan->kapasitas }} orang</span>
                                </div>
                            </div>

                            <div class="mt-auto">
                                <a href="{{ route('mahasiswa.ruangan.show', $ruangan) }}"
                                    class="btn btn-sm btn-outline-primary w-100">Lihat Detail</a>
                            </div>

                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="text-center py-5 text-muted">
                        <p class="mb-0">Tidak ada ruangan ditemukan.</p>
                    </div>
                </div>
            @endforelse
        </div>

        {{ $ruangans->links() }}
    </div>
</x-app-layout>
