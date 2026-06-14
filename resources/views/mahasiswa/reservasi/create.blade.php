<x-app-layout>
    <x-slot name="header">
        <h2 class="fs-4 fw-bold text-dark">Ajukan Peminjaman Ruangan</h2>
    </x-slot>

    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">

                @if ($errors->any())
                    <div class="alert alert-danger mb-4">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="card">
                    <div class="card-header fw-bold card-header-dark">Formulir Pengajuan Peminjaman</div>
                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('mahasiswa.reservasi.store') }}">
                            @csrf

                            {{-- Ruangan --}}
                            <div class="mb-4">
                                <label for="ruangan_id" class="form-label">Ruangan</label>
                                <select name="ruangan_id" id="ruangan_id"
                                    class="form-select @error('ruangan_id') is-invalid @enderror">
                                    <option value="">-- Pilih Ruangan --</option>
                                    @foreach ($ruangans as $r)
                                        <option value="{{ $r->id }}"
                                            @selected((string) old('ruangan_id', $selectedRuanganId) === (string) $r->id)>
                                            {{ $r->kode_ruangan }} — Lantai {{ $r->lantai }} · Kapasitas {{ $r->kapasitas }} orang
                                        </option>
                                    @endforeach
                                </select>
                                @error('ruangan_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Tanggal & Jam --}}
                            <div class="row mb-4">
                                <div class="col-md-4">
                                    <label for="tanggal_reservasi" class="form-label">Tanggal Peminjaman</label>
                                    <input type="date" name="tanggal_reservasi" id="tanggal_reservasi"
                                        class="form-control @error('tanggal_reservasi') is-invalid @enderror"
                                        value="{{ old('tanggal_reservasi') }}" min="{{ date('Y-m-d') }}">
                                    @error('tanggal_reservasi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label for="jam_mulai" class="form-label">Jam Mulai</label>
                                    <input type="time" name="jam_mulai" id="jam_mulai"
                                        class="form-control @error('jam_mulai') is-invalid @enderror"
                                        value="{{ old('jam_mulai') }}">
                                    @error('jam_mulai')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label for="jam_selesai" class="form-label">Jam Selesai</label>
                                    <input type="time" name="jam_selesai" id="jam_selesai"
                                        class="form-control @error('jam_selesai') is-invalid @enderror"
                                        value="{{ old('jam_selesai') }}">
                                    @error('jam_selesai')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Keperluan --}}
                            <div class="mb-4">
                                <label for="keperluan" class="form-label">Keperluan</label>
                                <textarea name="keperluan" id="keperluan" rows="3"
                                    class="form-control @error('keperluan') is-invalid @enderror"
                                    placeholder="Contoh: Rapat organisasi, Latihan presentasi, dll.">{{ old('keperluan') }}</textarea>
                                @error('keperluan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <hr class="my-4" style="border-color: #E4E7EF;">

                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('mahasiswa.ruangan.index') }}" class="btn btn-secondary">Batal</a>
                                <button type="submit" class="btn btn-primary">Ajukan Peminjaman</button>
                            </div>

                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
