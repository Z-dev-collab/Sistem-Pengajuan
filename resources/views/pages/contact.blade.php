@extends('layouts.app')

@section('title', 'Kontak & Lokasi')
@section('meta_description', 'Alamat, jam pelayanan, nomor telepon, WhatsApp, dan peta lokasi Kelurahan Batu Selicin.')

@section('content')
    <section class="page-hero">
        <div class="container">
            <nav class="breadcrumbs" aria-label="Breadcrumb">
                <a href="{{ route('home') }}">Beranda</a>
                <x-icon name="chevron-right" :size="14" />
                <span>Kontak</span>
            </nav>
            <p class="eyebrow"><span class="eyebrow-dot"></span> Hubungi kami</p>
            <h1>Kami siap membantu Anda.</h1>
            <p class="page-lead">Datang langsung, telepon, atau kirim pesan WhatsApp. Petugas pelayanan akan membantu pada hari dan jam kerja.</p>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <div class="contact-cards">
                <a href="https://wa.me/{{ config('kelurahan.whatsapp') }}?text={{ urlencode(config('kelurahan.whatsapp_message')) }}" target="_blank" rel="noopener" class="contact-card contact-card-featured">
                    <span class="contact-card-icon"><x-icon name="whatsapp" :size="26" /></span>
                    <div>
                        <span>WhatsApp</span>
                        <h2>{{ config('kelurahan.phone') }}</h2>
                        <p>Respon pada jam layanan</p>
                    </div>
                    <x-icon name="arrow-up-right" :size="20" class="contact-card-arrow" />
                </a>
                <a href="tel:{{ preg_replace('/[^0-9+]/', '', config('kelurahan.phone')) }}" class="contact-card">
                    <span class="contact-card-icon"><x-icon name="phone" :size="24" /></span>
                    <div>
                        <span>Telepon</span>
                        <h2>{{ config('kelurahan.phone') }}</h2>
                        <p>Senin–Jumat, jam kerja</p>
                    </div>
                </a>
                <a href="mailto:{{ config('kelurahan.email') }}" class="contact-card">
                    <span class="contact-card-icon"><x-icon name="mail" :size="24" /></span>
                    <div>
                        <span>Email</span>
                        <h2>{{ config('kelurahan.email') }}</h2>
                        <p>Untuk surat menyurat resmi</p>
                    </div>
                </a>
            </div>

            <div class="contact-detail-grid">
                <div class="contact-detail-panel">
                    <h2>Kantor Kelurahan Batu Selicin</h2>
                    <div class="contact-detail-list">
                        <div class="contact-line">
                            <span class="contact-line-icon"><x-icon name="map-pin" :size="20" /></span>
                            <div>
                                <strong>Alamat</strong>
                                <p>{{ config('kelurahan.address') }}</p>
                            </div>
                        </div>
                        <div class="contact-line">
                            <span class="contact-line-icon"><x-icon name="clock" :size="20" /></span>
                            <div>
                                <strong>Jam pelayanan</strong>
                                <p>{{ config('kelurahan.hours') }}<br>{{ config('kelurahan.hours_note') }}<br>Sabtu, Minggu, dan hari libur nasional tutup.</p>
                            </div>
                        </div>
                    </div>
                    <div class="contact-actions">
                        <a href="{{ config('kelurahan.map_link') }}" target="_blank" rel="noopener" class="btn btn-outline">
                            Buka di Google Maps
                            <x-icon name="arrow-up-right" :size="17" />
                        </a>
                    </div>
                </div>

                <div class="map-card map-card-large">
                    <iframe
                        src="{{ config('kelurahan.map_embed') }}"
                        title="Peta lokasi {{ config('kelurahan.name') }}"
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                        allowfullscreen
                    ></iframe>
                    <div class="map-card-caption">
                        <x-icon name="map-pin" :size="18" />
                        <span>{{ config('kelurahan.address') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
