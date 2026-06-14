@csrf

<div class="row">
    <div class="col-md-6 mb-3">
        <label for="kode_ruangan" class="form-label">Kode Ruangan</label>
        <input type="text" name="kode_ruangan" id="kode_ruangan"
            class="form-control @error('kode_ruangan') is-invalid @enderror"
            value="{{ old('kode_ruangan', $ruangan->kode_ruangan ?? '') }}"
            placeholder="Contoh: 1.02">
        @error('kode_ruangan')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label for="nama_ruangan" class="form-label">Nama Ruangan</label>
        <input type="text" name="nama_ruangan" id="nama_ruangan"
            class="form-control @error('nama_ruangan') is-invalid @enderror"
            value="{{ old('nama_ruangan', $ruangan->nama_ruangan ?? '') }}"
            placeholder="Contoh: Ruang 1.02">
        @error('nama_ruangan')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="row">
    <div class="col-md-4 mb-3">
        <label for="lantai" class="form-label">Lantai</label>
        <input type="number" name="lantai" id="lantai"
            class="form-control @error('lantai') is-invalid @enderror"
            value="{{ old('lantai', $ruangan->lantai ?? '') }}" min="1" max="10">
        @error('lantai')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4 mb-3">
        <label for="kapasitas" class="form-label">Kapasitas</label>
        <input type="number" name="kapasitas" id="kapasitas"
            class="form-control @error('kapasitas') is-invalid @enderror"
            value="{{ old('kapasitas', $ruangan->kapasitas ?? '') }}" min="1">
        @error('kapasitas')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4 mb-3">
        <label for="status" class="form-label">Status</label>
        @php
            $statusOptions = ['tersedia' => 'Tersedia', 'maintenance' => 'Maintenance'];
            $currentStatus = old('status', $ruangan->status ?? 'tersedia');
        @endphp
        <select name="status" id="status" class="form-select @error('status') is-invalid @enderror">
            @foreach ($statusOptions as $value => $label)
                <option value="{{ $value }}" @selected($currentStatus === $value)>{{ $label }}</option>
            @endforeach
        </select>
        @error('status')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<hr style="border-color: #E4E7EF;" class="my-3">

<div class="d-flex justify-content-end gap-2">
    <a href="{{ route('logistik.ruangan.index') }}" class="btn btn-secondary">Batal</a>
    <button type="submit" class="btn btn-primary">Simpan</button>
</div>
