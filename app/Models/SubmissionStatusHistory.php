<?php

namespace App\Models;

use App\Enums\SubmissionStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubmissionStatusHistory extends Model
{
    protected $fillable = [
        'submission_id',
        'status',
        'title',
        'note',
        'changed_by',
    ];

    protected function casts(): array
    {
        return [
            'status' => SubmissionStatus::class,
        ];
    }

    public function submission(): BelongsTo
    {
        return $this->belongsTo(Submission::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
