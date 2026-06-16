<x-guest-layout>
    <div class="teluroom-auth-card">
        <div class="text-center mb-4 pb-2">
            <h1 class="auth-title mb-2">Daftar Akun Mahasiswa</h1>
            <p class="auth-subheading mb-0">Lengkapi data untuk membuat akun TelURoom</p>
        </div>

        <form method="POST" action="{{ route('register') }}" novalidate>
            @csrf

            <div class="mb-4">
                <label for="name" class="form-label">Nama Lengkap</label>
                <input id="name" type="text" name="name"
                    class="form-control @error('name') is-invalid @enderror"
                    value="{{ old('name') }}" placeholder="Nama lengkap sesuai KTP/KTM" required autofocus>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label for="nim" class="form-label">Nomor Induk Mahasiswa (NIM)</label>
                <input id="nim" type="text" name="nim"
                    class="form-control @error('nim') is-invalid @enderror"
                    value="{{ old('nim') }}" placeholder="Masukkan NIM" required>
                @error('nim')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label for="prodi" class="form-label">Program Studi</label>
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

            <div class="mb-4">
                <label for="email" class="form-label">Alamat Email</label>
                <input id="email" type="email" name="email"
                    class="form-control @error('email') is-invalid @enderror"
                    value="{{ old('email') }}" placeholder="mahasiswa@student.telkomuniversity.ac.id" required>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label for="password" class="form-label">Kata Sandi</label>
                <div class="input-group">
                    <input id="password" type="password" name="password"
                        class="form-control @error('password') is-invalid @enderror"
                        placeholder="Minimal 8 karakter" required>
                    <button class="input-group-text password-toggle" type="button" tabindex="-1">
                        <svg class="eye-icon" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                    </button>
                </div>
                @error('password')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-5">
                <label for="password_confirmation" class="form-label">Konfirmasi Kata Sandi</label>
                <div class="input-group">
                    <input id="password_confirmation" type="password" name="password_confirmation"
                        class="form-control @error('password_confirmation') is-invalid @enderror"
                        placeholder="Ulangi kata sandi" required>
                    <button class="input-group-text password-toggle" type="button" tabindex="-1">
                        <svg class="eye-icon" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                    </button>
                </div>
                @error('password_confirmation')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary w-100 mb-4">
                Daftar Akun
            </button>

            <div class="text-center" style="font-size: 0.875rem;">
                <span class="text-secondary">Sudah punya akun?</span>
                <a href="{{ route('login') }}" class="fw-semibold text-primary text-decoration-none" style="color: #111827 !important;">Masuk di sini</a>
            </div>
        </form>
    </div>
</x-guest-layout>