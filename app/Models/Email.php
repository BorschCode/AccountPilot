<?php

namespace App\Models;

use App\Enums\ProxyType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Email extends Model
{
    /** @use HasFactory<\Database\Factories\EmailFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'proxy_type' => ProxyType::class,
        ];
    }

    public function platformAccounts(): HasMany
    {
        return $this->hasMany(PlatformAccount::class);
    }
}
