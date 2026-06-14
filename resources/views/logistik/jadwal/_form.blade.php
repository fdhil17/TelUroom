@csrf

@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="row">
    <div class="col-md-6 mb-3">
        <label for="ruangan_id" class="form-label">Ruangan</label>
        <select name="ruangan_id" id="ruangan_id" class="form-select @error('ruangan_id') is-invalid @enderror">
            <option value="">-- Pilih Ruangan --</option>
            @foreach ($ruangans as $r)
                <option value="{{ $r->id }}" @selected((string) old('ruangan_id', $jadwal->ruangan_id ?? '') === (string) $r->id)>
                    {{ $r->kode_ruangan }} — Lantai {{ $r->lantai }} · Kapasitas {{ $r->kapasitas }} orang
                </option>
            @endforeach
        </select>
        @error('ruangan_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label for="hari" class="form-label">Hari</label>
        @php
            $hariOptions = ['senin','selasa','rabu','kamis','jumat','sabtu','minggu'];
            $currentHari = old('hari', $jadwal->hari ?? '');
        @endphp
        <select name="hari" id="hari" class="form-select @error('hari') is-invalid @enderror">
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

<div class="row">
    <div class="col-md-3 mb-3">
        <label for="jam_mulai" class="form-label">Jam Mulai</label>
        <input type="time" name="jam_mulai" id="jam_mulai"
            class="form-control @error('jam_mulai') is-invalid @enderror"
            value="{{ old('jam_mulai', isset($jadwal) ? substr($jadwal->jam_mulai, 0, 5) : '') }}">
        @error('jam_mulai')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-3 mb-3">
        <label for="jam_selesai" class="form-label">Jam Selesai</label>
        <input type="time" name="jam_selesai" id="jam_selesai"
            class="form-control @error('jam_selesai') is-invalid @enderror"
            value="{{ old('jam_selesai', isset($jadwal) ? substr($jadwal->jam_selesai, 0, 5) : '') }}">
        @error('jam_selesai')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-3 mb-3">
        <label for="mata_kuliah" class="form-label">Mata Kuliah</label>
        <input type="text" name="mata_kuliah" id="mata_kuliah"
            class="form-control @error('mata_kuliah') is-invalid @enderror"
            value="{{ old('mata_kuliah', $jadwal->mata_kuliah ?? '') }}">
        @error('mata_kuliah')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-3 mb-3">
        <label for="dosen" class="form-label">Dosen</label>
        <input type="text" name="dosen" id="dosen"
            class="form-control @error('dosen') is-invalid @enderror"
            value="{{ old('dosen', $jadwal->dosen ?? '') }}">
        @error('dosen')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<hr style="border-color: #E4E7EF;" class="my-3">

<div class="d-flex justify-content-end gap-2">
    <a href="{{ route('logistik.jadwal.index') }}" class="btn btn-secondary">Batal</a>
    <button type="submit" class="btn btn-primary">Simpan</button>
</div>
