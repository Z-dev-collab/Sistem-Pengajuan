@extends('layouts.admin')

@section('title', 'Pengajuan')

@section('content')
    <div class="admin-page-head">
        <div>
            <p class="admin-eyebrow">Manajemen berkas</p>
            <h1>Pengajuan warga</h1>
            <p>Periksa berkas, berikan catatan perbaikan, dan perbarui status pengajuan.</p>
        </div>
        <div class="admin-page-actions">
            <a href="{{ route('admin.notifications.index') }}" class="btn btn-outline">
                <x-icon name="bell" :size="17" />
                Notifikasi
            </a>
        </div>
    </div>

    <div class="admin-filter-card">
        <form method="GET" action="{{ route('admin.submissions.index') }}" class="admin-filter-form">
            <div class="filter-search">
                <x-icon name="search" :size="18" />
                <label for="q" class="sr-only">Cari pengajuan</label>
                <input type="search" id="q" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Cari nomor, nama, NIK, atau HP…">
            </div>
            <div class="filter-select">
                <label for="service" class="sr-only">Layanan</label>
                <select id="service" name="service">
                    <option value="">Semua layanan</option>
                    @foreach($services as $service)
                        <option value="{{ $service->id }}" @selected(($filters['service'] ?? '') == $service->id)>{{ $service->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="filter-select">
                <label for="status" class="sr-only">Status</label>
                <select id="status" name="status">
                    <option value="">Semua status</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status->value }}" @selected(($filters['status'] ?? '') === $status->value)>{{ $status->label() }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary btn-sm">Terapkan</button>
            @if(array_filter($filters))
                <a href="{{ route('admin.submissions.index') }}" class="filter-reset">Reset</a>
            @endif
        </form>
    </div>

    <div class="admin-status-tabs">
        <a href="{{ route('admin.submissions.index', array_filter(['q' => $filters['q'] ?? null, 'service' => $filters['service'] ?? null])) }}" class="{{ empty($filters['status']) ? 'active' : '' }}">Semua <span>{{ \App\Models\Submission::count() }}</span></a>
        @foreach($statuses as $status)
            <a href="{{ route('admin.submissions.index', array_filter(['status' => $status->value, 'q' => $filters['q'] ?? null, 'service' => $filters['service'] ?? null])) }}" class="{{ ($filters['status'] ?? '') === $status->value ? 'active' : '' }}">
                {{ $status->label() }}
                <span>{{ \App\Models\Submission::where('status', $status->value)->count() }}</span>
            </a>
        @endforeach
    </div>

    <section class="admin-card">
        @if($submissions->isEmpty())
            <div class="admin-empty">
                <x-icon name="inbox" :size="32" />
                <h2>Tidak ada pengajuan</h2>
                <p>Belum ada data yang sesuai dengan filter yang dipilih.</p>
                @if(array_filter($filters))
                    <a href="{{ route('admin.submissions.index') }}" class="btn btn-outline btn-sm">Hapus filter</a>
                @endif
            </div>
        @else
            <div class="table-wrap">
                <table class="admin-table admin-table-lg">
                    <thead>
                        <tr>
                            <th>Nomor / Pemohon</th>
                            <th>Layanan</th>
                            <th>Kontak</th>
                            <th>Status</th>
                            <th>Tanggal masuk</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($submissions as $submission)
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
                                <td>
                                    <span class="table-service">{{ $submission->service->name }}</span>
                                    <small class="table-sub">{{ $submission->created_at->diffForHumans() }}</small>
                                </td>
                                <td>
                                    <span class="table-service">{{ $submission->phone }}</span>
                                    <small class="table-sub">{{ $submission->notification_channel === 'sms' ? 'SMS' : 'WhatsApp' }}</small>
                                </td>
                                <td><span class="badge {{ $submission->status->badgeClass() }}">{{ $submission->status->label() }}</span></td>
                                <td>{{ $submission->created_at->translatedFormat('d M Y') }}</td>
                                <td class="table-action">
                                    <a href="{{ route('admin.submissions.show', $submission) }}" class="btn btn-outline btn-xs">Periksa</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{ $submissions->links('partials.pagination') }}
        @endif
    </section>
@endsection
