@extends('layouts.app')

@section('title', 'Beranda — Layanan Administrasi Kelurahan')

@section('content')
    <section class="hero">
        <div class="hero-grid-bg" aria-hidden="true"></div>
        <div class="container hero-inner">
            <div class="hero-copy">
                <p class="eyebrow">
                    <span class="eyebrow-dot"></span>
                    Pelayanan warga, kini lebih dekat
                </p>
                <h1>Urus dokumen penting, <em>tanpa bolak-balik.</em></h1>
                <p class="hero-lead">
                    Ajukan surat nikah, ahli waris, atau keterangan kematian secara online. Lengkapi berkas dari rumah,
                    lalu pantau prosesnya kapan saja.
                </p>

                <form action="{{ route('search') }}" method="GET" class="hero-search" role="search">
                    <x-icon name="search" :size="21" class="hero-search-icon" />
                    <label for="hero-search" class="sr-only">Cari layanan atau pertanyaan</label>
                    <input
                        type="search"
                        id="hero-search"
                        name="q"
                        list="service-suggestions"
                        placeholder="Cari layanan, misalnya “surat kematian”…"
                        value="{{ request('q') }}"
                        autocomplete="off"
                    >
                    <datalist id="service-suggestions">
                        @foreach($services as $service)
                            <option value="{{ $service['name'] }}"></option>
                        @endforeach
                    </datalist>
                    <button type="submit" class="btn btn-primary">
                        Cari
                        <x-icon name="arrow-right" :size="17" />
                    </button>
                </form>

                <div class="hero-suggestions">
                    <span>Populer:</span>
                    <a href="{{ route('services.show', 'pengurusan-kematian') }}">Surat kematian</a>
                    <a href="{{ route('services.show', 'pengurusan-ahli-waris') }}">Ahli waris</a>
                    <a href="{{ route('services.show', 'pengurusan-nikah') }}">Surat nikah</a>
                </div>

                <ul class="hero-trust">
                    <li><x-icon name="check-circle" :size="18" /> 100% gratis</li>
                    <li><x-icon name="shield-check" :size="18" /> Data aman</li>
                    <li><x-icon name="whatsapp" :size="18" /> Notifikasi WA/SMS</li>
                </ul>
            </div>

            <div class="hero-art" aria-hidden="true">
                <img src="{{ asset('images/hero-art.png') }}" alt="Foto Kantor Walikota Batam" class="hero-art-img">
            </div>
        </div>
    </section>

    <section class="name-typewriter">
        <p class="sr-only">{{ config('kelurahan.name') }}</p>
        <p class="name-typewriter-line" aria-hidden="true" data-typewriter>
            <span class="tw-text">{{ config('kelurahan.name') }}</span><span class="tw-caret"></span>
        </p>
    </section>

    <section class="section" id="layanan">
        <div class="container">
            <div class="section-heading split">
                <div>
                    <p class="eyebrow"><span class="eyebrow-dot"></span> Layanan populer</p>
                    <h2>Pilih layanan yang Anda butuhkan</h2>
                    <p>Lihat syarat, biaya, dan estimasi waktu sebelum mengajukan. Semua bisa dimulai dari rumah.</p>
                </div>
                <a href="{{ route('services.index') }}" class="text-link">
                    Lihat semua layanan
                    <x-icon name="arrow-right" :size="17" />
                </a>
            </div>

            <div class="service-grid">
                @foreach($services as $service)
                    <article class="service-card">
                        <div class="service-card-top">
                            <span class="service-icon service-icon-{{ $loop->index + 1 }}">
                                <x-icon :name="$service['icon']" :size="30" />
                            </span>
                            <span class="service-number">0{{ $loop->iteration }}</span>
                        </div>
                        <h3><a href="{{ route('services.show', $service['slug']) }}">{{ $service['name'] }}</a></h3>
                        <p>{{ $service['tagline'] }}</p>
                        <div class="service-card-actions">
                            <a href="{{ route('services.show', $service['slug']) . '#persyaratan' }}" class="card-link card-link-strong">Lihat persyaratan</a>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    </section>

    <section class="section section-process">
        <div class="container">
            <div class="section-heading centered">
                <p class="eyebrow"><span class="eyebrow-dot"></span> Alur layanan</p>
                <h2>Empat langkah, selesai.</h2>
                <p>Proses yang jelas dari awal sampai dokumen Anda siap diambil.</p>
            </div>

            <ol class="process-grid">
                <li class="process-step">
                    <span class="process-number">01</span>
                    <span class="process-icon"><x-icon name="search" :size="24" /></span>
                    <h3>Pilih layanan</h3>
                    <p>Temukan layanan yang sesuai dan pelajari persyaratannya.</p>
                </li>
                <li class="process-step">
                    <span class="process-number">02</span>
                    <span class="process-icon"><x-icon name="upload" :size="24" /></span>
                    <h3>Unggah berkas</h3>
                    <p>Isi formulir dan unggah KTP, KK, serta surat pengantar RT/RW.</p>
                </li>
                <li class="process-step">
                    <span class="process-number">03</span>
                    <span class="process-icon"><x-icon name="shield-check" :size="24" /></span>
                    <h3>Petugas verifikasi</h3>
                    <p>Kami memeriksa kelengkapan berkas dan mengirim kabar lewat WhatsApp.</p>
                </li>
                <li class="process-step">
                    <span class="process-number">04</span>
                    <span class="process-icon"><x-icon name="file-check" :size="24" /></span>
                    <h3>Ambil dokumen</h3>
                    <p>Datang sesuai jadwal dengan membawa berkas asli dan bukti pengajuan.</p>
                </li>
            </ol>
        </div>
    </section>

    <section class="section section-contact">
        <div class="container contact-home-grid">
            <div class="contact-home-copy">
                <p class="eyebrow"><span class="eyebrow-dot"></span> Kunjungi kami</p>
                <h2>Kantor kelurahan, dekat dengan warga.</h2>
                <p>Silakan datang untuk verifikasi berkas asli atau pengambilan dokumen. Buat janji terlebih dahulu agar pelayanan lebih cepat.</p>

                <div class="contact-lines">
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
                            <p>{{ config('kelurahan.hours') }} · {{ config('kelurahan.hours_note') }}</p>
                        </div>
                    </div>
                    <div class="contact-line">
                        <span class="contact-line-icon"><x-icon name="phone" :size="20" /></span>
                        <div>
                            <strong>Telepon / WhatsApp</strong>
                            <p>{{ config('kelurahan.phone') }}</p>
                        </div>
                    </div>
                </div>

                <div class="contact-actions">
                    <a href="https://wa.me/{{ config('kelurahan.whatsapp') }}?text={{ urlencode(config('kelurahan.whatsapp_message')) }}" target="_blank" rel="noopener" class="btn btn-primary">
                        <x-icon name="whatsapp" :size="18" />
                        Chat WhatsApp
                    </a>
                    <a href="{{ config('kelurahan.map_link') }}" target="_blank" rel="noopener" class="btn btn-outline">
                        Buka di Maps
                        <x-icon name="arrow-up-right" :size="17" />
                    </a>
                </div>
            </div>

            <div class="map-card">
                <iframe
                    src="{{ config('kelurahan.map_embed') }}"
                    title="Peta lokasi {{ config('kelurahan.name') }}"
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"
                    allowfullscreen
                ></iframe>
                <div class="map-card-caption">
                    <x-icon name="map-pin" :size="18" />
                    <span>{{ config('kelurahan.name') }}, {{ config('kelurahan.kecamatan') }}</span>
                </div>
            </div>
        </div>
    </section>

@endsection
