<x-app-layout>
    <x-slot name="breadcrumb">
        <div class="breadcrumb-item">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
            <a href="#">Data Master</a>
        </div>
        <div class="breadcrumb-item active">
            <span class="separator">/</span>
            Jadwal Akademik
        </div>
    </x-slot>

    <x-slot name="header">
        Daftar Jadwal Akademik
    </x-slot>

    <x-slot name="toolbar">
        <form method="GET" action="{{ route('logistik.jadwal.index') }}" class="d-flex flex-column flex-md-row w-100 gap-3 align-items-md-center">
            <div class="toolbar-actions ms-md-auto d-flex flex-wrap gap-2 w-100 w-md-auto justify-content-start justify-content-md-end">
                <select name="ruangan_id" class="form-select" onchange="this.form.submit()" style="min-width: 180px;">
                    <option value="">Semua Ruangan</option>
                    @foreach ($ruangans as $r)
                        <option value="{{ $r->id }}" @selected((string) $ruanganId === (string) $r->id)>
                            {{ $r->kode_ruangan }} - Lt. {{ $r->lantai }}
                        </option>
                    @endforeach
                </select>

                <select name="hari" class="form-select" onchange="this.form.submit()" style="min-width: 150px;">
                    <option value="">Semua Hari</option>
                    @foreach (['senin','selasa','rabu','kamis','jumat','sabtu','minggu'] as $h)
                        <option value="{{ $h }}" @selected($hari === $h)>{{ ucfirst($h) }}</option>
                    @endforeach
                </select>

                <a href="{{ route('logistik.jadwal.create') }}" class="btn btn-dark d-flex align-items-center gap-2">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    Tambah Jadwal
                </a>
            </div>
        </form>
    </x-slot>

    <div class="teluroom-card mb-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 border-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4 fw-medium text-secondary" style="font-size: 0.8125rem; border-bottom: 1px solid #E5E7EB;">RUANGAN</th>
                            <th class="fw-medium text-secondary" style="font-size: 0.8125rem; border-bottom: 1px solid #E5E7EB;">HARI</th>
                            <th class="fw-medium text-secondary" style="font-size: 0.8125rem; border-bottom: 1px solid #E5E7EB;">JAM</th>
                            <th class="fw-medium text-secondary" style="font-size: 0.8125rem; border-bottom: 1px solid #E5E7EB;">MATA KULIAH</th>
                            <th class="fw-medium text-secondary" style="font-size: 0.8125rem; border-bottom: 1px solid #E5E7EB;">DOSEN</th>
                            <th class="fw-medium text-secondary" style="font-size: 0.8125rem; border-bottom: 1px solid #E5E7EB;">DIBUAT OLEH</th>
                            <th class="fw-medium text-secondary text-end pe-4" style="font-size: 0.8125rem; border-bottom: 1px solid #E5E7EB;">AKSI</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @forelse ($jadwals as $jadwal)
                            <tr>
                                <td class="ps-4 py-3">
                                    <span class="fw-semibold text-dark">{{ $jadwal->ruangan->kode_ruangan }}</span>
                                    <span class="text-secondary d-block" style="font-size:0.8125rem;">{{ $jadwal->ruangan->nama_ruangan }}</span>
                                </td>
                                <td class="py-3 text-dark fw-medium">{{ ucfirst($jadwal->hari) }}</td>
                                <td class="py-3 text-dark">
                                    <span class="fw-semibold">{{ substr($jadwal->jam_mulai, 0, 5) }}</span>
                                    <span class="text-secondary"> - </span>
                                    <span class="fw-semibold">{{ substr($jadwal->jam_selesai, 0, 5) }}</span>
                                </td>
                                <td class="py-3 text-dark">{{ $jadwal->mata_kuliah }}</td>
                                <td class="py-3 text-secondary">{{ $jadwal->dosen }}</td>
                                <td class="py-3 text-secondary" style="font-size: 0.875rem;">{{ $jadwal->creator->name }}</td>
                                <td class="py-3 text-end pe-4">
                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="{{ route('logistik.jadwal.edit', $jadwal) }}" class="btn btn-sm btn-light text-secondary d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; padding: 0;" title="Edit Jadwal">
                                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                                        </a>
                                        <form action="{{ route('logistik.jadwal.destroy', $jadwal) }}" method="POST" class="d-inline" id="formDelete-{{ $jadwal->id }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-sm btn-light text-danger d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; padding: 0;" title="Hapus Jadwal"
                                                data-bs-toggle="modal" data-bs-target="#confirmDeleteModal"
                                                data-form-id="formDelete-{{ $jadwal->id }}"
                                                data-message="Apakah Anda yakin ingin menghapus jadwal mata kuliah {{ $jadwal->mata_kuliah }}?">
                                                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div class="teluroom-empty-state py-0">
                                        <svg width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" class="mb-3"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                        <h3 class="fs-6 text-dark fw-medium">Belum ada jadwal akademik</h3>
                                        <p class="text-secondary mb-4" style="font-size: 0.875rem;">Mulai tambahkan jadwal kuliah untuk memblokir slot ruangan.</p>
                                        <a href="{{ route('logistik.jadwal.create') }}" class="btn btn-dark btn-sm d-inline-flex align-items-center gap-2">
                                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                                            Tambah Jadwal Pertama
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($jadwals->hasPages())
                <div class="px-4 py-3 border-top d-flex justify-content-end">
                    {{ $jadwals->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- Modal Konfirmasi Hapus --}}
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
                <div class="modal-body p-4 text-center">
                    <div class="d-inline-flex align-items-center justify-content-center bg-danger bg-opacity-10 text-danger rounded-circle mb-3" style="width: 48px; height: 48px;">
                        <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                    </div>
                    <h5 class="fw-bold mb-2 text-dark">Hapus Jadwal?</h5>
                    <p class="text-secondary mb-4" id="confirmDeleteMessage" style="font-size: 0.875rem;">Apakah Anda yakin?</p>
                    <div class="d-flex gap-2 w-100">
                        <button type="button" class="btn btn-light w-50" data-bs-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-danger w-50" id="confirmDeleteBtn">Hapus</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const confirmDeleteModal = document.getElementById('confirmDeleteModal');
            if (confirmDeleteModal) {
                confirmDeleteModal.addEventListener('show.bs.modal', function (event) {
                    const btn = event.relatedTarget;
                    document.getElementById('confirmDeleteMessage').textContent = btn.getAttribute('data-message');
                    const formId = btn.getAttribute('data-form-id');
                    document.getElementById('confirmDeleteBtn').onclick = function () {
                        document.getElementById(formId).submit();
                    };
                });
            }
        });
    </script>
</x-app-layout>