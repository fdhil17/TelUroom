<x-app-layout>
    <x-slot name="breadcrumb">
        <div class="breadcrumb-item">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline></svg>
            <a href="#">Pengajuan Saya</a>
        </div>
        <div class="breadcrumb-item active">
            <span class="separator">/</span>
            Buat Pengajuan Baru
        </div>
    </x-slot>

    <x-slot name="header">
        Formulir Peminjaman Ruangan
    </x-slot>

    <div class="d-flex justify-content-center">
        <div style="width: 100%; max-width: 800px;">
            <div class="teluroom-card mb-4">
                <div class="card-body p-4 p-md-5">
                    <form method="POST" action="{{ route('mahasiswa.reservasi.store') }}" novalidate>
                        @csrf

                        {{-- Ruangan --}}
                        <div class="mb-4">
                            <label for="ruangan_id" class="form-label">Ruangan</label>
                            <select name="ruangan_id" id="ruangan_id" class="form-select teluroom-input @error('ruangan_id') is-invalid @enderror">
                                <option value="">-- Pilih Ruangan --</option>
                                @foreach ($ruangans as $r)
                                    <option value="{{ $r->id }}" @selected((string) old('ruangan_id', $selectedRuanganId ?? '') === (string) $r->id)>
                                        {{ $r->kode_ruangan }} - Lantai {{ $r->lantai }} (Kapasitas {{ $r->kapasitas }} orang)
                                    </option>
                                @endforeach
                            </select>
                            @error('ruangan_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Tanggal & Jam --}}
                        <div class="row mb-4 g-3">
                            <div class="col-md-4">
                                <label for="tanggal_reservasi" class="form-label">Tanggal Peminjaman</label>
                                <input type="date" name="tanggal_reservasi" id="tanggal_reservasi"
                                    class="form-control teluroom-input @error('tanggal_reservasi') is-invalid @enderror"
                                    value="{{ old('tanggal_reservasi') }}" min="{{ date('Y-m-d') }}">
                                @error('tanggal_reservasi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="jam_mulai" class="form-label">Jam Mulai</label>
                                <input type="time" name="jam_mulai" id="jam_mulai"
                                    class="form-control teluroom-input @error('jam_mulai') is-invalid @enderror"
                                    value="{{ old('jam_mulai') }}">
                                @error('jam_mulai')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="jam_selesai" class="form-label">Jam Selesai</label>
                                <input type="time" name="jam_selesai" id="jam_selesai"
                                    class="form-control teluroom-input @error('jam_selesai') is-invalid @enderror"
                                    value="{{ old('jam_selesai') }}">
                                @error('jam_selesai')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Keperluan --}}
                        <div class="mb-5">
                            <label for="keperluan" class="form-label">Keperluan / Nama Kegiatan</label>
                            <textarea name="keperluan" id="keperluan" rows="3"
                                class="form-control teluroom-input @error('keperluan') is-invalid @enderror"
                                placeholder="Contoh: Rapat organisasi, Latihan presentasi, dll.">{{ old('keperluan') }}</textarea>
                            @error('keperluan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-3 pt-3 border-top">
                            <a href="{{ route('mahasiswa.ruangan.index') }}" class="btn btn-light border px-4 d-flex align-items-center" style="height: 48px;">Batal</a>
                            <button type="submit" class="btn btn-dark px-4 d-flex align-items-center" style="height: 48px;">Kirim Pengajuan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>