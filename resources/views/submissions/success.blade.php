@extends('layouts.app')

@section('title', 'Pengajuan Berhasil — ' . config('kelurahan.name'))

@section('content')
    <section class="success-section">
        <div class="container success-container">
            <div class="success-icon-wrap">
                <span class="success-icon"><x-icon name="check" :size="36" /></span>
                <span class="success-ring"></span>
            </div>
            <p class="eyebrow"><span class="eyebrow-dot"></span> Pengajuan terkirim</p>
            <h1>Terima kasih, {{ explode(' ', $submission->applicant_name)[0] }}.</h1>
            <p class="success-lead">Pengajuan <strong>{{ $submission->service->name }}</strong> Anda sudah kami terima dan akan segera diperiksa petugas.</p>

            <div class="tracking-code-card">
                <p>Nomor pengajuan Anda</p>
                <div class="tracking-code-row">
                    <strong data-copy-target>{{ $submission->tracking_code }}</strong>
                    <button type="button" class="copy-button" data-copy="{{ $submission->tracking_code }}" title="Salin nomor pengajuan">
                        <x-icon name="copy" :size="18" />
                        <span>Salin</span>
                    </button>
                </div>
                <p class="tracking-code-note"><x-icon name="info" :size="15" /> Simpan nomor ini dan tunjukkan saat mengambil dokumen.</p>
            </div>

            <div class="success-summary">
                <div>
                    <span>Layanan</span>
                    <strong>{{ $submission->service->name }}</strong>
                </div>
                <div>
                    <span>Tanggal diajukan</span>
                    <strong>{{ $submission->created_at->translatedFormat('d F Y, H.i') }} WIB</strong>
                </div>
                <div>
                    <span>Status saat ini</span>
                    <span class="badge {{ $submission->status->badgeClass() }}">{{ $submission->status->label() }}</span>
                </div>
                <div>
                    <span>Notifikasi</span>
                    <strong>{{ $submission->notification_channel === 'sms' ? 'SMS' : 'WhatsApp' }} ke {{ $submission->masked_phone }}</strong>
                </div>
            </div>

            <div class="next-steps-card">
                <h2>Apa yang terjadi selanjutnya?</h2>
                <ol class="mini-steps">
                    <li>
                        <span>1</span>
                        <div><strong>Petugas memeriksa berkas</strong><p>Kami memastikan seluruh dokumen lengkap dan terbaca.</p></div>
                    </li>
                    <li>
                        <span>2</span>
                        <div><strong>Anda menerima kabar</strong><p>Notifikasi dikirim jika berkas perlu diperbaiki atau sudah selesai.</p></div>
                    </li>
                    <li>
                        <span>3</span>
                        <div><strong>Ambil dokumen</strong><p>Datang ke kelurahan dengan membawa berkas asli dan nomor pengajuan.</p></div>
                    </li>
                </ol>
            </div>

            <div class="success-actions">
                <a href="{{ route('home') }}" class="btn btn-outline">Kembali ke Beranda</a>
            </div>

            <p class="success-help">Ada pertanyaan? <a href="https://wa.me/{{ config('kelurahan.whatsapp') }}?text={{ urlencode('Halo, saya ingin menanyakan pengajuan dengan nomor '.$submission->tracking_code.'.') }}" target="_blank" rel="noopener">Hubungi petugas melalui WhatsApp</a>.</p>
        </div>
    </section>
@endsection
