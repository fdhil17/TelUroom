<x-app-layout>
    <x-slot name="header">
        <h2 class="page-title">DASHBOARD SSC</h2>
    </x-slot>

    <div class="container py-4">

        <div class="teluroom-welcome mb-4">
            <div class="d-flex align-items-center gap-3">
                <div class="teluroom-avatar-lg">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
                <div>
                    <p class="mb-0 text-secondary" style="font-size: 0.8rem; letter-spacing: 0.5px; text-transform: uppercase;">Selamat Datang</p>
                    <h5 class="mb-0 fw-semibold" style="color: #111111;">{{ Auth::user()->name }}</h5>
                </div>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3">
                <div class="teluroom-stat-card">
                    <p class="card-label">MENUNGGU PERSETUJUAN SSC</p>
                    <h2 class="stat-number">{{ $statistik['menunggu_ssc'] }}</h2>
                    <div class="stat-icon stat-icon-warning"><svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="teluroom-stat-card">
                    <p class="card-label">DITERUSKAN KE LOGISTIK</p>
                    <h2 class="stat-number">{{ $statistik['menunggu_logistik'] }}</h2>
                    <div class="stat-icon stat-icon-info"><svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg></div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="teluroom-stat-card">
                    <p class="card-label">DISETUJUI</p>
                    <h2 class="stat-number">{{ $statistik['disetujui'] }}</h2>
                    <div class="stat-icon stat-icon-success"><svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="teluroom-stat-card">
                    <p class="card-label">DITOLAK SSC</p>
                    <h2 class="stat-number">{{ $statistik['ditolak_ssc'] }}</h2>
                    <div class="stat-icon stat-icon-danger"><svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg></div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h6 class="section-title mb-0">Pengajuan Terbaru (Semua)</h6>
            </div>
            <div class="card-body p-0">
                @if ($reservasiTerbaru->isEmpty())
                    <div class="teluroom-empty-state">
                        <svg width="40" height="40" fill="none" stroke="#D1D5DB" stroke-width="1.5" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        <p class="mt-3 mb-1 fw-semibold" style="color: #374151;">Belum Ada Pengajuan</p>
                        <p class="text-secondary mb-0" style="font-size: 0.875rem;">Belum ada pengajuan peminjaman saat ini.</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Mahasiswa</th>
                                    <th>Ruangan</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                    <th class="text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($reservasiTerbaru as $r)

                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

    </div>
</x-app-layout>