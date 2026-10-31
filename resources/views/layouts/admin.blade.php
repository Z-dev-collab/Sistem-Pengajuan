<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Panel Petugas') — {{ config('kelurahan.name') }}</title>
    <meta name="theme-color" content="#0c4a37">
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 48 48%22><rect width=%2248%22 height=%2248%22 rx=%2212%22 fill=%22%230c4a37%22/><path d=%22M24 8l12 4.5V23c0 7.5-5 13-12 15.4C17 36 12 30.5 12 23V12.5L24 8Z%22 fill=%22none%22 stroke=%22%23f5eeda%22 stroke-width=%222%22/><path d=%22M24 17v10M19 22l5-5 5 5M18 31h12%22 fill=%22none%22 stroke=%22%23f5eeda%22 stroke-width=%222%22 stroke-linecap=%22round%22 stroke-linejoin=%22round%22/></svg>">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="admin-body">
    <a class="skip-link" href="#admin-content">Lewati ke konten utama</a>
    <div class="admin-overlay" data-admin-overlay></div>

    <aside class="admin-sidebar" id="admin-sidebar">
        <a href="{{ route('admin.dashboard') }}" class="admin-brand">
            <x-brand class="brand-light" />
        </a>

        <div class="admin-sidebar-label">Ruang kerja</div>
        <nav class="admin-nav" aria-label="Navigasi panel petugas">
            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <x-icon name="dashboard" :size="19" />
                <span>Dashboard</span>
            </a>
            <a href="{{ route('admin.submissions.index') }}" class="{{ request()->routeIs('admin.submissions.*') ? 'active' : '' }}">
                <x-icon name="inbox" :size="19" />
                <span>Pengajuan</span>
                @php $newCount = \App\Models\Submission::where('status', 'submitted')->count(); @endphp
                @if($newCount > 0)
                    <span class="admin-nav-badge">{{ $newCount }}</span>
                @endif
            </a>
            <a href="{{ route('admin.notifications.index') }}" class="{{ request()->routeIs('admin.notifications.*') ? 'active' : '' }}">
                <x-icon name="bell" :size="19" />
                <span>Notifikasi</span>
                @php $pendingNotif = \App\Models\Notification::where('status', 'pending')->count(); @endphp
                @if($pendingNotif > 0)
                    <span class="admin-nav-badge admin-nav-badge-muted">{{ $pendingNotif }}</span>
                @endif
            </a>

            <div class="admin-sidebar-label">Pengaturan konten</div>
            <a href="{{ route('admin.services.index') }}" class="{{ request()->routeIs('admin.services.*') ? 'active' : '' }}">
                <x-icon name="file-text" :size="19" />
                <span>Layanan</span>
            </a>
        </nav>

        <div class="admin-sidebar-bottom">
            <a href="{{ route('home') }}" class="admin-site-link">
                <x-icon name="external-link" :size="17" />
                Lihat situs publik
            </a>
            <div class="admin-user-card">
                <span class="admin-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                <div>
                    <strong>{{ auth()->user()->name }}</strong>
                    <small>{{ auth()->user()->role === 'admin' ? 'Administrator' : 'Petugas' }}</small>
                </div>
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" class="icon-button" title="Keluar" aria-label="Keluar">
                        <x-icon name="logout" :size="18" />
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <div class="admin-main">
        <header class="admin-topbar">
            <div class="admin-topbar-left">
                <button type="button" class="icon-button admin-menu-toggle" data-admin-menu aria-expanded="false" aria-controls="admin-sidebar" aria-label="Buka menu">
                    <x-icon name="menu" :size="22" />
                </button>
                <div>
                    <span class="admin-topbar-eyebrow">Panel Petugas</span>
                    <strong>{{ config('kelurahan.name') }}</strong>
                </div>
            </div>
            <div class="admin-topbar-right">
                <span class="admin-date"><x-icon name="calendar" :size="16" /> {{ now()->translatedFormat('l, d F Y') }}</span>
                <a href="{{ route('home') }}" class="icon-button" title="Lihat situs" aria-label="Lihat situs"><x-icon name="external-link" :size="18" /></a>
                <span class="admin-avatar admin-avatar-sm">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
            </div>
        </header>

        <main class="admin-content" id="admin-content">
            @include('partials.admin-flash')
            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>
