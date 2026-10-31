@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <div class="admin-page-head">
        <div>
            <p class="admin-eyebrow">Ringkasan pelayanan</p>
            <h1>Selamat datang, {{ explode(' ', auth()->user()->name)[0] }}.</h1>
            <p>Berikut kondisi pengajuan dan aktivitas pelayanan hari ini.</p>
        </div>
        <div class="admin-page-actions">
            <a href="{{ route('admin.submissions.index') }}" class="btn btn-primary">
                <x-icon name="inbox" :size="17" />
                Lihat Pengajuan
            </a>
        </div>
    </div>

    @if($pendingNotifications > 0)
        <a href="{{ route('admin.notifications.index', ['status' => 'pending']) }}" class="admin-notice-banner">
            <span class="admin-notice-icon"><x-icon name="bell" :size="20" /></span>
            <div>
                <strong>{{ $pendingNotifications }} notifikasi menunggu pengiriman</strong>
                <span>Gateway otomatis belum dikonfigurasi atau pengiriman perlu dicoba ulang.</span>
            </div>
            <x-icon name="arrow-right" :size="18" />
        </a>
    @endif

    <div class="stat-grid">
        <article class="stat-card">
            <span class="stat-icon stat-icon-blue"><x-icon name="inbox" :size="22" /></span>
            <div class="stat-copy">
                <span>Perlu diperiksa</span>
                <strong>{{ $stats['new'] }}</strong>
                <small>Pengajuan baru</small>
            </div>
            <a href="{{ route('admin.submissions.index', ['status' => 'submitted']) }}" class="stat-link" aria-label="Lihat pengajuan baru"><x-icon name="arrow-up-right" :size="16" /></a>
        </article>
        <article class="stat-card">
            <span class="stat-icon stat-icon-amber"><x-icon name="shield-check" :size="22" /></span>
            <div class="stat-copy">
                <span>Sedang diproses</span>
                <strong>{{ $stats['verified'] }}</strong>
                <small>Sudah diverifikasi</small>
            </div>
            <a href="{{ route('admin.submissions.index', ['status' => 'verified']) }}" class="stat-link" aria-label="Lihat pengajuan diproses"><x-icon name="arrow-up-right" :size="16" /></a>
        </article>
        <article class="stat-card">
            <span class="stat-icon stat-icon-red"><x-icon name="alert" :size="22" /></span>
            <div class="stat-copy">
                <span>Perlu perbaikan</span>
                <strong>{{ $stats['revision'] }}</strong>
                <small>Menunggu warga</small>
            </div>
            <a href="{{ route('admin.submissions.index', ['status' => 'needs_revision']) }}" class="stat-link" aria-label="Lihat pengajuan perbaikan"><x-icon name="arrow-up-right" :size="16" /></a>
        </article>
        <article class="stat-card">
            <span class="stat-icon stat-icon-green"><x-icon name="check-circle" :size="22" /></span>
            <div class="stat-copy">
                <span>Selesai bulan ini</span>
                <strong>{{ $stats['completed'] }}</strong>
                <small>Dokumen siap diambil</small>
            </div>
            <a href="{{ route('admin.submissions.index', ['status' => 'completed']) }}" class="stat-link" aria-label="Lihat pengajuan selesai"><x-icon name="arrow-up-right" :size="16" /></a>
        </article>
    </div>

    <div class="admin-dashboard-grid">
        <section class="admin-card">
            <div class="admin-card-head">
                <div>
                    <h2>Pengajuan terbaru</h2>
                    <p>6 pengajuan yang terakhir masuk.</p>
                </div>
                <a href="{{ route('admin.submissions.index') }}" class="text-link">Lihat semua <x-icon name="arrow-right" :size="15" /></a>
            </div>

            @if($recentSubmissions->isEmpty())
                <div class="admin-empty">
                    <x-icon name="inbox" :size="28" />
                    <p>Belum ada pengajuan masuk.</p>
                </div>
            @else
                <div class="table-wrap">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Pemohon</th>
                                <th>Layanan</th>
                                <th>Status</th>
                                <th>Masuk</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentSubmissions as $submission)
                                <tr>
                                    <td>
                                        <div class="table-person">
                                            <span class="table-avatar">{{ strtoupper(substr($submission->applicant_name, 0, 1)) }}</span>
                                            <div>
                                                <strong>{{ $submission->applicant_name }}</strong>
                                                <small>{{ $submission->tracking_code }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $submission->service->name }}</td>
                                    <td><span class="badge {{ $submission->status->badgeClass() }}">{{ $submission->status->label() }}</span></td>
                                    <td>{{ $submission->created_at->diffForHumans() }}</td>
                                    <td class="table-action"><a href="{{ route('admin.submissions.show', $submission) }}" class="icon-button" aria-label="Detail {{ $submission->tracking_code }}"><x-icon name="arrow-right" :size="17" /></a></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </section>

        <div class="admin-dashboard-side">
            <section class="admin-card">
                <div class="admin-card-head">
                    <div>
                        <h2>Distribusi layanan</h2>
                        <p>Jumlah pengajuan per layanan.</p>
                    </div>
                </div>
                <div class="service-bars">
                    @php $maxService = max(1, $serviceStats->max('total_submissions')); @endphp
                    @foreach($serviceStats as $service)
                        <div class="service-bar-row">
                            <div class="service-bar-label">
                                <span>{{ $service->name }}</span>
                                <strong>{{ $service->total_submissions }}</strong>
                            </div>
                            <div class="service-bar-track">
                                <span style="width: {{ max(4, ($service->total_submissions / $maxService) * 100) }}%"></span>
                            </div>
                            <small>{{ $service->open_submissions }} sedang diproses</small>
                        </div>
                    @endforeach
                </div>
            </section>
        </div>
    </div>
@endsection
