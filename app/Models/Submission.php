<?php

namespace App\Models;

use App\Enums\SubmissionStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Submission extends Model
{
    use HasFactory;

    public const DOCUMENT_TYPES = [
        'ktp' => 'KTP',
        'kk' => 'Kartu Keluarga',
        'pengantar' => 'Surat Pengantar RT/RW',
        'pendukung' => 'Dokumen Pendukung',
    ];

    protected $fillable = [
        'tracking_code',
        'service_id',
        'applicant_name',
        'nik',
        'kk_number',
        'phone',
        'email',
        'address',
        'rt',
        'rw',
        'notes',
        'status',
        'notification_channel',
        'admin_notes',
        'result_file',
        'verified_at',
        'completed_at',
        'completed_by',
    ];

    protected function casts(): array
    {
        return [
            'status' => SubmissionStatus::class,
            'verified_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(SubmissionDocument::class);
    }

    public function histories(): HasMany
    {
        return $this->hasMany(SubmissionStatusHistory::class)->latest();
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    public function completedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'completed_by');
    }

    public function scopeFilter(Builder $query, array $filters): Builder
    {
        return $query
            ->when($filters['status'] ?? null, fn (Builder $q, $status) => $q->where('status', $status))
            ->when($filters['service'] ?? null, fn (Builder $q, $service) => $q->where('service_id', $service))
            ->when($filters['q'] ?? null, function (Builder $q, $search) {
                $q->where(function (Builder $inner) use ($search) {
                    $inner->where('tracking_code', 'like', "%{$search}%")
                        ->orWhere('applicant_name', 'like', "%{$search}%")
                        ->orWhere('nik', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                });
            });
    }

    public function getMaskedNikAttribute(): string
    {
        return substr($this->nik, 0, 6).'••••••'.substr($this->nik, -2);
    }

    public function getMaskedPhoneAttribute(): string
    {
        $phone = preg_replace('/[^0-9]/', '', $this->phone);

        return substr($phone, 0, 4).'••••'.substr($phone, -2);
    }

    public function getWhatsappUrlAttribute(): string
    {
        $phone = preg_replace('/[^0-9]/', '', $this->phone);
        $phone = str_starts_with($phone, '0') ? '62'.substr($phone, 1) : $phone;

        return 'https://wa.me/'.$phone;
    }

    public function documentOf(string $type): ?SubmissionDocument
    {
        return $this->documents->firstWhere('type', $type);
    }
}
