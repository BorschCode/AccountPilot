<?php

namespace App\Models;

use App\Enums\EmploymentType;
use App\Enums\JobPostingStatus;
use App\Enums\Platform;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobPosting extends Model
{
    /** @use HasFactory<\Database\Factories\JobPostingFactory> */
    use HasFactory;

    use SoftDeletes;

    protected function casts(): array
    {
        return [
            'employment_type' => EmploymentType::class,
            'status' => JobPostingStatus::class,
            'platforms' => 'array',
            'queued_at' => 'datetime',
            'posted_at' => 'datetime',
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function platformPosts(): HasMany
    {
        return $this->hasMany(JobPlatformPost::class);
    }

    /** @return Platform[] */
    public function selectedPlatforms(): array
    {
        return array_map(
            fn (string $value) => Platform::from($value),
            $this->platforms ?? []
        );
    }
}
