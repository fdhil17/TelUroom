<x-app-layout>
    <x-slot name="breadcrumb">
        <div class="breadcrumb-item">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
            <a href="#">Data Master</a>
        </div>
        <div class="breadcrumb-item active">
            <span class="separator">/</span>
            Daftar Ruangan
        </div>
    </x-slot>

    <x-slot name="header">
        Katalog Ruangan
    </x-slot>

    <x-slot name="toolbar">
        <form method="GET" action="{{ route('mahasiswa.ruangan.index') }}" class="d-flex flex-column flex-md-row w-100 gap-3 align-items-md-center">
            <div class="toolbar-search">
                <svg class="search-icon" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                <input type="text" name="search" class="form-control" placeholder="Cari kode atau nama ruangan..." value="{{ request('search') }}">
            </div>
            
            <div class="toolbar-actions ms-md-auto d-flex flex-wrap gap-2 w-100 w-md-auto justify-content-start justify-content-md-end">
                <select name="lantai" class="form-select" onchange="this.form.submit()" style="min-width: 150px;">
                    <option value="">Semua Lantai</option>
                    @for ($i = 1; $i <= 2; $i++)
                        <option value="{{ $i }}" @selected((string) request('lantai') === (string) $i)>Lantai {{ $i }}</option>
                    @endfor
                </select>
            </div>
        </form>
    </x-slot>

    <div class="teluroom-card mb-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 border-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4 fw-medium text-secondary" style="font-size: 0.8125rem; border-bottom: 1px solid #E5E7EB;">KODE</th>
                            <th class="fw-medium text-secondary" style="font-size: 0.8125rem; border-bottom: 1px solid #E5E7EB;">NAMA RUANGAN</th>
                            <th class="fw-medium text-secondary" style="font-size: 0.8125rem; border-bottom: 1px solid #E5E7EB;">LANTAI</th>
                            <th class="fw-medium text-secondary" style="font-size: 0.8125rem; border-bottom: 1px solid #E5E7EB;">KAPASITAS</th>
                            <th class="fw-medium text-secondary" style="font-size: 0.8125rem; border-bottom: 1px solid #E5E7EB;">FASILITAS UTAMA</th>
                            <th class="fw-medium text-secondary text-end pe-4" style="font-size: 0.8125rem; border-bottom: 1px solid #E5E7EB;">AKSI</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @forelse ($ruangans as $ruangan)
                            <tr>
                                <td class="ps-4 py-3 fw-semibold text-dark">{{ $ruangan->kode_ruangan }}</td>
                                <td class="py-3 text-dark">{{ $ruangan->nama_ruangan }}</td>
                                <td class="py-3 text-secondary">{{ $ruangan->lantai }}</td>
                                <td class="py-3 text-secondary">{{ $ruangan->kapasitas }} orang</td>
                                <td class="py-3 text-secondary">
                                    <span class="text-truncate d-inline-block" style="max-width:200px;" title="{{ $ruangan->fasilitas }}">
                                        {{ $ruangan->fasilitas }}
                                    </span>
                                </td>
                                <td class="py-3 text-end pe-4">
                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="{{ route('mahasiswa.ruangan.show', $ruangan) }}" class="btn btn-sm btn-light text-secondary d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; padding: 0;" title="Detail Ruangan">
                                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                        </a>
                                        <a href="{{ route('mahasiswa.reservasi.create', ['ruangan_id' => $ruangan->id]) }}" class="btn btn-sm btn-dark d-flex align-items-center gap-2" style="height: 32px;">
                                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></svg>
                                            Pinjam
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="teluroom-empty-state py-0">
                                        <svg width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" class="mb-3"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                        <h3 class="fs-6 text-dark fw-medium">Tidak ada ruangan</h3>
                                        <p class="text-secondary mb-0" style="font-size: 0.875rem;">Data ruangan tidak ditemukan atau belum tersedia.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($ruangans->hasPages())
                <div class="px-4 py-3 border-top d-flex justify-content-end">
                    {{ $ruangans->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>