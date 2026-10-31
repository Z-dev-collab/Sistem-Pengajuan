<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'tagline',
        'description',
        'icon',
        'keywords',
        'fee',
        'fee_note',
        'estimated_days',
        'form_title',
        'form_fields',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'form_fields' => 'array',
            'is_active' => 'boolean',
            'fee' => 'integer',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function requirements(): HasMany
    {
        return $this->hasMany(ServiceRequirement::class)->orderBy('sort_order');
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(Submission::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function getFeeLabelAttribute(): string
    {
        return $this->fee > 0
            ? 'Rp '.number_format($this->fee, 0, ',', '.')
            : 'Gratis';
    }

    public function getEstimateLabelAttribute(): string
    {
        return '± '.$this->estimated_days.' hari kerja';
    }
}
