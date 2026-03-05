<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class SiteTemplate extends Model
{
    /** @use HasFactory<\Database\Factories\SiteTemplateFactory> */
    use HasFactory;

    use SoftDeletes;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'expects_email_confirmation' => 'boolean',
        ];
    }

    public function siteRegistrations(): HasMany
    {
        return $this->hasMany(SiteRegistration::class);
    }
}
