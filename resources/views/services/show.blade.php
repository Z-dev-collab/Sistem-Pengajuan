@extends('layouts.app')

@section('title', $service['name'] . ' — ' . config('kelurahan.name'))
@section('meta_description', $service['tagline'] . ' Lihat syarat, biaya, estimasi waktu, dan unduh formulir.')

@section('content')
    <section class="page-hero service-hero">
        <div class="container">
            <nav class="breadcrumbs" aria-label="Breadcrumb">
                <a href="{{ route('home') }}">Beranda</a>
                <x-icon name="chevron-right" :size="14" />
                <a href="{{ route('services.index') }}">Layanan</a>
                <x-icon name="chevron-right" :size="14" />
                <span>{{ $service['name'] }}</span>
            </nav>

            <div class="service-hero-grid">
                <div class="service-hero-copy">
                    <span class="service-icon service-icon-lg service-icon-{{ (($service['sort_order'] ?? 1) % 4) ?: 4 }}">
                        <x-icon :name="$service['icon']" :size="38" />
                    </span>
                    <p class="eyebrow"><span class="eyebrow-dot"></span> Layanan kelurahan</p>
                    <h1>{{ $service['name'] }}</h1>
                    <p class="page-lead">{{ $service['tagline'] }}</p>
                    <div class="service-hero-meta">
                        <span><x-icon name="check-circle" :size="17" /> {{ $service['fee_label'] }}</span>
                        <span><x-icon name="clock" :size="17" /> {{ $service['estimate_label'] }}</span>
                        <span><x-icon name="file-text" :size="17" /> {{ count($service['requirements']) }} persyaratan</span>
                    </div>
                    <div class="service-hero-actions">
                        <a href="#" class="btn btn-primary">
                            Ajukan Online
                            <x-icon name="arrow-right" :size="17" />
                        </a>
                        <a href="{{ route('services.form', $service['slug']) }}" class="btn btn-outline">
                            <x-icon name="download" :size="17" />
                            Unduh Formulir
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container service-detail-grid">
            <div class="service-detail-main">
                <div class="content-card">
                    <h2>Tentang layanan ini</h2>
                    <p class="content-lead">{{ $service['description'] }}</p>
                </div>

                <div class="content-card" id="persyaratan">
                    <div class="content-card-heading">
                        <div>
                            <h2>Persyaratan berkas</h2>
                            <p>Siapkan dokumen berikut sebelum mengajukan. Tanda <span class="mandatory-dot"></span> wajib dilengkapi.</p>
                        </div>
                        <span class="count-badge">{{ count($service['requirements']) }} item</span>
                    </div>

                    <ul class="requirement-list">
                        @foreach($service['requirements'] as $requirement)
                            <li>
                                <span class="requirement-check {{ $requirement['is_mandatory'] ? '' : 'optional' }}">
                                    <x-icon :name="$requirement['is_mandatory'] ? 'check' : 'plus'" :size="15" />
                                </span>
                                <div>
                                    <strong>{{ $requirement['name'] }}</strong>
                                    @if($requirement['note'])
                                        <span>{{ $requirement['note'] }}</span>
                                    @endif
                                </div>
                                @unless($requirement['is_mandatory'])
                                    <span class="tag-optional">Opsional</span>
                                @endunless
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="content-card">
                    <h2>Yang perlu diperhatikan</h2>
                    <ul class="check-bullet-list">
                        <li>Bawa berkas asli saat verifikasi atau pengambilan dokumen di kelurahan.</li>
                        <li>Pastikan data NIK dan nomor Kartu Keluarga sesuai dengan dokumen resmi.</li>
                        <li>Gunakan nomor WhatsApp aktif agar notifikasi status dapat diterima.</li>
                        <li>Untuk pengambilan oleh orang lain, siapkan surat kuasa dan identitas penerima.</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <section class="cta-section">
        <div class="container">
            <div class="cta-panel">
                <div>
                    <p class="eyebrow eyebrow-light"><span class="eyebrow-dot"></span> Pelayanan tanpa biaya</p>
                    <h2>Lengkapi berkas, kami urus sisanya.</h2>
                    <p>Petugas mengirim kabar melalui WhatsApp atau SMS setiap ada perubahan status.</p>
                </div>
                <div class="cta-actions">
                    <a href="#" class="btn btn-cream">
                        Ajukan {{ $service['name'] }}
                        <x-icon name="arrow-right" :size="18" />
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection
