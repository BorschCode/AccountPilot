<?php

namespace App\Models;

use App\Enums\Platform;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlatformAccount extends Model
{
    /** @use HasFactory<\Database\Factories\PlatformAccountFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'platform' => Platform::class,
        ];
    }

    public function email(): BelongsTo
    {
        return $this->belongsTo(Email::class);
    }
}
