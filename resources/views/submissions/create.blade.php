@extends('layouts.app')

@section('title', 'Ajukan Layanan Online')

@section('content')
    <section class="page-hero form-hero">
        <div class="container">
            <nav class="breadcrumbs" aria-label="Breadcrumb">
                <a href="{{ route('home') }}">Beranda</a>
                <x-icon name="chevron-right" :size="14" />
                <a href="{{ route('services.index') }}">Layanan</a>
                <x-icon name="chevron-right" :size="14" />
                <span>Pengajuan Online</span>
            </nav>
            <div class="page-hero-grid">
                <div>
                    <p class="eyebrow"><span class="eyebrow-dot"></span> Formulir pengajuan</p>
                    <h1>Ajukan layanan dari rumah.</h1>
                    <p class="page-lead">Isi data dengan benar dan unggah berkas yang diminta. Anda akan menerima nomor pengajuan setelah formulir terkirim.</p>
                </div>
                <ol class="form-steps">
                    <li class="active"><span>1</span> Data &amp; berkas</li>
                    <li><span>2</span> Verifikasi petugas</li>
                    <li><span>3</span> Selesai</li>
                </ol>
            </div>
        </div>
    </section>

    <section class="section form-section">
        <div class="container form-layout">
            <form action="{{ route('submissions.store') }}" method="POST" enctype="multipart/form-data" class="submission-form" id="submission-form" novalidate>
                @csrf

                <div class="honeypot" aria-hidden="true">
                    <label for="website">Website</label>
                    <input type="text" id="website" name="website" tabindex="-1" autocomplete="off">
                </div>

                @if($errors->any())
                    <div class="form-error-summary" role="alert">
                        <x-icon name="alert" :size="22" />
                        <div>
                            <strong>Ada {{ $errors->count() }} hal yang perlu diperiksa.</strong>
                            <p>Silakan perbaiki isian yang ditandai merah di bawah ini.</p>
                        </div>
                    </div>
                @endif

                <div class="form-card">
                    <div class="form-card-heading">
                        <span class="form-step-number">01</span>
                        <div>
                            <h2>Pilih layanan</h2>
                            <p>Tentukan layanan yang ingin Anda ajukan.</p>
                        </div>
                    </div>

                    @php
                        $selectedId = old('service_id', $selectedService?->id);
                    @endphp
                    <div class="field">
                        <label for="service_id">Jenis layanan <span class="required-star">*</span></label>
                        <select name="service_id" id="service_id" required data-service-select class="{{ $errors->has('service_id') ? 'invalid' : '' }}">
                            <option value="">— Pilih layanan —</option>
                            @foreach($services as $service)
                                <option
                                    value="{{ $service->id }}"
                                    data-name="{{ $service->name }}"
                                    data-fee="{{ $service->fee_label }}"
                                    data-estimate="{{ $service->estimate_label }}"
                                    data-requirements="{{ $service->requirements()->count() }}"
                                    @selected((string) $selectedId === (string) $service->id)
                                >{{ $service->name }}</option>
                            @endforeach
                        </select>
                        @error('service_id') <p class="field-error">{{ $message }}</p> @enderror
                        <p class="field-hint">Belum yakin? <a href="{{ route('services.index') }}" class="inline-link">Bandingkan layanan dan persyaratannya</a>.</p>
                    </div>
                </div>

                <div class="form-card">
                    <div class="form-card-heading">
                        <span class="form-step-number">02</span>
                        <div>
                            <h2>Data pemohon</h2>
                            <p>Isi sesuai dokumen resmi. Data digunakan hanya untuk keperluan pelayanan.</p>
                        </div>
                    </div>

                    <div class="form-grid">
                        <div class="field field-full">
                            <label for="applicant_name">Nama lengkap <span class="required-star">*</span></label>
                            <input type="text" id="applicant_name" name="applicant_name" value="{{ old('applicant_name') }}" placeholder="Sesuai KTP" autocomplete="name" required class="{{ $errors->has('applicant_name') ? 'invalid' : '' }}">
                            @error('applicant_name') <p class="field-error">{{ $message }}</p> @enderror
                        </div>

                        <div class="field">
                            <label for="nik">NIK <span class="required-star">*</span></label>
                            <input type="text" id="nik" name="nik" value="{{ old('nik') }}" placeholder="16 digit NIK" inputmode="numeric" maxlength="16" autocomplete="off" required class="{{ $errors->has('nik') ? 'invalid' : '' }}">
                            @error('nik') <p class="field-error">{{ $message }}</p> @enderror
                        </div>

                        <div class="field">
                            <label for="kk_number">Nomor Kartu Keluarga <span class="required-star">*</span></label>
                            <input type="text" id="kk_number" name="kk_number" value="{{ old('kk_number') }}" placeholder="16 digit nomor KK" inputmode="numeric" maxlength="16" required class="{{ $errors->has('kk_number') ? 'invalid' : '' }}">
                            @error('kk_number') <p class="field-error">{{ $message }}</p> @enderror
                        </div>

                        <div class="field">
                            <label for="phone">Nomor WhatsApp / HP <span class="required-star">*</span></label>
                            <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" placeholder="Contoh: 081234567890" inputmode="tel" autocomplete="tel" required class="{{ $errors->has('phone') ? 'invalid' : '' }}">
                            @error('phone') <p class="field-error">{{ $message }}</p> @enderror
                            <p class="field-hint">Nomor ini akan menerima notifikasi status pengajuan.</p>
                        </div>

                        <div class="field">
                            <label for="email">Email <span class="tag-optional">Opsional</span></label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="nama@email.com" autocomplete="email" class="{{ $errors->has('email') ? 'invalid' : '' }}">
                            @error('email') <p class="field-error">{{ $message }}</p> @enderror
                        </div>

                        <div class="field field-full">
                            <label for="address">Alamat lengkap <span class="required-star">*</span></label>
                            <textarea id="address" name="address" rows="3" placeholder="Nama jalan, nomor rumah, kelurahan, kecamatan" autocomplete="street-address" required class="{{ $errors->has('address') ? 'invalid' : '' }}">{{ old('address') }}</textarea>
                            @error('address') <p class="field-error">{{ $message }}</p> @enderror
                        </div>

                        <div class="field">
                            <label for="rt">RT <span class="required-star">*</span></label>
                            <input type="text" id="rt" name="rt" value="{{ old('rt') }}" placeholder="001" inputmode="numeric" maxlength="3" required class="{{ $errors->has('rt') ? 'invalid' : '' }}">
                            @error('rt') <p class="field-error">{{ $message }}</p> @enderror
                        </div>

                        <div class="field">
                            <label for="rw">RW <span class="required-star">*</span></label>
                            <input type="text" id="rw" name="rw" value="{{ old('rw') }}" placeholder="002" inputmode="numeric" maxlength="3" required class="{{ $errors->has('rw') ? 'invalid' : '' }}">
                            @error('rw') <p class="field-error">{{ $message }}</p> @enderror
                        </div>

                        <div class="field field-full">
                            <label for="notes">Keterangan tambahan <span class="tag-optional">Opsional</span></label>
                            <textarea id="notes" name="notes" rows="3" placeholder="Contoh: dokumen dibutuhkan untuk pengurusan asuransi" class="{{ $errors->has('notes') ? 'invalid' : '' }}">{{ old('notes') }}</textarea>
                            @error('notes') <p class="field-error">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <div class="form-card">
                    <div class="form-card-heading">
                        <span class="form-step-number">03</span>
                        <div>
                            <h2>Unggah berkas</h2>
                            <p>Tiga berkas utama wajib dilampirkan. Foto dengan pencahayaan cukup agar mudah dibaca.</p>
                        </div>
                    </div>

                    <div class="upload-stack">
                        @include('partials.file-upload', [
                            'field' => 'ktp',
                            'label' => 'KTP',
                            'hint' => 'Foto atau scan KTP pemohon, pastikan sisi depan terlihat jelas.',
                            'required' => true,
                        ])
                        @include('partials.file-upload', [
                            'field' => 'kk',
                            'label' => 'Kartu Keluarga',
                            'hint' => 'Foto atau scan Kartu Keluarga yang memuat nama pemohon.',
                            'required' => true,
                        ])
                        @include('partials.file-upload', [
                            'field' => 'pengantar',
                            'label' => 'Surat Pengantar RT/RW',
                            'hint' => 'Surat pengantar asli yang sudah ditandatangani RT dan RW.',
                            'required' => true,
                        ])
                        @include('partials.file-upload', [
                            'field' => 'pendukung',
                            'label' => 'Dokumen pendukung lain',
                            'hint' => 'Contoh: akta kelahiran, akta nikah, atau surat keterangan kematian.',
                            'required' => false,
                            'multiple' => true,
                        ])
                    </div>
                </div>

                <div class="form-card">
                    <div class="form-card-heading">
                        <span class="form-step-number">04</span>
                        <div>
                            <h2>Notifikasi &amp; pernyataan</h2>
                            <p>Pilih cara kami mengabari perkembangan pengajuan Anda.</p>
                        </div>
                    </div>

                    <fieldset class="field">
                        <legend>Saluran notifikasi <span class="required-star">*</span></legend>
                        <div class="radio-cards">
                            <label class="radio-card">
                                <input type="radio" name="notification_channel" value="whatsapp" @checked(old('notification_channel', 'whatsapp') === 'whatsapp')>
                                <span class="radio-card-mark"><x-icon name="whatsapp" :size="22" /></span>
                                <span>
                                    <strong>WhatsApp</strong>
                                    <small>Paling cepat dan lengkap</small>
                                </span>
                                <span class="radio-check"><x-icon name="check" :size="13" /></span>
                            </label>
                            <label class="radio-card">
                                <input type="radio" name="notification_channel" value="sms" @checked(old('notification_channel') === 'sms')>
                                <span class="radio-card-mark"><x-icon name="message-circle" :size="22" /></span>
                                <span>
                                    <strong>SMS</strong>
                                    <small>Untuk nomor tanpa WhatsApp</small>
                                </span>
                                <span class="radio-check"><x-icon name="check" :size="13" /></span>
                            </label>
                        </div>
                        @error('notification_channel') <p class="field-error">{{ $message }}</p> @enderror
                    </fieldset>

                    <label class="consent-row {{ $errors->has('consent') ? 'has-error' : '' }}">
                        <input type="checkbox" name="consent" value="1" required @checked(old('consent'))>
                        <span class="checkbox-mark"><x-icon name="check" :size="13" /></span>
                        <span>Saya menyatakan bahwa seluruh data dan berkas yang saya berikan benar, dan saya menyetujui data tersebut digunakan untuk keperluan pelayanan kelurahan.</span>
                    </label>
                    @error('consent') <p class="field-error">{{ $message }}</p> @enderror

                    <div class="form-submit-row">
                        <div class="secure-note">
                            <x-icon name="lock" :size="16" />
                            <span>Data Anda disimpan dengan aman dan hanya dapat diakses petugas berwenang.</span>
                        </div>
                        <button type="submit" class="btn btn-primary btn-lg" data-submit-button>
                            Kirim Pengajuan
                            <x-icon name="arrow-right" :size="18" />
                        </button>
                    </div>
                </div>
            </form>

            <aside class="form-aside">
                <div class="sticky-card">
                    <div class="form-summary-card">
                        <p class="summary-label">Ringkasan pengajuan</p>
                        <h3 data-summary-name>Belum memilih layanan</h3>
                        <dl class="summary-list summary-list-compact">
                            <div>
                                <dt>Biaya</dt>
                                <dd data-summary-fee>—</dd>
                            </div>
                            <div>
                                <dt>Estimasi</dt>
                                <dd data-summary-estimate>—</dd>
                            </div>
                            <div>
                                <dt>Persyaratan</dt>
                                <dd data-summary-requirements>—</dd>
                            </div>
                        </dl>
                        <div class="summary-divider"></div>
                        <p class="summary-help-text">Pastikan berkas terbaca jelas. Berkas yang buram akan memperlambat verifikasi.</p>
                    </div>

                    <div class="sidebar-contact sidebar-contact-compact">
                        <h3>Butuh bantuan mengisi?</h3>
                        <p>Hubungi petugas pada jam layanan.</p>
                        <a href="https://wa.me/{{ config('kelurahan.whatsapp') }}?text={{ urlencode('Halo, saya butuh bantuan mengisi formulir pengajuan online.') }}" target="_blank" rel="noopener" class="sidebar-contact-link">
                            <x-icon name="whatsapp" :size="18" />
                            <span>Chat WhatsApp<small>{{ config('kelurahan.phone') }}</small></span>
                        </a>
                        <a href="{{ route('contact') }}" class="sidebar-contact-link">
                            <x-icon name="map-pin" :size="18" />
                            <span>Kantor kelurahan<small>{{ config('kelurahan.hours') }}</small></span>
                        </a>
                    </div>
                </div>
            </aside>
        </div>
    </section>
@endsection
