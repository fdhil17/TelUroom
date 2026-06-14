<x-app-layout>
    <x-slot name="header">
        <h2 class="fs-4 fw-bold text-dark">Detail Pengajuan Peminjaman</h2>
    </x-slot>

    <div class="container py-4">
        <div class="card">
            <div class="card-body">
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

                <dl class="row">
                    <dt class="col-sm-3">Nama Mahasiswa</dt>
                    <dd class="col-sm-9">{{ $reservasi->user->name }}</dd>

                    <dt class="col-sm-3">NIM</dt>
                    <dd class="col-sm-9">{{ $reservasi->user->nim ?? '-' }}</dd>

                    <dt class="col-sm-3">Program Studi</dt>
                    <dd class="col-sm-9">{{ $reservasi->user->prodi ?? '-' }}</dd>

                    <dt class="col-sm-3">Ruangan</dt>
                    <dd class="col-sm-9">{{ $reservasi->ruangan->kode_ruangan }} - {{ $reservasi->ruangan->nama_ruangan }}</dd>

                    <dt class="col-sm-3">Tanggal</dt>
                    <dd class="col-sm-9">{{ $reservasi->tanggal_reservasi->format('d-m-Y') }}</dd>

                    <dt class="col-sm-3">Jam</dt>
                    <dd class="col-sm-9">{{ substr($reservasi->jam_mulai, 0, 5) }} - {{ substr($reservasi->jam_selesai, 0, 5) }}</dd>

                    <dt class="col-sm-3">Keperluan</dt>
                    <dd class="col-sm-9">{{ $reservasi->keperluan }}</dd>

                    <dt class="col-sm-3">Status</dt>
                    <dd class="col-sm-9">
                        <span class="badge {{ $statusBadge[$reservasi->status] }}">
                            {{ $statusLabel[$reservasi->status] }}
                        </span>
                    </dd>

                    @if ($reservasi->catatan_ssc)
                        <dt class="col-sm-3">Catatan SSC</dt>
                        <dd class="col-sm-9">{{ $reservasi->catatan_ssc }}</dd>
                    @endif

                    @if ($reservasi->catatan_logistik)
                        <dt class="col-sm-3">Catatan Logistik</dt>
                        <dd class="col-sm-9">{{ $reservasi->catatan_logistik }}</dd>
                    @endif

                    @if ($reservasi->status === 'disetujui' && $reservasi->qr_code)
                        <dt class="col-sm-3">QR Code</dt>
                        <dd class="col-sm-9">
                            <img src="{{ asset('storage/' . $reservasi->qr_code) }}" alt="QR Code Peminjaman" style="width: 200px;"><br>
                            <a href="{{ asset('storage/' . $reservasi->qr_code) }}" download class="btn btn-sm btn-outline-primary mt-2">
                                Unduh QR Code
                            </a>
                        </dd>
                    @endif
                </dl>
            </div>
        </div>

        <div class="mt-3">
            <a href="{{ route('mahasiswa.reservasi.index') }}" class="btn btn-outline-secondary">Kembali</a>
        </div>
    </div>
</x-app-layout>
