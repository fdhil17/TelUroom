<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('verifikasi.judul_halaman') }}</title>
    @vite(['resources/sass/app.scss'])
</head>
<body class="bg-light d-flex align-items-center min-vh-100">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="teluroom-card border-0 shadow-sm">
                    <div class="card-body p-4 p-md-5">
                        <div class="text-center mb-4 pb-3 border-bottom">
                            <h4 class="fw-bold text-dark mb-1" style="font-family: 'Eixample Villa Extra Bold', sans-serif;">TelUroom</h4>
                            <p class="text-secondary mb-0" style="font-size: 0.875rem;">{{ __('verifikasi.subjudul') }}</p>
                        </div>

                        @if ($reservasi->status === 'disetujui')
                            <div class="p-3 mb-4 rounded d-flex align-items-center justify-content-center gap-2" style="background-color: #ECFDF5; border: 1px solid #A7F3D0; color: #059669;">
                                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                                <strong class="fw-semibold">{{ __('verifikasi.valid') }}</strong>
                            </div>
                        @else
                            <div class="p-3 mb-4 rounded d-flex align-items-center justify-content-center gap-2" style="background-color: #FEF2F2; border: 1px solid #FCA5A5; color: #DC2626;">
                                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                                <strong class="fw-semibold">{{ __('verifikasi.tidak_valid') }}</strong>
                            </div>
                        @endif

                        <div class="d-flex flex-column gap-3 mb-4">
                            <div class="d-flex justify-content-between align-items-start border-bottom pb-2">
                                <span class="text-secondary" style="font-size: 0.8125rem; font-weight: 600;">{{ __('verifikasi.no_pengajuan') }}</span>
                                <span class="text-dark fw-medium">#{{ $reservasi->id }}</span>
                            </div>

                            <div class="d-flex justify-content-between align-items-start border-bottom pb-2">
                                <span class="text-secondary" style="font-size: 0.8125rem; font-weight: 600;">{{ __('verifikasi.label_nama') }}</span>
                                <span class="text-dark fw-medium text-end">{{ $reservasi->user->name }}<br><span class="text-secondary" style="font-size: 0.8125rem;">{{ $reservasi->user->nim ?? '-' }} &middot; {{ $reservasi->user->prodi ?? '-' }}</span></span>
                            </div>

                            <div class="d-flex justify-content-between align-items-start border-bottom pb-2">
                                <span class="text-secondary" style="font-size: 0.8125rem; font-weight: 600;">{{ __('pengajuan.label_ruangan') }}</span>
                                <span class="text-dark fw-medium text-end">{{ $reservasi->ruangan->kode_ruangan }}<br><span class="text-secondary" style="font-size: 0.8125rem;">{{ $reservasi->ruangan->nama_ruangan }}</span></span>
                            </div>

                            <div class="d-flex justify-content-between align-items-start border-bottom pb-2">
                                <span class="text-secondary" style="font-size: 0.8125rem; font-weight: 600;">TANGGAL & WAKTU</span>
                                <span class="text-dark fw-medium text-end">{{ $reservasi->tanggal_reservasi->format('d M Y') }}<br><span class="text-secondary" style="font-size: 0.8125rem;">{{ substr($reservasi->jam_mulai, 0, 5) }} - {{ substr($reservasi->jam_selesai, 0, 5) }}</span></span>
                            </div>

                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-secondary" style="font-size: 0.8125rem; font-weight: 600;">STATUS</span>
                                <span><x-status-badge :status="$reservasi->status" /></span>
                            </div>
                        </div>

                        <div class="text-center mt-5">
                            <p class="text-secondary mb-0" style="font-size: 0.75rem;">
                                {{ __('verifikasi.footer_info') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>