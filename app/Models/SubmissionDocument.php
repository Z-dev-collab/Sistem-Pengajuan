<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class SubmissionDocument extends Model
{
    protected $fillable = [
        'submission_id',
        'type',
        'original_name',
        'path',
        'mime_type',
        'size',
    ];

    public function submission(): BelongsTo
    {
        return $this->belongsTo(Submission::class);
    }

    public function getTypeLabelAttribute(): string
    {
        return Submission::DOCUMENT_TYPES[$this->type] ?? ucfirst($this->type);
    }

    public function getSizeLabelAttribute(): string
    {
        if ($this->size >= 1024 * 1024) {
            return number_format($this->size / 1024 / 1024, 1, ',', '.').' MB';
        }

        return number_format($this->size / 1024, 0, ',', '.').' KB';
    }

    public function getExistsAttribute(): bool
    {
        return Storage::disk('local')->exists($this->path);
    }
}
