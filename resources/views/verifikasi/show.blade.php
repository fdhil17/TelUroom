<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verifikasi Peminjaman - TelURoom</title>
    @vite(['resources/sass/app.scss'])
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-body">
                        <div class="text-center mb-3">
                            <h4 class="fw-bold text-primary">TelUroom</h4>
                            <p class="text-muted">Verifikasi Peminjaman Ruangan</p>
                        </div>

                        @php
                            $statusBadge = [
                                'menunggu_ssc' => 'bg-warning text-dark',
                                'menunggu_logistik' => 'bg-info text-dark',
                                'disetujui' => 'bg-success',
                                'ditolak_ssc' => 'bg-danger',
                                'ditolak_logistik' => 'bg-danger',
                            ];
                            $statusLabel = [
                                'menunggu_ssc'      => 'Menunggu Verifikasi SSC',
                                'menunggu_logistik' => 'Menunggu Persetujuan Logistik',
                                'disetujui'         => 'Disetujui',
                                'ditolak_ssc'       => 'Ditolak oleh SSC',
                                'ditolak_logistik'  => 'Ditolak oleh Logistik',
                            ];
                        @endphp

                        @if ($reservasi->status === 'disetujui')
                            <div class="alert alert-success text-center">
                                <strong>✓ Peminjaman Valid & Disetujui</strong>
                            </div>
                        @else
                            <div class="alert alert-danger text-center">
                                <strong>✗ Peminjaman Tidak Valid</strong>
                            </div>
                        @endif

                        <dl class="row">
                            <dt class="col-sm-4">No. Pengajuan</dt>
                            <dd class="col-sm-8">#{{ $reservasi->id }}</dd>

                            <dt class="col-sm-4">Nama Mahasiswa</dt>
                            <dd class="col-sm-8">{{ $reservasi->user->name }}</dd>

                            <dt class="col-sm-4">NIM</dt>
                            <dd class="col-sm-8">{{ $reservasi->user->nim ?? '-' }}</dd>

                            <dt class="col-sm-4">Program Studi</dt>
                            <dd class="col-sm-8">{{ $reservasi->user->prodi ?? '-' }}</dd>

                            <dt class="col-sm-4">Ruangan</dt>
                            <dd class="col-sm-8">{{ $reservasi->ruangan->kode_ruangan }} - {{ $reservasi->ruangan->nama_ruangan }}</dd>

                            <dt class="col-sm-4">Tanggal</dt>
                            <dd class="col-sm-8">{{ $reservasi->tanggal_reservasi->format('d-m-Y') }}</dd>

                            <dt class="col-sm-4">Jam</dt>
                            <dd class="col-sm-8">{{ substr($reservasi->jam_mulai, 0, 5) }} - {{ substr($reservasi->jam_selesai, 0, 5) }}</dd>

                            <dt class="col-sm-4">Keperluan</dt>
                            <dd class="col-sm-8">{{ $reservasi->keperluan }}</dd>

                            <dt class="col-sm-4">Status</dt>
                            <dd class="col-sm-8">
                                <span class="badge {{ $statusBadge[$reservasi->status] }}">
                                    {{ $statusLabel[$reservasi->status] }}
                                </span>
                            </dd>
                        </dl>

                        <p class="text-muted small text-center mt-3 mb-0">
                            Halaman ini dapat diakses oleh petugas ruangan untuk memverifikasi keaslian peminjaman ruang.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
