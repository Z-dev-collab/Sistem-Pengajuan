@extends('layouts.admin')

@section('title', 'Layanan')

@section('content')
    <div class="admin-page-head">
        <div>
            <p class="admin-eyebrow">Konten situs</p>
            <h1>Layanan &amp; persyaratan</h1>
            <p>Perbarui informasi layanan, biaya, estimasi, dan daftar persyaratan yang tampil di situs publik.</p>
        </div>
        <div class="admin-page-actions">
            <a href="{{ route('services.index') }}" target="_blank" class="btn btn-outline">
                <x-icon name="external-link" :size="16" />
                Lihat Halaman Publik
            </a>
        </div>
    </div>

    <div class="admin-service-grid">
        @foreach($services as $service)
            <article class="admin-service-card {{ $service->is_active ? '' : 'inactive' }}">
                <div class="admin-service-card-head">
                    <span class="service-icon service-icon-{{ ($service->sort_order % 3) ?: 1 }}">
                        <x-icon :name="$service->icon" :size="27" />
                    </span>
                    <span class="badge {{ $service->is_active ? 'badge-green' : 'badge-gray' }}">{{ $service->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                </div>
                <h2>{{ $service->name }}</h2>
                <p>{{ $service->tagline }}</p>
                <div class="admin-service-meta">
                    <span><x-icon name="wallet" :size="15" /> {{ $service->fee_label }}</span>
                    <span><x-icon name="clock" :size="15" /> {{ $service->estimate_label }}</span>
                    <span><x-icon name="file-text" :size="15" /> {{ $service->requirements_count }} syarat</span>
                </div>
                <div class="admin-service-stats">
                    <div><strong>{{ $service->submissions_count }}</strong><span>Total pengajuan</span></div>
                    <div><strong>{{ $service->requirements_count }}</strong><span>Persyaratan</span></div>
                </div>
                <a href="{{ route('admin.services.edit', $service) }}" class="btn btn-outline btn-block">
                    <x-icon name="edit" :size="16" />
                    Kelola Layanan
                </a>
            </article>
        @endforeach
    </div>
@endsection
