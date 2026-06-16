<x-app-layout>
    <x-slot name="breadcrumb">
        <div class="breadcrumb-item">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
            <a href="{{ route('mahasiswa.ruangan.index') }}">Katalog Ruangan</a>
        </div>
        <div class="breadcrumb-item active">
            <span class="separator">/</span>
            {{ $ruangan->kode_ruangan }}
        </div>
    </x-slot>

    <x-slot name="header">
        {{ $ruangan->nama_ruangan }}
    </x-slot>

    {{-- Action Toolbar --}}
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <div class="d-flex align-items-center gap-2">
            <x-status-badge :status="$ruangan->status" />
            <span class="text-secondary" style="font-size: 0.875rem;">Lantai {{ $ruangan->lantai }} &middot; Kapasitas {{ $ruangan->kapasitas }} orang</span>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('mahasiswa.ruangan.index') }}" class="btn btn-light border d-inline-flex align-items-center gap-2" style="height: 40px;">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                Kembali
            </a>
            @if ($ruangan->status !== 'maintenance')
                <a href="{{ route('mahasiswa.reservasi.create', ['ruangan_id' => $ruangan->id]) }}"
                   class="btn btn-dark d-inline-flex align-items-center gap-2" style="height: 40px;">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></svg>
                    Pinjam Ruangan Ini
                </a>
            @endif
        </div>
    </div>

    {{-- Detail Info --}}
    <div class="row g-4 mb-4">
        <div class="col-lg-6">
            <div class="teluroom-card h-100">
                <div class="card-body p-4 p-md-5">
                    <h6 class="fw-bold text-dark mb-4 pb-3 border-bottom" style="font-size: 0.8125rem; letter-spacing: 0.06em; text-transform: uppercase;">Informasi Ruangan</h6>

                    <div class="d-flex flex-column gap-4">
                        <div>
                            <span class="text-secondary d-block mb-1" style="font-size: 0.8125rem; font-weight: 600;">KODE RUANGAN</span>
                            <span class="fw-bold text-dark" style="font-size: 1.125rem;">{{ $ruangan->kode_ruangan }}</span>
                        </div>
                        <div>
                            <span class="text-secondary d-block mb-1" style="font-size: 0.8125rem; font-weight: 600;">NAMA RUANGAN</span>
                            <span class="text-dark">{{ $ruangan->nama_ruangan }}</span>
                        </div>
                        <div class="row g-3">
                            <div class="col-6">
                                <span class="text-secondary d-block mb-1" style="font-size: 0.8125rem; font-weight: 600;">LANTAI</span>
                                <span class="fw-medium text-dark">Lantai {{ $ruangan->lantai }}</span>
                            </div>
                            <div class="col-6">
                                <span class="text-secondary d-block mb-1" style="font-size: 0.8125rem; font-weight: 600;">KAPASITAS</span>
                                <span class="fw-medium text-dark">{{ $ruangan->kapasitas }} Orang</span>
                            </div>
                        </div>
                        <div>
                            <span class="text-secondary d-block mb-2" style="font-size: 0.8125rem; font-weight: 600;">STATUS</span>
                            <x-status-badge :status="$ruangan->status" />
                        </div>
                        @if($ruangan->fasilitas)
                        <div>
                            <span class="text-secondary d-block mb-1" style="font-size: 0.8125rem; font-weight: 600;">FASILITAS</span>
                            <span class="text-dark" style="font-size: 0.9375rem;">{{ $ruangan->fasilitas }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Jadwal Akademik --}}
        <div class="col-lg-6">
            <div class="teluroom-card h-100">
                <div class="card-body p-0">
                    <div class="p-4 pb-3 border-bottom">
                        <h6 class="fw-bold text-dark mb-0" style="font-size: 0.8125rem; letter-spacing: 0.06em; text-transform: uppercase;">Jadwal Akademik (Ruangan Terkunci)</h6>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 border-0">
                            <thead>
                                <tr>
                                    <th class="ps-4 py-3 fw-medium text-secondary" style="font-size: 0.8125rem; border-bottom: 1px solid #E5E7EB;">HARI</th>
                                    <th class="py-3 fw-medium text-secondary" style="font-size: 0.8125rem; border-bottom: 1px solid #E5E7EB;">JAM</th>
                                    <th class="py-3 fw-medium text-secondary" style="font-size: 0.8125rem; border-bottom: 1px solid #E5E7EB;">MATA KULIAH</th>
                                    <th class="py-3 pe-4 fw-medium text-secondary" style="font-size: 0.8125rem; border-bottom: 1px solid #E5E7EB;">DOSEN</th>
                                </tr>
                            </thead>
                            <tbody class="border-top-0">
                                @forelse ($jadwalAkademiks as $jadwal)
                                    <tr>
                                        <td class="ps-4 py-3 fw-medium text-dark">{{ ucfirst($jadwal->hari) }}</td>
                                        <td class="py-3 text-dark" style="white-space: nowrap;">
                                            <span class="fw-semibold">{{ substr($jadwal->jam_mulai, 0, 5) }}</span>
                                            <span class="text-secondary"> - </span>
                                            <span class="fw-semibold">{{ substr($jadwal->jam_selesai, 0, 5) }}</span>
                                        </td>
                                        <td class="py-3 text-dark">{{ $jadwal->mata_kuliah }}</td>
                                        <td class="py-3 pe-4 text-secondary">{{ $jadwal->dosen }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4">
                                            <div class="text-secondary" style="font-size: 0.875rem;">
                                                Tidak ada jadwal akademik pada ruangan ini.
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>