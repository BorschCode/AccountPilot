<?php

namespace App\Models;

use App\Enums\SiteRegistrationStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class SiteRegistration extends Model
{
    /** @use HasFactory<\Database\Factories\SiteRegistrationFactory> */
    use HasFactory;

    use SoftDeletes;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'status' => SiteRegistrationStatus::class,
            'result_data' => 'array',
            'queued_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    public function siteTemplate(): BelongsTo
    {
        return $this->belongsTo(SiteTemplate::class);
    }

    public function email(): BelongsTo
    {
        return $this->belongsTo(Email::class);
    }

    public function goLoginProfile(): BelongsTo
    {
        return $this->belongsTo(GoLoginProfile::class);
    }
}
