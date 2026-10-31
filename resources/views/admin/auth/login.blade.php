<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk Petugas — {{ config('kelurahan.name') }}</title>
    <meta name="robots" content="noindex, nofollow">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="login-body">
    <div class="login-shell">
        <aside class="login-panel">
            <a href="{{ route('home') }}" class="brand-link">
                <x-brand class="brand-light" />
            </a>

            <div class="login-panel-copy">
                <span class="login-panel-mark"><x-icon name="shield-check" :size="30" /></span>
                <p class="eyebrow eyebrow-light"><span class="eyebrow-dot"></span> Ruang kerja petugas</p>
                <h1>Pelayanan yang rapi dimulai dari sini.</h1>
                <p>Periksa berkas warga, berikan catatan perbaikan, dan perbarui status pengajuan dalam satu tempat.</p>
                <ul class="login-feature-list">
                    <li><x-icon name="check" :size="15" /> Verifikasi berkas lebih terstruktur</li>
                    <li><x-icon name="check" :size="15" /> Notifikasi otomatis ke warga</li>
                    <li><x-icon name="check" :size="15" /> Riwayat pelayanan yang jelas</li>
                </ul>
            </div>

            <p class="login-panel-footer">{{ config('kelurahan.kecamatan') }}<br>{{ config('kelurahan.kota') }}, {{ config('kelurahan.provinsi') }}</p>
        </aside>

        <main class="login-main">
            <div class="login-form-wrap">
                <a href="{{ route('home') }}" class="login-back"><x-icon name="arrow-left" :size="16" /> Kembali ke situs</a>
                <div class="login-heading">
                    <p class="eyebrow"><span class="eyebrow-dot"></span> Akses terbatas</p>
                    <h1>Masuk ke panel petugas</h1>
                    <p>Gunakan akun yang telah diberikan oleh administrator kelurahan.</p>
                </div>

                @if($errors->any())
                    <div class="login-error" role="alert">
                        <x-icon name="alert-circle" :size="19" />
                        <p>{{ $errors->first() }}</p>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.login.store') }}" class="login-form">
                    @csrf
                    <div class="field">
                        <label for="email">Email petugas</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="nama@batuselicin.go.id" autocomplete="username" required autofocus>
                    </div>
                    <div class="field">
                        <label for="password">Kata sandi</label>
                        <div class="password-field">
                            <input type="password" id="password" name="password" placeholder="Masukkan kata sandi" autocomplete="current-password" required>
                            <button type="button" class="password-toggle" data-password-toggle aria-label="Tampilkan kata sandi">
                                <x-icon name="eye" :size="18" />
                            </button>
                        </div>
                    </div>
                    <label class="remember-row">
                        <input type="checkbox" name="remember" value="1" @checked(old('remember'))>
                        <span>Ingat saya di perangkat ini</span>
                    </label>
                    <button type="submit" class="btn btn-primary btn-lg btn-block">
                        Masuk
                        <x-icon name="arrow-right" :size="18" />
                    </button>
                </form>

                <p class="login-help">Lupa kata sandi? Hubungi administrator kelurahan untuk pengaturan ulang.</p>
            </div>
        </main>
    </div>
</body>
</html>
