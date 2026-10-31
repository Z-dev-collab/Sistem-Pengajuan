<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    protected $fillable = [
        'submission_id',
        'channel',
        'recipient',
        'message',
        'status',
        'provider_reference',
        'error',
        'sent_at',
    ];

    protected function casts(): array
    {
        return [
            'sent_at' => 'datetime',
        ];
    }

    public function submission(): BelongsTo
    {
        return $this->belongsTo(Submission::class);
    }

    public function getChannelLabelAttribute(): string
    {
        return $this->channel === 'sms' ? 'SMS' : 'WhatsApp';
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'sent' => 'Terkirim',
            'failed' => 'Gagal',
            'cancelled' => 'Dibatalkan',
            default => 'Menunggu',
        };
    }

    public function getWhatsappUrlAttribute(): ?string
    {
        if ($this->channel !== 'whatsapp' || ! $this->recipient) {
            return null;
        }

        $phone = preg_replace('/[^0-9]/', '', $this->recipient);
        $phone = str_starts_with($phone, '0') ? '62'.substr($phone, 1) : $phone;

        return 'https://wa.me/'.$phone.'?text='.urlencode($this->message);
    }
}
