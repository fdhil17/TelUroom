<x-app-layout>
    <x-slot name="header">
        <h2 class="fs-4 fw-bold text-dark">Jadwal Akademik</h2>
    </x-slot>

    <div class="container py-4">

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        {{-- Filter --}}
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('logistik.jadwal.index') }}" class="row g-2 align-items-end">
                    <div class="col-md-4">
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
                    <div class="col-md-3">
                        <label class="form-label">Hari</label>
                        <select name="hari" class="form-select">
                            <option value="">Semua Hari</option>
                            @foreach (['senin','selasa','rabu','kamis','jumat','sabtu','minggu'] as $h)
                                <option value="{{ $h }}" @selected($hari === $h)>{{ ucfirst($h) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">Filter</button>
                    </div>
                    <div class="col-md-3 text-end">
                        <a href="{{ route('logistik.jadwal.create') }}" class="btn btn-primary">+ Tambah Jadwal</a>
                    </div>
                </form>
            </div>
        </div>

        {{-- Tabel --}}
        <div class="card">
            <div class="card-header fw-bold card-header-dark">Daftar Jadwal Akademik</div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Ruangan</th>
                                <th>Hari</th>
                                <th>Jam</th>
                                <th>Mata Kuliah</th>
                                <th>Dosen</th>
                                <th>Dibuat Oleh</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($jadwals as $jadwal)
                                <tr>
                                    <td>
                                        <span class="fw-semibold">{{ $jadwal->ruangan->kode_ruangan }}</span>
                                        <span class="text-muted d-block" style="font-size:0.82rem;">{{ $jadwal->ruangan->nama_ruangan }}</span>
                                    </td>
                                    <td>{{ ucfirst($jadwal->hari) }}</td>
                                    <td>
                                        <span class="fw-semibold">{{ substr($jadwal->jam_mulai, 0, 5) }}</span>
                                        <span class="text-muted"> – </span>
                                        <span class="fw-semibold">{{ substr($jadwal->jam_selesai, 0, 5) }}</span>
                                    </td>
                                    <td>{{ $jadwal->mata_kuliah }}</td>
                                    <td>{{ $jadwal->dosen }}</td>
                                    <td>{{ $jadwal->creator->name }}</td>
                                    <td class="text-end">
                                        <a href="{{ route('logistik.jadwal.edit', $jadwal) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                        <form action="{{ route('logistik.jadwal.destroy', $jadwal) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus jadwal ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">Tidak ada data jadwal akademik.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if ($jadwals->hasPages())
                    <div class="px-3 py-3 border-top">
                        {{ $jadwals->links() }}
                    </div>
                @endif
            </div>
        </div>

    </div>
</x-app-layout>
