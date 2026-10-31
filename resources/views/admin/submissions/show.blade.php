@extends('layouts.admin')

@section('title', 'Pengajuan ' . $submission->tracking_code)

@section('content')
    <div class="admin-breadcrumb">
        <a href="{{ route('admin.submissions.index') }}"><x-icon name="arrow-left" :size="15" /> Semua pengajuan</a>
        <span>/</span>
        <span>{{ $submission->tracking_code }}</span>
    </div>

    <div class="admin-detail-head">
        <div>
            <div class="admin-detail-title">
                <h1>{{ $submission->service->name }}</h1>
                <span class="badge {{ $submission->status->badgeClass() }}">{{ $submission->status->label() }}</span>
            </div>
            <p>{{ $submission->tracking_code }} · Diajukan {{ $submission->created_at->translatedFormat('d F Y, H.i') }} WIB oleh {{ $submission->applicant_name }}</p>
        </div>
        <div class="admin-page-actions">
            <button type="button" class="btn btn-outline btn-sm" data-print><x-icon name="printer" :size="16" /> Cetak</button>
            <a href="{{ $submission->whatsapp_url }}" target="_blank" rel="noopener" class="btn btn-outline btn-sm"><x-icon name="whatsapp" :size="16" /> WhatsApp</a>
        </div>
    </div>

    @if($errors->any())
        <div class="admin-flash admin-flash-error" role="alert">
            <x-icon name="alert-circle" :size="19" />
            <div>
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        </div>
    @endif

    @if($submission->status === \App\Enums\SubmissionStatus::NeedsRevision)
        <div class="admin-alert admin-alert-red">
            <x-icon name="alert" :size="22" />
            <div>
                <strong>Menunggu perbaikan dari pemohon</strong>
                <p>{{ $submission->admin_notes }}</p>
            </div>
        </div>
    @elseif($submission->status === \App\Enums\SubmissionStatus::Completed)
        <div class="admin-alert admin-alert-green">
            <x-icon name="check-circle" :size="22" />
            <div>
                <strong>Pengajuan selesai</strong>
                <p>Diselesaikan pada {{ $submission->completed_at?->translatedFormat('d F Y, H.i') }} WIB oleh {{ $submission->completedBy?->name ?? 'petugas' }}.</p>
            </div>
        </div>
    @endif

    <div class="admin-detail-grid">
        <div class="admin-detail-main">
            <section class="admin-card">
                <div class="admin-card-head">
                    <div>
                        <h2>Data pemohon</h2>
                        <p>Informasi yang diisi pada formulir pengajuan.</p>
                    </div>
                </div>
                <dl class="admin-detail-list">
                    <div><dt>Nama lengkap</dt><dd>{{ $submission->applicant_name }}</dd></div>
                    <div><dt>NIK</dt><dd>{{ $submission->nik }}</dd></div>
                    <div><dt>Nomor KK</dt><dd>{{ $submission->kk_number }}</dd></div>
                    <div><dt>Nomor HP</dt><dd><a href="tel:{{ $submission->phone }}">{{ $submission->phone }}</a></dd></div>
                    <div><dt>Email</dt><dd>{{ $submission->email ?: '—' }}</dd></div>
                    <div><dt>Alamat</dt><dd>{{ $submission->address }}, RT {{ $submission->rt }} / RW {{ $submission->rw }}</dd></div>
                    <div><dt>Notifikasi</dt><dd>{{ $submission->notification_channel === 'sms' ? 'SMS' : 'WhatsApp' }}</dd></div>
                    <div><dt>Keterangan pemohon</dt><dd>{{ $submission->notes ?: '—' }}</dd></div>
                </dl>
            </section>

            <section class="admin-card">
                <div class="admin-card-head">
                    <div>
                        <h2>Berkas persyaratan</h2>
                        <p>Klik lihat untuk membuka berkas di tab baru.</p>
                    </div>
                    <span class="count-badge">{{ $submission->documents->count() }} berkas</span>
                </div>

                <ul class="admin-document-list">
                    @foreach(['ktp', 'kk', 'pengantar'] as $type)
                        @php $document = $submission->documentOf($type); @endphp
                        <li class="{{ $document ? '' : 'missing' }}">
                            <span class="document-icon {{ $document?->mime_type === 'application/pdf' ? 'pdf' : 'image' }}">
                                <x-icon :name="$document ? 'file-text' : 'alert-circle'" :size="21" />
                            </span>
                            <div>
                                <strong>{{ \App\Models\Submission::DOCUMENT_TYPES[$type] }}</strong>
                                @if($document)
                                    <span>{{ $document->original_name }} · {{ $document->size_label }}</span>
                                @else
                                    <span>Belum diunggah</span>
                                @endif
                            </div>
                            @if($document)
                                <div class="document-actions">
                                    <a href="{{ route('admin.documents.preview', [$submission, $document]) }}" target="_blank" class="btn btn-ghost btn-xs"><x-icon name="eye" :size="14" /> Lihat</a>
                                    <a href="{{ route('admin.documents.download', [$submission, $document]) }}" class="icon-button" title="Unduh"><x-icon name="download" :size="16" /></a>
                                </div>
                            @endif
                        </li>
                    @endforeach

                    @foreach($submission->documents->where('type', 'pendukung') as $document)
                        <li>
                            <span class="document-icon {{ $document->mime_type === 'application/pdf' ? 'pdf' : 'image' }}">
                                <x-icon name="file-text" :size="21" />
                            </span>
                            <div>
                                <strong>Dokumen Pendukung</strong>
                                <span>{{ $document->original_name }} · {{ $document->size_label }}</span>
                            </div>
                            <div class="document-actions">
                                <a href="{{ route('admin.documents.preview', [$submission, $document]) }}" target="_blank" class="btn btn-ghost btn-xs"><x-icon name="eye" :size="14" /> Lihat</a>
                                <a href="{{ route('admin.documents.download', [$submission, $document]) }}" class="icon-button" title="Unduh"><x-icon name="download" :size="16" /></a>
                            </div>
                        </li>
                    @endforeach
                </ul>

                @if($submission->result_file)
                    <div class="result-file-row">
                        <span class="document-icon pdf"><x-icon name="file-check" :size="20" /></span>
                        <div>
                            <strong>Dokumen hasil</strong>
                            <span>Diunggah oleh petugas</span>
                        </div>
                        <a href="{{ route('admin.submissions.result', $submission) }}" class="btn btn-outline btn-xs"><x-icon name="download" :size="14" /> Unduh</a>
                    </div>
                @endif
            </section>

            <section class="admin-card">
                <div class="admin-card-head">
                    <div>
                        <h2>Riwayat status</h2>
                        <p>Jejak pemeriksaan dan perubahan status.</p>
                    </div>
                </div>
                <ol class="admin-timeline">
                    @foreach($submission->histories as $history)
                        <li>
                            <span class="admin-timeline-dot {{ $history->status->badgeClass() }}"><x-icon :name="$history->status->icon()" :size="14" /></span>
                            <div>
                                <div class="admin-timeline-head">
                                    <strong>{{ $history->title }}</strong>
                                    <time>{{ $history->created_at->translatedFormat('d M Y, H.i') }}</time>
                                </div>
                                @if($history->note)<p>{{ $history->note }}</p>@endif
                                <small>{{ $history->user?->name ?? 'Sistem' }} · {{ $history->status->label() }}</small>
                            </div>
                        </li>
                    @endforeach
                </ol>
            </section>
        </div>

        <aside class="admin-detail-aside">
            <section class="admin-card admin-action-card">
                <div class="admin-card-head">
                    <div>
                        <h2>Tindakan petugas</h2>
                        <p>Perbarui status pengajuan.</p>
                    </div>
                </div>

                @if($submission->status === \App\Enums\SubmissionStatus::Completed)
                    <div class="action-completed">
                        <span class="success-icon success-icon-sm"><x-icon name="check" :size="22" /></span>
                        <h3>Pengajuan selesai</h3>
                        <p>Dokumen telah diselesaikan. Anda masih dapat mengunduh dokumen hasil di samping.</p>
                    </div>
                @else
                    @if($submission->status === \App\Enums\SubmissionStatus::Submitted || $submission->status === \App\Enums\SubmissionStatus::NeedsRevision)
                        <form action="{{ route('admin.submissions.verify', $submission) }}" method="POST" class="admin-action-form">
                            @csrf
                            <label class="sr-only" for="verify_note">Catatan verifikasi (opsional)</label>
                            <textarea name="note" id="verify_note" rows="2" placeholder="Catatan verifikasi (opsional)"></textarea>
                            <button type="submit" class="btn btn-primary btn-block">
                                <x-icon name="shield-check" :size="17" />
                                Verifikasi Berkas
                            </button>
                        </form>
                    @endif

                    @if($submission->status !== \App\Enums\SubmissionStatus::Completed)
                        <details class="action-disclosure action-disclosure-warning">
                            <summary><x-icon name="alert" :size="16" /> Minta Perbaikan</summary>
                            <form action="{{ route('admin.submissions.revision', $submission) }}" method="POST">
                                @csrf
                                <div class="field">
                                    <label for="revision_note_admin">Catatan untuk pemohon <span class="required-star">*</span></label>
                                    <textarea name="note" id="revision_note_admin" rows="4" required placeholder="Jelaskan bagian yang perlu diperbaiki secara spesifik…">{{ old('note') }}</textarea>
                                    <p class="field-hint">Catatan akan dikirim ke pemohon melalui WhatsApp atau SMS.</p>
                                </div>
                                <button type="submit" class="btn btn-danger btn-block">Kirim Permintaan Perbaikan</button>
                            </form>
                        </details>

                        <details class="action-disclosure action-disclosure-success">
                            <summary><x-icon name="check-circle" :size="16" /> Selesaikan Pengajuan</summary>
                            <form action="{{ route('admin.submissions.complete', $submission) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="field">
                                    <label for="result_file">Dokumen hasil <span class="tag-optional">Opsional</span></label>
                                    <input type="file" id="result_file" name="result_file" accept=".pdf,.jpg,.jpeg,.png">
                                    <p class="field-hint">PDF atau gambar, maksimal 4 MB.</p>
                                </div>
                                <div class="field">
                                    <label for="complete_note">Catatan penyelesaian <span class="tag-optional">Opsional</span></label>
                                    <textarea name="note" id="complete_note" rows="2" placeholder="Contoh: dokumen dapat diambil mulai besok."></textarea>
                                </div>
                                <button type="submit" class="btn btn-success btn-block">Tandai Selesai</button>
                            </form>
                        </details>
                    @endif
                @endif
            </section>

            <section class="admin-card">
                <div class="admin-card-head">
                    <div>
                        <h2>Notifikasi</h2>
                        <p>Outbox untuk pemohon.</p>
                    </div>
                    <a href="{{ route('admin.notifications.index') }}" class="icon-button" aria-label="Buka semua notifikasi"><x-icon name="arrow-up-right" :size="16" /></a>
                </div>

                @if($submission->notifications->isEmpty())
                    <p class="admin-muted">Belum ada notifikasi.</p>
                @else
                    <ul class="admin-notif-list">
                        @foreach($submission->notifications->sortByDesc('created_at')->take(5) as $notification)
                            <li>
                                <span class="notif-channel {{ $notification->channel === 'sms' ? 'sms' : 'wa' }}"><x-icon :name="$notification->channel === 'sms' ? 'message-circle' : 'whatsapp'" :size="16" /></span>
                                <div>
                                    <strong>{{ $notification->channel_label }} <span class="notif-status notif-{{ $notification->status }}">{{ $notification->status_label }}</span></strong>
                                    <p>{{ \Illuminate\Support\Str::limit($notification->message, 95) }}</p>
                                    <small>{{ $notification->created_at->diffForHumans() }}</small>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </section>
        </aside>
    </div>
@endsection
