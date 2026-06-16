<x-app-layout>
    <x-slot name="header">
        <h2 class="fs-4 fw-bold text-dark">Kalender Jadwal & Peminjaman</h2>
    </x-slot>

    <div class="container py-4">

        <div class="card mb-3">
            <div class="card-body">
                <div class="row align-items-end">
                    <div class="col-md-4">
                        <label for="ruangan_filter" class="form-label">Filter Ruangan</label>
                        <select id="ruangan_filter" class="form-select">
                            <option value="">Semua Ruangan</option>
                            @foreach ($ruangans as $r)
                                <option value="{{ $r->id }}">{{ $r->kode_ruangan }} - {{ $r->nama_ruangan }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-8 text-md-end mt-3 mt-md-0">
                        <span class="badge" style="background-color: #0d6efd;">&nbsp;</span> Jadwal Kuliah
                        &nbsp;&nbsp;
                        <span class="badge" style="background-color: #198754;">&nbsp;</span> Peminjaman Disetujui
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div id="calendar"></div>
            </div>
        </div>
    </div>

    @vite(['resources/js/calendar.js'])
</x-app-layout>
