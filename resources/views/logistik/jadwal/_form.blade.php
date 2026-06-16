@csrf

<div class="row g-3 mb-4">
    <div class="col-md-6">
        <label for="ruangan_id" class="form-label">Ruangan</label>
        <select name="ruangan_id" id="ruangan_id" class="form-select teluroom-input @error('ruangan_id') is-invalid @enderror">
            <option value="">-- Pilih Ruangan --</option>
            @foreach ($ruangans as $r)
                <option value="{{ $r->id }}" @selected((string) old('ruangan_id', $jadwal->ruangan_id ?? '') === (string) $r->id)>
                    {{ $r->kode_ruangan }} - Lantai {{ $r->lantai }} (Kapasitas {{ $r->kapasitas }} orang)
                </option>
            @endforeach
        </select>
        @error('ruangan_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="hari" class="form-label">Hari</label>
        @php
            $hariOptions = ['senin','selasa','rabu','kamis','jumat','sabtu','minggu'];
            $currentHari = old('hari', $jadwal->hari ?? '');
        @endphp
        <select name="hari" id="hari" class="form-select teluroom-input @error('hari') is-invalid @enderror">
            <option value="">-- Pilih Hari --</option>
            @foreach ($hariOptions as $h)
                <option value="{{ $h }}" @selected($currentHari === $h)>{{ ucfirst($h) }}</option>
            @endforeach
        </select>
        @error('hari')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="row g-3 mb-5">
    <div class="col-md-3">
        <label for="jam_mulai" class="form-label">Jam Mulai</label>
        <input type="time" name="jam_mulai" id="jam_mulai"
            class="form-control teluroom-input @error('jam_mulai') is-invalid @enderror"
            value="{{ old('jam_mulai', isset($jadwal) ? substr($jadwal->jam_mulai, 0, 5) : '') }}">
        @error('jam_mulai')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-3">
        <label for="jam_selesai" class="form-label">Jam Selesai</label>
        <input type="time" name="jam_selesai" id="jam_selesai"
            class="form-control teluroom-input @error('jam_selesai') is-invalid @enderror"
            value="{{ old('jam_selesai', isset($jadwal) ? substr($jadwal->jam_selesai, 0, 5) : '') }}">
        @error('jam_selesai')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-3">
        <label for="mata_kuliah" class="form-label">Mata Kuliah</label>
        <input type="text" name="mata_kuliah" id="mata_kuliah"
            class="form-control teluroom-input @error('mata_kuliah') is-invalid @enderror"
            value="{{ old('mata_kuliah', $jadwal->mata_kuliah ?? '') }}" placeholder="MK">
        @error('mata_kuliah')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-3">
        <label for="dosen" class="form-label">Dosen</label>
        <input type="text" name="dosen" id="dosen"
            class="form-control teluroom-input @error('dosen') is-invalid @enderror"
            value="{{ old('dosen', $jadwal->dosen ?? '') }}" placeholder="Nama Dosen">
        @error('dosen')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="d-flex justify-content-end gap-3 pt-3 border-top">
    <a href="{{ route('logistik.jadwal.index') }}" class="btn btn-light border px-4 d-flex align-items-center" style="height: 48px;">Batal</a>
    <button type="submit" class="btn btn-dark px-4 d-flex align-items-center" style="height: 48px;">Simpan Jadwal</button>
</div>