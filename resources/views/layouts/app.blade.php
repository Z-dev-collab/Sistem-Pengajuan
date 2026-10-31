<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Pelayanan Administrasi') — {{ config('kelurahan.name') }}</title>
    <meta name="description" content="@yield('meta_description', 'Layanan administrasi online Kelurahan Batu Selicin: pengurusan nikah, ahli waris, dan surat keterangan kematian. Ajukan dari rumah, lacak status dengan mudah.')">
    <meta name="theme-color" content="#0c4a37">
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 48 48%22><rect width=%2248%22 height=%2248%22 rx=%2212%22 fill=%22%230c4a37%22/><path d=%22M24 8l12 4.5V23c0 7.5-5 13-12 15.4C17 36 12 30.5 12 23V12.5L24 8Z%22 fill=%22none%22 stroke=%22%23f5eeda%22 stroke-width=%222%22/><path d=%22M24 17v10M19 22l5-5 5 5M18 31h12%22 fill=%22none%22 stroke=%22%23f5eeda%22 stroke-width=%222%22 stroke-linecap=%22round%22 stroke-linejoin=%22round%22/></svg>">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body>
    <a class="skip-link" href="#main-content">Lewati ke konten utama</a>

    <div class="topbar">
        <div class="container topbar-inner">
            <p class="topbar-location">
                <x-icon name="map-pin" :size="14" />
                <span>Situs resmi pelayanan {{ config('kelurahan.kota') }}</span>
            </p>
            <div class="topbar-links">
                <span class="topbar-hours"><x-icon name="clock" :size="14" /> {{ config('kelurahan.hours') }}</span>
            </div>
        </div>
    </div>

    <header class="site-header" id="site-header">
        <div class="container header-inner">
            <a href="{{ route('home') }}" class="brand-link" aria-label="Beranda {{ config('kelurahan.name') }}">
                <x-brand />
            </a>

            <nav class="main-nav" id="main-nav" aria-label="Navigasi utama">
                <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Beranda</a>
                <a href="{{ route('services.index') }}" class="{{ request()->routeIs('services.*') ? 'active' : '' }}">Layanan</a>
                <a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'active' : '' }}">Kontak</a>
            </nav>

            <div class="header-actions">
                <button type="button" class="text-size-toggle" data-font-toggle aria-pressed="false" title="Perbesar ukuran teks">
                    <span>Aa</span>
                    <span class="sr-only">Perbesar teks</span>
                </button>
                <button type="button" class="menu-toggle" data-menu-toggle aria-expanded="false" aria-controls="main-nav">
                    <x-icon name="menu" :size="24" class="menu-open-icon" />
                    <x-icon name="close" :size="24" class="menu-close-icon" />
                    <span class="sr-only">Buka menu</span>
                </button>
            </div>
        </div>
    </header>

    @include('partials.flash')

    <main id="main-content">
        @yield('content')
    </main>

    <footer class="site-footer">
        <div class="container footer-grid">
            <div class="footer-brand">
                <a href="{{ route('home') }}" class="brand-link">
                    <x-brand class="brand-light" />
                </a>
                <p>Pelayanan administrasi kelurahan yang lebih mudah, transparan, dan ramah bagi seluruh warga.</p>
                <div class="footer-social">
                    <a href="https://wa.me/{{ config('kelurahan.whatsapp') }}?text={{ urlencode(config('kelurahan.whatsapp_message')) }}" target="_blank" rel="noopener" aria-label="WhatsApp Kelurahan">
                        <x-icon name="whatsapp" :size="20" />
                    </a>
                    <a href="mailto:{{ config('kelurahan.email') }}" aria-label="Email Kelurahan">
                        <x-icon name="mail" :size="20" />
                    </a>
                </div>
            </div>

            <div class="footer-col">
                <h2>Layanan</h2>
                <a href="{{ route('services.show', 'pengurusan-nikah') }}">Pengurusan Nikah</a>
                <a href="{{ route('services.show', 'pengurusan-ahli-waris') }}">Pengurusan Ahli Waris</a>
                <a href="{{ route('services.show', 'pengurusan-kematian') }}">Pengurusan Kematian</a>
            </div>

            <div class="footer-col">
                <h2>Bantuan</h2>
                <a href="{{ route('contact') }}">Hubungi Kami</a>
            </div>

            <div class="footer-col footer-contact">
                <h2>Kunjungi Kami</h2>
                <p><x-icon name="map-pin" :size="17" /> {{ config('kelurahan.address') }}</p>
                <p><x-icon name="clock" :size="17" /> {{ config('kelurahan.hours') }}</p>
                <p><x-icon name="phone" :size="17" /> {{ config('kelurahan.phone') }}</p>
            </div>
        </div>
        <div class="container footer-bottom">
            <p>&copy; {{ now()->year }} {{ config('kelurahan.name') }}. Seluruh hak cipta dilindungi.</p>
            <p>{{ config('kelurahan.kecamatan') }}, {{ config('kelurahan.kota') }}</p>
        </div>
    </footer>

    <a class="wa-float" href="https://wa.me/{{ config('kelurahan.whatsapp') }}?text={{ urlencode(config('kelurahan.whatsapp_message')) }}" target="_blank" rel="noopener" aria-label="Chat WhatsApp Kelurahan">
        <x-icon name="whatsapp" :size="26" />
        <span class="wa-float-label">Butuh bantuan?</span>
    </a>

    @stack('scripts')
</body>
</html>
