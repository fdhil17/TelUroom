<x-app-layout>
    <x-slot name="breadcrumb">
        <div class="breadcrumb-item active">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
            Beranda
        </div>
    </x-slot>

    <x-slot name="header">
        Dashboard SSC
    </x-slot>

    <div class="row g-4 mb-5">
        {{-- Stat Card 1 --}}
        <div class="col-6 col-md-4">
            <div class="teluroom-card h-100">
                <div class="card-body d-flex flex-column">
                    <span class="text-uppercase text-tertiary fw-semibold mb-2" style="font-size: 0.75rem; letter-spacing: 0.05em;">Total Pengajuan</span>
                    <div class="d-flex align-items-end justify-content-between mt-auto">
                        <span class="fs-1 fw-bold lh-1 text-primary" style="font-family: 'Eixample Villa Extra Bold', sans-serif;">{{ $statistik['total'] }}</span>
                        <div class="text-primary opacity-50">
                            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Stat Card 2 --}}
        <div class="col-6 col-md-4">
            <div class="teluroom-card h-100">
                <div class="card-body d-flex flex-column">
                    <span class="text-uppercase text-tertiary fw-semibold mb-2" style="font-size: 0.75rem; letter-spacing: 0.05em;">Perlu Verifikasi</span>
                    <div class="d-flex align-items-end justify-content-between mt-auto">
                        <span class="fs-1 fw-bold lh-1" style="font-family: 'Eixample Villa Extra Bold', sans-serif; color: #D97706;">{{ $statistik['menunggu_ssc'] }}</span>
                        <div class="opacity-50" style="color: #D97706;">
                            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Stat Card 3 --}}
        <div class="col-6 col-md-4">
            <div class="teluroom-card h-100">
                <div class="card-body d-flex flex-column">
                    <span class="text-uppercase text-tertiary fw-semibold mb-2" style="font-size: 0.75rem; letter-spacing: 0.05em;">Telah Diverifikasi</span>
                    <div class="d-flex align-items-end justify-content-between mt-auto">
                        <span class="fs-1 fw-bold lh-1" style="font-family: 'Eixample Villa Extra Bold', sans-serif; color: #059669;">{{ ($statistik['menunggu_logistik'] + $statistik['disetujui']) }}</span>
                        <div class="opacity-50" style="color: #059669;">
                            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Pengajuan Perlu Verifikasi --}}
    <div class="teluroom-card mb-4">
        <div class="card-body p-0">
            <div class="d-flex align-items-center justify-content-between p-4 border-bottom">
                <h3 class="fs-5 fw-bold mb-0">Antrian Verifikasi</h3>
                <a href="{{ route('ssc.approval.index') }}" class="btn btn-sm btn-outline-secondary">Lihat Semua</a>
            </div>
            
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 border-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4 fw-medium text-secondary" style="font-size: 0.8125rem;">MAHASISWA</th>
                            <th class="fw-medium text-secondary" style="font-size: 0.8125rem;">RUANGAN</th>
                            <th class="fw-medium text-secondary" style="font-size: 0.8125rem;">TANGGAL</th>
                            <th class="fw-medium text-secondary text-end pe-4" style="font-size: 0.8125rem;">AKSI</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @forelse ($reservasiTerbaru ?? [] as $reservasi)
                            <tr>
                                <td class="ps-4 py-3">
                                    <div class="fw-semibold text-dark">{{ $reservasi->user->name }}</div>
                                    <div class="text-secondary" style="font-size: 0.8125rem;">{{ $reservasi->user->nim }} &middot; {{ $reservasi->user->prodi }}</div>
                                </td>
                                <td class="py-3">
                                    <div class="fw-semibold text-dark">{{ $reservasi->ruangan->kode_ruangan }}</div>
                                    <div class="text-secondary" style="font-size: 0.8125rem;">Lt. {{ $reservasi->ruangan->lantai }}</div>
                                </td>
                                <td class="py-3">
                                    <div class="text-dark">{{ $reservasi->tanggal_reservasi->format('d M Y') }}</div>
                                    <div class="text-secondary" style="font-size: 0.8125rem;">
                                        {{ substr($reservasi->jam_mulai, 0, 5) }} - {{ substr($reservasi->jam_selesai, 0, 5) }}
                                    </div>
                                </td>
                                <td class="py-3 text-end pe-4">
                                    <a href="{{ route('ssc.approval.show', $reservasi->id) }}" class="btn btn-sm btn-dark">Verifikasi &rarr;</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5">
                                    <div class="teluroom-empty-state py-0">
                                        <svg width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" class="mb-3"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                                        <h3 class="fs-6 text-dark fw-medium">Antrian Kosong</h3>
                                        <p class="text-secondary mb-0" style="font-size: 0.875rem;">Tidak ada pengajuan yang menunggu verifikasi saat ini.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>