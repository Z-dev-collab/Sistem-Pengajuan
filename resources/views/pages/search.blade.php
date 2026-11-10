@extends('layouts.app')

@section('title', $query ? 'Hasil pencarian “' . $query . '”' : 'Pencarian')

@section('content')
    <section class="page-hero search-hero">
        <div class="container">
            <nav class="breadcrumbs" aria-label="Breadcrumb">
                <a href="{{ route('home') }}">Beranda</a>
                <x-icon name="chevron-right" :size="14" />
                <span>Pencarian</span>
            </nav>
            <p class="eyebrow"><span class="eyebrow-dot"></span> Pencarian</p>
            <h1>Temukan layanan yang Anda cari.</h1>
            <form action="{{ route('search') }}" method="GET" class="page-search page-search-wide" role="search">
                <x-icon name="search" :size="20" />
                <label for="search-query" class="sr-only">Kata kunci pencarian</label>
                <input type="search" id="search-query" name="q" value="{{ $query }}" placeholder="Contoh: surat kematian, ahli waris, biaya nikah…" autofocus>
                <button type="submit" class="btn btn-primary">Cari</button>
            </form>
            @if($query)
                <p class="search-summary">
                    Menampilkan hasil untuk <strong>“{{ $query }}”</strong> ·
                    {{ $services->count() }} layanan
                </p>
            @endif
        </div>
    </section>

    <section class="section">
        <div class="container">
            @if(! $query || mb_strlen($query) < 2)
                <div class="empty-state">
                    <span class="empty-state-icon"><x-icon name="search" :size="30" /></span>
                    <h2>Ketik kata kunci pencarian</h2>
                    <p>Masukkan nama layanan atau topik, misalnya “surat kematian”, “ahli waris”, atau “biaya”.</p>
                    <div class="quick-search-chips">
                        <a href="{{ route('search', ['q' => 'surat kematian']) }}">surat kematian</a>
                        <a href="{{ route('search', ['q' => 'ahli waris']) }}">ahli waris</a>
                        <a href="{{ route('search', ['q' => 'nikah']) }}">nikah</a>
                        <a href="{{ route('search', ['q' => 'biaya']) }}">biaya</a>
                    </div>
                </div>
            @elseif($services->isEmpty())
                <div class="empty-state">
                    <span class="empty-state-icon"><x-icon name="search" :size="30" /></span>
                    <h2>Belum ada hasil untuk “{{ $query }}”</h2>
                    <p>Coba gunakan kata kunci lain, atau tanyakan langsung kepada petugas kami.</p>
                    <div class="empty-state-actions">
                        <a href="{{ route('services.index') }}" class="btn btn-primary">Lihat semua layanan</a>
                        <a href="https://wa.me/{{ config('kelurahan.whatsapp') }}?text={{ urlencode('Halo, saya mencari informasi tentang '.$query.'.') }}" target="_blank" rel="noopener" class="btn btn-outline">
                            <x-icon name="whatsapp" :size="17" /> Tanya petugas
                        </a>
                    </div>
                </div>
            @else
                @if($services->isNotEmpty())
                    <div class="search-section">
                        <div class="section-heading">
                            <h2>Layanan</h2>
                            <p>{{ $services->count() }} layanan sesuai pencarian Anda.</p>
                        </div>
                        <div class="search-result-list">
                            @foreach($services as $service)
                                <a href="{{ route('services.show', $service->slug) }}" class="search-result-card">
                                    <span class="service-icon service-icon-{{ ($service->sort_order % 3) ?: 1 }}">
                                        <x-icon :name="$service->icon" :size="26" />
                                    </span>
                                    <div class="search-result-copy">
                                        <h3>{{ $service->name }}</h3>
                                        <p>{{ $service->tagline }}</p>
                                        <div class="search-result-meta">
                                            <span>{{ $service->fee_label }}</span>
                                            <span>{{ $service->estimate_label }}</span>
                                        </div>
                                    </div>
                                    <span class="search-result-arrow"><x-icon name="arrow-right" :size="18" /></span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endif
        </div>
    </section>
@endsection
