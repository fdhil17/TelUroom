<x-app-layout>
    <x-slot name="breadcrumb">
        <div class="breadcrumb-item">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline></svg>
            <a href="{{ route('mahasiswa.reservasi.index') }}">Pengajuan Saya</a>
        </div>
        <div class="breadcrumb-item active">
            <span class="separator">/</span>
            Detail Pengajuan
        </div>
    </x-slot>

    <x-slot name="header">
        Detail Pengajuan Peminjaman
    </x-slot>

    {{-- Action Toolbar --}}
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <div class="d-flex align-items-center gap-2">
            <x-status-badge :status="$reservasi->status" />
            <span class="text-secondary" style="font-size: 0.875rem;">
                {{ $reservasi->tanggal_reservasi->format('d M Y') }}
                &middot;
                {{ substr($reservasi->jam_mulai, 0, 5) }} - {{ substr($reservasi->jam_selesai, 0, 5) }}
            </span>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('mahasiswa.reservasi.index') }}" class="btn btn-light border d-inline-flex align-items-center gap-2" style="height: 40px;">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                Kembali
            </a>
        </div>
    </div>

    <div class="row g-4">
        {{-- Kolom Kiri: Detail Informasi --}}
        <div class="col-lg-8">
            <div class="teluroom-card mb-4">
                <div class="card-body p-4 p-md-5">
                    <h6 class="fw-bold text-dark mb-4 pb-3 border-bottom" style="font-size: 0.8125rem; letter-spacing: 0.06em; text-transform: uppercase;">Informasi Peminjaman</h6>

                    <div class="row g-4 mb-4">
                        <div class="col-sm-6">
                            <span class="text-secondary d-block mb-1" style="font-size: 0.8125rem; font-weight: 600;">NAMA PEMINJAM</span>
                            <span class="fw-medium text-dark">{{ $reservasi->user->name }}</span>
                        </div>
                        <div class="col-sm-6">
                            <span class="text-secondary d-block mb-1" style="font-size: 0.8125rem; font-weight: 600;">NIM</span>
                            <span class="text-dark">{{ $reservasi->user->nim ?? '-' }}</span>
                        </div>
                    </div>

                    <div class="row g-4 mb-4">
                        <div class="col-sm-6">
                            <span class="text-secondary d-block mb-1" style="font-size: 0.8125rem; font-weight: 600;">PROGRAM STUDI</span>
                            <span class="text-dark">{{ $reservasi->user->prodi ?? '-' }}</span>
                        </div>
                        <div class="col-sm-6">
                            <span class="text-secondary d-block mb-1" style="font-size: 0.8125rem; font-weight: 600;">RUANGAN</span>
                            <span class="fw-medium text-dark d-block">{{ $reservasi->ruangan->kode_ruangan }}</span>
                            <span class="text-secondary" style="font-size: 0.8125rem;">{{ $reservasi->ruangan->nama_ruangan }} &middot; Lt. {{ $reservasi->ruangan->lantai }}</span>
                        </div>
                    </div>

                    <div class="row g-4 mb-4">
                        <div class="col-sm-6">
                            <span class="text-secondary d-block mb-1" style="font-size: 0.8125rem; font-weight: 600;">TANGGAL PEMINJAMAN</span>
                            <span class="fw-medium text-dark">{{ $reservasi->tanggal_reservasi->format('d F Y') }}</span>
                        </div>
                        <div class="col-sm-6">
                            <span class="text-secondary d-block mb-1" style="font-size: 0.8125rem; font-weight: 600;">WAKTU</span>
                            <span class="fw-medium text-dark">
                                {{ substr($reservasi->jam_mulai, 0, 5) }}
                                <span class="text-secondary fw-normal"> - </span>
                                {{ substr($reservasi->jam_selesai, 0, 5) }}
                            </span>
                        </div>
                    </div>

                    <div class="mb-4">
                        <span class="text-secondary d-block mb-2" style="font-size: 0.8125rem; font-weight: 600;">KEPERLUAN / KEGIATAN</span>
                        <div class="p-3 bg-light rounded text-dark" style="font-size: 0.9375rem; border: 1px solid #E5E7EB; line-height: 1.6;">
                            {{ $reservasi->keperluan }}
                        </div>
                    </div>

                    <div>
                        <span class="text-secondary d-block mb-2" style="font-size: 0.8125rem; font-weight: 600;">STATUS PENGAJUAN</span>
                        <x-status-badge :status="$reservasi->status" />
                    </div>
                </div>
            </div>

            {{-- Catatan Petugas --}}
            @if ($reservasi->catatan_ssc || $reservasi->catatan_logistik)
                <div class="teluroom-card">
                    <div class="card-body p-4 p-md-5">
                        <h6 class="fw-bold text-dark mb-4 pb-3 border-bottom" style="font-size: 0.8125rem; letter-spacing: 0.06em; text-transform: uppercase;">Catatan Petugas</h6>

                        @if ($reservasi->catatan_ssc)
                            <div class="mb-4">
                                <span class="text-secondary d-block mb-2" style="font-size: 0.8125rem; font-weight: 600;">CATATAN SSC</span>
                                <div class="p-3 rounded text-dark" style="font-size: 0.875rem; background-color: #f8f9fa; border-left: 3px solid #6b7280; line-height: 1.6;">
                                    {{ $reservasi->catatan_ssc }}
                                </div>
                            </div>
                        @endif

                        @if ($reservasi->catatan_logistik)
                            <div>
                                <span class="text-secondary d-block mb-2" style="font-size: 0.8125rem; font-weight: 600;">CATATAN LOGISTIK</span>
                                <div class="p-3 rounded text-dark" style="font-size: 0.875rem; background-color: #f8f9fa; border-left: 3px solid #6b7280; line-height: 1.6;">
                                    {{ $reservasi->catatan_logistik }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        {{-- Kolom Kanan: QR Code / Progress Status --}}
        <div class="col-lg-4">
            @if ($reservasi->status === 'disetujui' && $reservasi->qr_code)
                <div class="teluroom-card text-center">
                    <div class="card-body p-4">
                        <h6 class="fw-bold text-dark mb-1" style="font-size: 0.8125rem; letter-spacing: 0.06em; text-transform: uppercase;">QR Code Peminjaman</h6>
                        <p class="text-secondary mb-4" style="font-size: 0.8125rem;">Tunjukkan kepada petugas saat hendak menggunakan ruangan.</p>
                        <img src="{{ asset('storage/' . $reservasi->qr_code) }}"
                             alt="QR Code Peminjaman"
                             class="img-fluid rounded mb-4"
                             style="max-width: 200px; border: 1px solid #E5E7EB; padding: 8px;">
                        <div>
                            <a href="{{ asset('storage/' . $reservasi->qr_code) }}" download
                               class="btn btn-dark w-100 d-flex align-items-center justify-content-center gap-2" style="height: 44px;">
                                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                                Unduh QR Code
                            </a>
                        </div>
                    </div>
                </div>
            @else
                <div class="teluroom-card bg-light border-0">
                    <div class="card-body p-4">
                        <h6 class="fw-bold text-dark mb-4" style="font-size: 0.8125rem; text-transform: uppercase; letter-spacing: 0.06em;">Status Proses</h6>

                        @php
                            $steps = [
                                ['label' => 'Pengajuan Dibuat'],
                                ['label' => 'Verifikasi SSC'],
                                ['label' => 'Persetujuan Logistik'],
                                ['label' => 'Disetujui & QR Aktif'],
                            ];
                            $stepMap = [
                                'menunggu_ssc'       => 1,
                                'menunggu_logistik'  => 2,
                                'disetujui'          => 4,
                                'selesai'            => 4,
                                'ditolak_ssc'        => -1,
                                'ditolak_logistik'   => -1,
                                'dibatalkan'         => -1,
                            ];
                            $currentStep = $stepMap[$reservasi->status] ?? 1;
                            $isRejected = $currentStep === -1;
                        @endphp

                        <div class="d-flex flex-column gap-2">
                            @foreach ($steps as $i => $step)
                                @php $num = $i + 1; $done = !$isRejected && $currentStep >= $num; @endphp
                                <div class="d-flex align-items-center gap-3">
                                    <div class="d-flex align-items-center justify-content-center rounded-circle flex-shrink-0"
                                         style="width: 30px; height: 30px; font-size: 0.75rem; font-weight: 700;
                                                background: {{ $done ? '#111827' : '#F3F4F6' }};
                                                color: {{ $done ? '#fff' : '#9CA3AF' }};
                                                border: 2px solid {{ $done ? '#111827' : '#E5E7EB' }};">
                                        @if ($done)
                                            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                                        @else
                                            {{ $num }}
                                        @endif
                                    </div>
                                    <span style="font-size: 0.8125rem; color: {{ $done ? '#111827' : '#9CA3AF' }}; font-weight: {{ $done ? '600' : '400' }};">{{ $step['label'] }}</span>
                                </div>
                                @if ($i < 3)
                                    <div style="width: 2px; height: 14px; background: {{ (!$isRejected && $currentStep > $num) ? '#111827' : '#E5E7EB' }}; margin-left: 14px;"></div>
                                @endif
                            @endforeach

                            @if ($isRejected)
                                <div class="mt-3 p-3 rounded d-flex align-items-center gap-2" style="background-color: #FEF2F2; border: 1px solid #FCA5A5;">
                                    <svg width="16" height="16" fill="none" stroke="#DC2626" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                                    <span class="text-danger fw-medium" style="font-size: 0.8125rem;">{{ ucwords(str_replace('_', ' ', $reservasi->status)) }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>