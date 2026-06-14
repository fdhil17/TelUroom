<x-guest-layout>
    <h5 class="fw-bold text-center mb-1">Daftar Akun Mahasiswa</h5>
    <p class="text-muted text-center small mb-4">Student Registration</p>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="mb-3">
            <label for="name" class="form-label fw-semibold">Nama Lengkap</label>
            <input id="name" type="text" name="name"
                class="form-control @error('name') is-invalid @enderror"
                value="{{ old('name') }}" placeholder="Nama lengkap" required autofocus>
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="nim" class="form-label fw-semibold">NIM</label>
            <input id="nim" type="text" name="nim"
                class="form-control @error('nim') is-invalid @enderror"
                value="{{ old('nim') }}" placeholder="Nomor Induk Mahasiswa" required>
            @error('nim')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="prodi" class="form-label fw-semibold">Program Studi</label>
            <select id="prodi" name="prodi" class="form-select @error('prodi') is-invalid @enderror" required>
                <option value="">-- Pilih Program Studi --</option>
                <optgroup label="Fakultas Teknik Elektro (FTE)">
                    <option value="S1 Teknik Telekomunikasi" @selected(old('prodi') === 'S1 Teknik Telekomunikasi')>S1 Teknik Telekomunikasi</option>
                    <option value="S1 Teknik Elektro" @selected(old('prodi') === 'S1 Teknik Elektro')>S1 Teknik Elektro</option>
                    <option value="S1 Teknik Komputer" @selected(old('prodi') === 'S1 Teknik Komputer')>S1 Teknik Komputer</option>
                </optgroup>
                <optgroup label="Fakultas Rekayasa Industri (FRI)">
                    <option value="S1 Teknik Industri" @selected(old('prodi') === 'S1 Teknik Industri')>S1 Teknik Industri</option>
                    <option value="S1 Sistem Informasi" @selected(old('prodi') === 'S1 Sistem Informasi')>S1 Sistem Informasi</option>
                    <option value="S1 Digital Supply Chain" @selected(old('prodi') === 'S1 Digital Supply Chain')>S1 Digital Supply Chain</option>
                </optgroup>
                <optgroup label="Fakultas Informatika (FIF)">
                    <option value="S1 Informatika" @selected(old('prodi') === 'S1 Informatika')>S1 Informatika</option>
                    <option value="S1 Teknologi Informasi" @selected(old('prodi') === 'S1 Teknologi Informasi')>S1 Teknologi Informasi</option>
                    <option value="S1 Rekayasa Perangkat Lunak" @selected(old('prodi') === 'S1 Rekayasa Perangkat Lunak')>S1 Rekayasa Perangkat Lunak</option>
                    <option value="S1 Data Sains" @selected(old('prodi') === 'S1 Data Sains')>S1 Data Sains</option>
                </optgroup>
                <optgroup label="Fakultas Ekonomi dan Bisnis (FEB)">
                    <option value="S1 Digital Business" @selected(old('prodi') === 'S1 Digital Business')>S1 Digital Business</option>
                </optgroup>
            </select>
            @error('prodi')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="email" class="form-label fw-semibold">Email</label>
            <input id="email" type="email" name="email"
                class="form-control @error('email') is-invalid @enderror"
                value="{{ old('email') }}" placeholder="email@gmail.com" required>
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label fw-semibold">Password</label>
            <input id="password" type="password" name="password"
                class="form-control @error('password') is-invalid @enderror"
                placeholder="Min. 8 karakter" required>
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4">
            <label for="password_confirmation" class="form-label fw-semibold">Konfirmasi Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation"
                class="form-control @error('password_confirmation') is-invalid @enderror"
                placeholder="Ulangi password" required>
            @error('password_confirmation')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
            Buat Akun
        </button>

        <div class="text-center mt-3">
            <small class="text-muted">Sudah punya akun? <a href="{{ route('login') }}" class="text-primary fw-semibold">Sign In</a></small>
        </div>
    </form>
</x-guest-layout>
