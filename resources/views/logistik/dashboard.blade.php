<x-app-layout>
    <x-slot name="header">
        <h2 class="fs-4 fw-bold text-dark">{{ __('dashboard.logistik_judul') }}</h2>
    </x-slot>

    <div class="container py-4">

        <div class="alert alert-primary">
            {{ __('dashboard.selamat_datang', ['nama' => Auth::user()->name]) }}
        </div>

        <h5 class="fw-bold mb-3">{{ __('dashboard.statistik_ruangan') }}</h5>
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="card stat-card text-center stat-primary">
                    <div class="card-body">
                        <h3 class="fw-bold">{{ $statistikRuangan['total'] }}</h3>
                        <p class="mb-0">{{ __('dashboard.total_ruangan') }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card stat-card text-center stat-success">
                    <div class="card-body">
                        <h3 class="fw-bold">{{ $statistikRuangan['tersedia'] }}</h3>
                        <p class="mb-0">Tersedia</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card stat-card text-center stat-info">
                    <div class="card-body">
                        <h3 class="fw-bold">{{ $statistikRuangan['digunakan_kuliah'] }}</h3>
                        <p class="mb-0">Digunakan Kuliah</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card stat-card text-center stat-danger">
                    <div class="card-body">
                        <h3 class="fw-bold">{{ $statistikRuangan['maintenance'] }}</h3>
                        <p class="mb-0">Dalam Perawatan</p>
                    </div>
                </div>
            </div>
        </div>

        <h5 class="fw-bold mb-3">{{ __('dashboard.statistik_pengajuan') }}</h5>
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="card stat-card text-center stat-warning">
                    <div class="card-body">
                        <h3 class="fw-bold">{{ $statistikReservasi['menunggu_logistik'] }}</h3>
                        <p class="mb-0">{{ __('dashboard.menunggu_persetujuan') }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card stat-card text-center stat-success">
                    <div class="card-body">
                        <h3 class="fw-bold">{{ $statistikReservasi['disetujui'] }}</h3>
                        <p class="mb-0">{{ __('dashboard.disetujui') }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card stat-card text-center stat-danger">
                    <div class="card-body">
                        <h3 class="fw-bold">{{ $statistikReservasi['ditolak_logistik'] }}</h3>
                        <p class="mb-0">{{ __('dashboard.ditolak') }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card stat-card text-center stat-primary">
                    <div class="card-body">
                        <h3 class="fw-bold">{{ $statistikReservasi['total'] }}</h3>
                        <p class="mb-0">{{ __('dashboard.total_pengajuan') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header fw-bold card-header-dark">{{ __('dashboard.pengajuan_terbaru') }}</div>
            <div class="card-body">
                @if ($reservasiTerbaru->isEmpty())
                    <p class="text-muted text-center mb-0">{{ __('dashboard.belum_ada_pengajuan') }}</p>
                @else
                    <table class="table table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Mahasiswa</th>
                                <th>{{ __('pengajuan.label_ruangan') }}</th>
                                <th>{{ __('pengajuan.label_tanggal') }}</th>
                                <th>Status</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $statusBadge = [
                                    'menunggu_logistik' => 'bg-info text-dark',
                                    'disetujui'         => 'bg-success',
                                    'ditolak_logistik'  => 'bg-danger',
                                ];
                                $statusLabel = [
                                    'menunggu_logistik' => 'Menunggu Persetujuan Logistik',
                                    'disetujui'         => 'Disetujui',
                                    'ditolak_logistik'  => 'Ditolak oleh Logistik',
                                ];
                            @endphp
                            @foreach ($reservasiTerbaru as $r)
                                <tr>
                                    <td>{{ $r->user->name }}</td>
                                    <td>
                                        <span class="fw-semibold">{{ $r->ruangan->kode_ruangan }}</span>
                                        <span class="text-muted d-block" style="font-size:0.82rem;">{{ $r->ruangan->nama_ruangan }}</span>
                                    </td>
                                    <td>{{ $r->tanggal_reservasi->format('d M Y') }}</td>
                                    <td>
                                        <span class="badge {{ $statusBadge[$r->status] ?? 'bg-secondary' }}">
                                            {{ $statusLabel[$r->status] ?? $r->status }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('logistik.approval.show', $r) }}" class="btn btn-sm btn-outline-primary">{{ __('pengajuan.btn_detail') }}</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif

                <a href="{{ route('logistik.approval.index') }}" class="btn btn-outline-primary mt-2">{{ __('dashboard.lihat_semua_persetujuan') }}</a>
            </div>
        </div>

    </div>
</x-app-layout>
