<x-app-layout>
    <x-slot name="header">
        <h2 class="fs-4 fw-bold text-dark">Dashboard SSC</h2>
    </x-slot>

    <div class="container py-4">

        <div class="alert alert-primary">
            Selamat datang, <strong>{{ Auth::user()->name }}</strong>!
        </div>

        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="card text-center border-warning">
                    <div class="card-body">
                        <h3 class="fw-bold text-warning">{{ $statistik['menunggu_ssc'] }}</h3>
                        <p class="text-muted mb-0">Menunggu Persetujuan SSC</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card text-center border-info">
                    <div class="card-body">
                        <h3 class="fw-bold text-info">{{ $statistik['menunggu_logistik'] }}</h3>
                        <p class="text-muted mb-0">Menunggu Logistik</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card text-center border-success">
                    <div class="card-body">
                        <h3 class="fw-bold text-success">{{ $statistik['disetujui'] }}</h3>
                        <p class="text-muted mb-0">Disetujui</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card text-center border-danger">
                    <div class="card-body">
                        <h3 class="fw-bold text-danger">{{ $statistik['ditolak_ssc'] }}</h3>
                        <p class="text-muted mb-0">Ditolak SSC</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header fw-bold">Reservasi Terbaru (Semua)</div>
            <div class="card-body">
                @if ($reservasiTerbaru->isEmpty())
                    <p class="text-muted text-center mb-0">Belum ada reservasi.</p>
                @else
                    <table class="table table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Mahasiswa</th>
                                <th>Ruangan</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $statusBadge = [
                                    'menunggu_ssc' => 'bg-warning text-dark',
                                    'menunggu_logistik' => 'bg-info text-dark',
                                    'disetujui' => 'bg-success',
                                    'ditolak_ssc' => 'bg-danger',
                                    'ditolak_logistik' => 'bg-danger',
                                ];
                                $statusLabel = [
                                    'menunggu_ssc' => 'Menunggu Persetujuan SSC',
                                    'menunggu_logistik' => 'Menunggu Persetujuan Logistik',
                                    'disetujui' => 'Disetujui',
                                    'ditolak_ssc' => 'Ditolak SSC',
                                    'ditolak_logistik' => 'Ditolak Logistik',
                                ];
                            @endphp
                            @foreach ($reservasiTerbaru as $r)
