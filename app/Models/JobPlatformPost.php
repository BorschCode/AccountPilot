<?php

namespace App\Models;

use App\Enums\Platform;
use App\Enums\PostingStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobPlatformPost extends Model
{
    /** @use HasFactory<\Database\Factories\JobPlatformPostFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'platform' => Platform::class,
            'status' => PostingStatus::class,
            'posted_at' => 'datetime',
        ];
    }

    public function jobPosting(): BelongsTo
    {
        return $this->belongsTo(JobPosting::class);
    }

    public function goLoginProfile(): BelongsTo
    {
        return $this->belongsTo(GoLoginProfile::class);
    }

    public function isSuccessful(): bool
    {
        return $this->status === PostingStatus::Posted && $this->external_url !== null;
    }
}
