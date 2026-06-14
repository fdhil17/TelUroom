<x-app-layout>
    <x-slot name="header">
        <h2 class="fs-4 fw-bold text-dark">Detail Ruangan: {{ $ruangan->kode_ruangan }}</h2>
    </x-slot>

    <div class="container py-4">
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title">{{ $ruangan->nama_ruangan }}</h5>
                <p class="mb-1">Lantai: {{ $ruangan->lantai }}</p>
                <p class="mb-1">Kapasitas: {{ $ruangan->kapasitas }} orang</p>
                <p class="mb-3">
                    Status:
                    @php
                        $statusBadge = [
                            'tersedia' => 'bg-success',
                            'digunakan_kuliah' => 'bg-primary',
                            'sudah_direservasi' => 'bg-warning text-dark',
                            'maintenance' => 'bg-danger',
                        ];
                        $statusLabel = [
                            'tersedia' => 'Tersedia',
                            'digunakan_kuliah' => 'Digunakan untuk Kuliah',
                            'sudah_direservasi' => 'Sudah Direservasi',
                            'maintenance' => 'Maintenance',
                        ];
                    @endphp
                    <span class="badge {{ $statusBadge[$ruangan->status] }}">
                        {{ $statusLabel[$ruangan->status] }}
                    </span>
                </p>

                @if ($ruangan->status !== 'maintenance')
                    <a href="{{ route('mahasiswa.reservasi.create', ['ruangan_id' => $ruangan->id]) }}" class="btn btn-success">
                        Ajukan Reservasi untuk Ruangan Ini
                    </a>
                @endif
            </div>
        </div>

        <div class="card">
            <div class="card-header fw-bold">Jadwal Akademik (Kuliah)</div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Hari</th>
                            <th>Jam</th>
                            <th>Mata Kuliah</th>
                            <th>Dosen</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($jadwalAkademiks as $jadwal)
                            <tr>
                                <td>{{ ucfirst($jadwal->hari) }}</td>
                                <td>{{ substr($jadwal->jam_mulai, 0, 5) }} - {{ substr($jadwal->jam_selesai, 0, 5) }}</td>
                                <td>{{ $jadwal->mata_kuliah }}</td>
                                <td>{{ $jadwal->dosen }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">Tidak ada jadwal akademik untuk ruangan ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-3">
            <a href="{{ route('mahasiswa.ruangan.index') }}" class="btn btn-outline-secondary">Kembali</a>
        </div>
    </div>
</x-app-layout>
