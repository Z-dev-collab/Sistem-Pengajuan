@extends('layouts.admin')

@section('title', 'Notifikasi')

@section('content')
    <div class="admin-page-head">
        <div>
            <p class="admin-eyebrow">Komunikasi warga</p>
            <h1>Notifikasi</h1>
            <p>Pantau pesan status pengajuan dan kirim ulang melalui WhatsApp atau SMS.</p>
        </div>
    </div>

    @php
        $waConfigured = config('services.whatsapp.token') && config('services.whatsapp.phone_number_id');
        $smsConfigured = config('services.sms.endpoint') && config('services.sms.token');
    @endphp

    @if(! $waConfigured && ! $smsConfigured)
        <div class="admin-alert admin-alert-amber">
            <x-icon name="info" :size="22" />
            <div>
                <strong>Gateway notifikasi belum dikonfigurasi</strong>
                <p>Pesan tetap tersimpan di outbox. Gunakan tombol WhatsApp untuk mengirim manual, atau isi kredensial gateway di file <code>.env</code> agar pengiriman otomatis.</p>
            </div>
        </div>
    @endif

    <div class="admin-filter-card">
        <form method="GET" action="{{ route('admin.notifications.index') }}" class="admin-filter-form">
            <div class="filter-search">
                <x-icon name="search" :size="18" />
                <label for="q" class="sr-only">Cari notifikasi</label>
                <input type="search" id="q" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Cari nomor tujuan atau isi pesan…">
            </div>
            <div class="filter-select">
                <label for="channel" class="sr-only">Saluran</label>
                <select id="channel" name="channel">
                    <option value="">Semua saluran</option>
                    <option value="whatsapp" @selected(($filters['channel'] ?? '') === 'whatsapp')>WhatsApp</option>
                    <option value="sms" @selected(($filters['channel'] ?? '') === 'sms')>SMS</option>
                </select>
            </div>
            <div class="filter-select">
                <label for="status" class="sr-only">Status</label>
                <select id="status" name="status">
                    <option value="">Semua status</option>
                    <option value="pending" @selected(($filters['status'] ?? '') === 'pending')>Menunggu</option>
                    <option value="sent" @selected(($filters['status'] ?? '') === 'sent')>Terkirim</option>
                    <option value="failed" @selected(($filters['status'] ?? '') === 'failed')>Gagal</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary btn-sm">Terapkan</button>
            @if(array_filter($filters))
                <a href="{{ route('admin.notifications.index') }}" class="filter-reset">Reset</a>
            @endif
        </form>
    </div>

    <section class="admin-card">
        @if($notifications->isEmpty())
            <div class="admin-empty">
                <x-icon name="bell" :size="32" />
                <h2>Belum ada notifikasi</h2>
                <p>Notifikasi akan muncul ketika ada pengajuan baru atau perubahan status.</p>
            </div>
        @else
            <ul class="notification-list">
                @foreach($notifications as $notification)
                    <li class="notification-item">
                        <span class="notif-channel {{ $notification->channel === 'sms' ? 'sms' : 'wa' }}"><x-icon :name="$notification->channel === 'sms' ? 'message-circle' : 'whatsapp'" :size="20" /></span>
                        <div class="notification-content">
                            <div class="notification-head">
                                <div>
                                    <strong>{{ $notification->channel_label }} ke {{ $notification->recipient }}</strong>
                                    <span class="notif-status notif-{{ $notification->status }}">{{ $notification->status_label }}</span>
                                </div>
                                <time>{{ $notification->created_at->translatedFormat('d M Y, H.i') }}</time>
                            </div>
                            <p>{{ $notification->message }}</p>
                            <div class="notification-meta">
                                @if($notification->submission)
                                    <a href="{{ route('admin.submissions.show', $notification->submission) }}">
                                        <x-icon name="file-text" :size="14" />
                                        {{ $notification->submission->tracking_code }} · {{ $notification->submission->applicant_name }}
                                    </a>
                                @else
                                    <span>—</span>
                                @endif
                                @if($notification->error)
                                    <span class="notif-error" title="{{ $notification->error }}"><x-icon name="alert-circle" :size="14" /> {{ \Illuminate\Support\Str::limit($notification->error, 70) }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="notification-actions">
                            @if($notification->whatsapp_url)
                                <a href="{{ $notification->whatsapp_url }}" target="_blank" rel="noopener" class="btn btn-outline btn-xs">
                                    <x-icon name="whatsapp" :size="14" />
                                    Kirim Manual
                                </a>
                            @endif
                            @if($notification->status !== 'sent')
                                <form action="{{ route('admin.notifications.resend', $notification) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-ghost btn-xs"><x-icon name="send" :size="14" /> Coba Gateway</button>
                                </form>
                                <form action="{{ route('admin.notifications.mark-sent', $notification) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-ghost btn-xs">Tandai Terkirim</button>
                                </form>
                            @endif
                        </div>
                    </li>
                @endforeach
            </ul>

            {{ $notifications->links('partials.pagination') }}
        @endif
    </section>
@endsection
