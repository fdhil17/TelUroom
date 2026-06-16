<?php

namespace App\Enums;

enum StatusEnum: string
{
    // Status Ruangan
    case TERSEDIA = 'tersedia';
    case DIGUNAKAN_KULIAH = 'digunakan_kuliah';
    case SUDAH_DIRESERVASI = 'sudah_direservasi';
    case MAINTENANCE = 'maintenance';

    // Status Peminjaman (Pengajuan)
    case MENUNGGU_SSC = 'menunggu_ssc';
    case MENUNGGU_LOGISTIK = 'menunggu_logistik';
    case DISETUJUI = 'disetujui';
    case DITOLAK_SSC = 'ditolak_ssc';
    case DITOLAK_LOGISTIK = 'ditolak_logistik';

    /**
     * Mengambil label UI yang sudah disesuaikan dari language file
     *
     * @return string
     */
    public function label(): string
    {
        return __('status.' . $this->value . '.label');
    }

    /**
     * Parsing aman: Jika valid kembalikan enum string, jika tidak kembalikan string asli.
     * Mencegah double fallback dan error pada query/assignment.
     *
     * @param string $value
     * @return string
     */
    public static function safeParse(string $value): string
    {
        return self::tryFrom($value)?->value ?? $value;
    }

    /**
     * Mengambil list seluruh nilai enum
     *
     * @return array
     */
    public static function all(): array
    {
        return array_column(self::cases(), 'value');
    }
}
