@csrf

<div class="row g-3 mb-4">
    <div class="col-md-6">
        <label for="kode_ruangan" class="form-label">Kode Ruangan</label>
        <input type="text" name="kode_ruangan" id="kode_ruangan"
            class="form-control teluroom-input @error('kode_ruangan') is-invalid @enderror"
            value="{{ old('kode_ruangan', $ruangan->kode_ruangan ?? '') }}"
            placeholder="Contoh: 1.02">
        @error('kode_ruangan')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="nama_ruangan" class="form-label">Nama Ruangan</label>
        <input type="text" name="nama_ruangan" id="nama_ruangan"
            class="form-control teluroom-input @error('nama_ruangan') is-invalid @enderror"
            value="{{ old('nama_ruangan', $ruangan->nama_ruangan ?? '') }}"
            placeholder="Contoh: Ruang 1.02">
        @error('nama_ruangan')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="row g-3 mb-5">
    <div class="col-md-4">
        <label for="lantai" class="form-label">Lantai</label>
        <input type="number" name="lantai" id="lantai"
            class="form-control teluroom-input @error('lantai') is-invalid @enderror"
            value="{{ old('lantai', $ruangan->lantai ?? '') }}" min="1" max="10">
        @error('lantai')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <label for="kapasitas" class="form-label">Kapasitas (Orang)</label>
        <input type="number" name="kapasitas" id="kapasitas"
            class="form-control teluroom-input @error('kapasitas') is-invalid @enderror"
            value="{{ old('kapasitas', $ruangan->kapasitas ?? '') }}" min="1">
        @error('kapasitas')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <label for="status" class="form-label">Status Ruangan</label>
        @php
            $statusOptions = ['tersedia' => 'Tersedia', 'maintenance' => 'Maintenance'];
            $currentStatus = old('status', $ruangan->status ?? 'tersedia');
        @endphp
        <select name="status" id="status" class="form-select teluroom-input @error('status') is-invalid @enderror">
            @foreach ($statusOptions as $value => $label)
                <option value="{{ $value }}" @selected($currentStatus === $value)>{{ $label }}</option>
            @endforeach
        </select>
        @error('status')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="d-flex justify-content-end gap-3 pt-3 border-top">
    <a href="{{ route('logistik.ruangan.index') }}" class="btn btn-light border px-4 d-flex align-items-center" style="height: 48px;">Batal</a>
    <button type="submit" class="btn btn-dark px-4 d-flex align-items-center" style="height: 48px;">Simpan Data</button>
</div>