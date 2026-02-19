<?php

namespace App\Models;

use App\Enums\GoLoginProfileStatus;
use App\Enums\Platform;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GoLoginProfile extends Model
{
    /** @use HasFactory<\Database\Factories\GoLoginProfileFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'platform' => Platform::class,
            'status' => GoLoginProfileStatus::class,
            'last_score_checked_at' => 'datetime',
            'last_used_at' => 'datetime',
        ];
    }

    public function platformPosts(): HasMany
    {
        return $this->hasMany(JobPlatformPost::class);
    }

    public function isUsable(): bool
    {
        return $this->status === GoLoginProfileStatus::Active
            && ($this->risk_score === null || $this->risk_score < 30);
    }
}
