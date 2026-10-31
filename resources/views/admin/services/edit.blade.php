@extends('layouts.admin')

@section('title', 'Kelola ' . $service->name)

@section('content')
    <div class="admin-breadcrumb">
        <a href="{{ route('admin.services.index') }}"><x-icon name="arrow-left" :size="15" /> Semua layanan</a>
        <span>/</span>
        <span>{{ $service->name }}</span>
    </div>

    <div class="admin-page-head">
        <div>
            <p class="admin-eyebrow">Konten layanan</p>
            <h1>{{ $service->name }}</h1>
            <p>Perubahan akan langsung tampil di halaman publik layanan.</p>
        </div>
        <div class="admin-page-actions">
            <a href="{{ route('services.show', $service) }}" target="_blank" class="btn btn-outline">
                <x-icon name="external-link" :size="16" />
                Pratinjau
            </a>
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

    <div class="admin-detail-grid admin-service-edit-grid">
        <div class="admin-detail-main">
            <section class="admin-card">
                <div class="admin-card-head">
                    <div>
                        <h2>Informasi layanan</h2>
                        <p>Detail utama yang dibaca warga sebelum mengajukan.</p>
                    </div>
                </div>

                <form action="{{ route('admin.services.update', $service) }}" method="POST" class="admin-form">
                    @csrf
                    @method('PUT')

                    <div class="form-grid">
                        <div class="field field-full">
                            <label for="name">Nama layanan</label>
                            <input type="text" id="name" name="name" value="{{ old('name', $service->name) }}" required>
                        </div>
                        <div class="field field-full">
                            <label for="tagline">Ringkasan singkat</label>
                            <input type="text" id="tagline" name="tagline" value="{{ old('tagline', $service->tagline) }}" required>
                        </div>
                        <div class="field field-full">
                            <label for="description">Deskripsi</label>
                            <textarea id="description" name="description" rows="5" required>{{ old('description', $service->description) }}</textarea>
                        </div>
                        <div class="field">
                            <label for="fee">Biaya (Rp)</label>
                            <input type="number" id="fee" name="fee" value="{{ old('fee', $service->fee) }}" min="0" step="1000" required>
                        </div>
                        <div class="field">
                            <label for="estimated_days">Estimasi (hari kerja)</label>
                            <input type="number" id="estimated_days" name="estimated_days" value="{{ old('estimated_days', $service->estimated_days) }}" min="1" max="30" required>
                        </div>
                        <div class="field field-full">
                            <label for="fee_note">Catatan biaya</label>
                            <input type="text" id="fee_note" name="fee_note" value="{{ old('fee_note', $service->fee_note) }}">
                        </div>
                        <div class="field field-full">
                            <label for="keywords">Kata kunci pencarian</label>
                            <input type="text" id="keywords" name="keywords" value="{{ old('keywords', $service->keywords) }}" placeholder="nikah, surat nikah, KUA">
                            <p class="field-hint">Pisahkan dengan koma. Membantu warga menemukan layanan melalui pencarian.</p>
                        </div>
                        <div class="field field-full">
                            <label for="form_title">Judul formulir PDF</label>
                            <input type="text" id="form_title" name="form_title" value="{{ old('form_title', $service->form_title) }}">
                        </div>
                    </div>

                    <label class="switch-row">
                        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $service->is_active))>
                        <span class="switch-track"><span></span></span>
                        <span>
                            <strong>Layanan aktif</strong>
                            <small>Jika dinonaktifkan, layanan tidak tampil dan tidak dapat diajukan.</small>
                        </span>
                    </label>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <x-icon name="check" :size="17" />
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </section>

            <section class="admin-card">
                <div class="admin-card-head">
                    <div>
                        <h2>Persyaratan berkas</h2>
                        <p>Urutan tampil mengikuti urutan di bawah.</p>
                    </div>
                    <span class="count-badge">{{ $service->requirements->count() }} item</span>
                </div>

                <ul class="admin-requirement-list">
                    @foreach($service->requirements as $requirement)
                        <li>
                            <form action="{{ route('admin.services.requirements.update', [$service, $requirement]) }}" method="POST" class="requirement-form">
                                @csrf
                                @method('PUT')
                                <span class="requirement-order">{{ $loop->iteration }}</span>
                                <div class="requirement-fields">
                                    <input type="text" name="name" value="{{ $requirement->name }}" aria-label="Nama persyaratan" required>
                                    <input type="text" name="note" value="{{ $requirement->note }}" placeholder="Catatan (opsional)" aria-label="Catatan persyaratan">
                                    <label class="inline-check">
                                        <input type="checkbox" name="is_mandatory" value="1" @checked($requirement->is_mandatory)>
                                        Wajib
                                    </label>
                                </div>
                                <button type="submit" class="btn btn-outline btn-xs">Simpan</button>
                            </form>
                            <form action="{{ route('admin.services.requirements.destroy', [$service, $requirement]) }}" method="POST" class="requirement-delete" onsubmit="return confirm('Hapus persyaratan ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="icon-button icon-button-danger" title="Hapus"><x-icon name="trash" :size="16" /></button>
                            </form>
                        </li>
                    @endforeach
                </ul>

                <form action="{{ route('admin.services.requirements.store', $service) }}" method="POST" class="add-requirement-form">
                    @csrf
                    <div class="form-grid">
                        <div class="field field-full">
                            <label for="new_requirement_name">Tambah persyaratan</label>
                            <input type="text" id="new_requirement_name" name="name" placeholder="Nama persyaratan baru" required>
                        </div>
                        <div class="field">
                            <label for="new_requirement_note">Catatan</label>
                            <input type="text" id="new_requirement_note" name="note" placeholder="Opsional">
                        </div>
                        <div class="field field-inline-end">
                            <label class="inline-check">
                                <input type="checkbox" name="is_mandatory" value="1" checked>
                                Wajib dilengkapi
                            </label>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-outline btn-sm">
                        <x-icon name="plus" :size="16" />
                        Tambah Persyaratan
                    </button>
                </form>
            </section>
        </div>

        <aside class="admin-detail-aside">
            <section class="admin-card">
                <div class="admin-card-head">
                    <div>
                        <h2>Pratinjau kartu</h2>
                        <p>Tampilan di halaman layanan.</p>
                    </div>
                </div>
                <div class="mini-service-preview">
                    <span class="service-icon service-icon-lg"><x-icon :name="$service->icon" :size="32" /></span>
                    <h3>{{ $service->name }}</h3>
                    <p>{{ $service->tagline }}</p>
                    <div class="admin-service-meta">
                        <span><x-icon name="wallet" :size="14" /> {{ $service->fee_label }}</span>
                        <span><x-icon name="clock" :size="14" /> {{ $service->estimate_label }}</span>
                    </div>
                </div>
            </section>

            <section class="admin-card admin-help-card">
                <x-icon name="info" :size="22" />
                <h2>Catatan</h2>
                <p>Formulir PDF dibuat otomatis dari data formulir layanan. Perubahan pada form_fields hanya dapat dilakukan melalui seeder atau database.</p>
            </section>
        </aside>
    </div>
@endsection
