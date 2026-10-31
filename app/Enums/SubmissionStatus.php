<?php

namespace App\Enums;

enum SubmissionStatus: string
{
    case Submitted = 'submitted';
    case Verified = 'verified';
    case NeedsRevision = 'needs_revision';
    case Completed = 'completed';

    public function label(): string
    {
        return match ($this) {
            self::Submitted => 'Diterima',
            self::Verified => 'Diverifikasi',
            self::NeedsRevision => 'Perlu Perbaikan',
            self::Completed => 'Selesai',
        };
    }

    public function shortDescription(): string
    {
        return match ($this) {
            self::Submitted => 'Pengajuan sudah masuk dan menunggu pemeriksaan petugas.',
            self::Verified => 'Berkas sudah diperiksa dan sedang diproses.',
            self::NeedsRevision => 'Ada berkas yang perlu diperbaiki atau dilengkapi.',
            self::Completed => 'Dokumen selesai dan siap diambil di kelurahan.',
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::Submitted => 'Pengajuan Anda sudah kami terima. Petugas akan memeriksa kelengkapan berkas.',
            self::Verified => 'Berkas Anda sudah diverifikasi. Pengajuan sedang diproses oleh petugas kelurahan.',
            self::NeedsRevision => 'Petugas menemukan berkas yang perlu diperbaiki. Silakan baca catatan dan unggah ulang berkas.',
            self::Completed => 'Pengajuan selesai. Silakan ambil dokumen asli di kantor kelurahan dengan membawa bukti pengajuan.',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::Submitted => 'badge-blue',
            self::Verified => 'badge-amber',
            self::NeedsRevision => 'badge-red',
            self::Completed => 'badge-green',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::Submitted => 'inbox',
            self::Verified => 'shield-check',
            self::NeedsRevision => 'alert',
            self::Completed => 'check-circle',
        };
    }

    public function step(): int
    {
        return match ($this) {
            self::Submitted => 1,
            self::Verified => 2,
            self::NeedsRevision => 2,
            self::Completed => 4,
        };
    }
}
