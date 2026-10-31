@extends('layouts.app')

@section('title', 'Layanan Administrasi — ' . config('kelurahan.name'))

@section('content')
    <section class="page-hero">
        <div class="container">
            <nav class="breadcrumbs" aria-label="Breadcrumb">
                <a href="{{ route('home') }}">Beranda</a>
                <x-icon name="chevron-right" :size="14" />
                <span>Layanan</span>
            </nav>
            <div class="page-hero-grid">
                <div>
                    <p class="eyebrow"><span class="eyebrow-dot"></span> Layanan administrasi</p>
                    <h1>Satu tempat untuk semua urusan surat Anda.</h1>
                    <p class="page-lead">Pilih layanan, pelajari persyaratan, lalu ajukan online. Petugas kami akan memandu prosesnya sampai selesai.</p>
                </div>
                <form action="{{ route('search') }}" method="GET" class="page-search" role="search">
                    <x-icon name="search" :size="19" />
                    <label for="service-search" class="sr-only">Cari layanan</label>
                    <input type="search" id="service-search" name="q" placeholder="Cari layanan…" value="{{ request('q') }}">
                    <button type="submit" class="btn btn-primary btn-sm">Cari</button>
                </form>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <div class="service-grid service-grid-page">
                @foreach($services as $service)
                    <article class="service-card">
                        <div class="service-card-top">
                            <span class="service-icon service-icon-{{ $loop->index + 1 }}">
                                <x-icon :name="$service['icon']" :size="30" />
                            </span>
                            <span class="service-number">0{{ $loop->iteration }}</span>
                        </div>
                        <h2><a href="{{ route('services.show', $service['slug']) }}">{{ $service['name'] }}</a></h2>
                        <p>{{ $service['tagline'] }}</p>
                        <div class="service-card-actions">
                            <a href="{{ route('services.show', $service['slug']) . '#persyaratan' }}" class="card-link card-link-strong">Lihat persyaratan</a>
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="info-strip">
                <span class="info-strip-icon"><x-icon name="info" :size="22" /></span>
                <div>
                    <strong>Semua layanan tidak dipungut biaya.</strong>
                    <p>Apabila membutuhkan materai, biaya materai dibebankan kepada pemohon sesuai ketentuan yang berlaku.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="section section-tight">
        <div class="container">
            <div class="help-duo">
                <div class="help-duo-card">
                    <span class="help-duo-icon"><x-icon name="message-circle" :size="24" /></span>
                    <div>
                        <h2>Masih bingung memilih layanan?</h2>
                        <p>Hubungi petugas melalui WhatsApp. Kami akan membantu menentukan layanan dan berkas yang diperlukan.</p>
                        <a href="https://wa.me/{{ config('kelurahan.whatsapp') }}?text={{ urlencode(config('kelurahan.whatsapp_message')) }}" target="_blank" rel="noopener" class="text-link">Chat petugas <x-icon name="whatsapp" :size="16" /></a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
