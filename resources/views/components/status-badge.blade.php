@props(['status'])

@php
    $badgeConfig = [
        'menunggu_ssc'      => ['class' => 'bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25', 'icon' => '<path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>'],
        'menunggu_logistik' => ['class' => 'bg-info bg-opacity-10 text-info border border-info border-opacity-25', 'icon' => '<path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>'],
        'disetujui'         => ['class' => 'bg-success bg-opacity-10 text-success border border-success border-opacity-25', 'icon' => '<path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>'],
        'ditolak_ssc'       => ['class' => 'bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25', 'icon' => '<path d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>'],
        'ditolak_logistik'  => ['class' => 'bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25', 'icon' => '<path d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>'],

        'tersedia'          => ['class' => 'bg-success bg-opacity-10 text-success border border-success border-opacity-25', 'icon' => '<path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>'],
        'digunakan_kuliah'  => ['class' => 'bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25', 'icon' => '<path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>'],
        'sudah_direservasi' => ['class' => 'bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25', 'icon' => '<path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>'],
        'maintenance'       => ['class' => 'bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25', 'icon' => '<path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>'],
    ];

    $config = $badgeConfig[$status] ?? ['class' => 'bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25', 'icon' => '<path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>'];
    $label = isset($badgeConfig[$status]) ? __('status.' . $status . '.label') : 'Status Tidak Diketahui';
@endphp

<span {{ $attributes->merge(['class' => 'badge rounded-pill d-inline-flex align-items-center gap-1 px-3 py-2 fw-semibold ' . $config['class']]) }} style="box-shadow: 0 2px 8px rgba(0,0,0,0.05); letter-spacing: 0.02em;">
    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
        {!! $config['icon'] !!}
    </svg>
    {{ $label }}
</span>
