<?php

namespace App\Enums;

enum GoLoginProfileStatus: string
{
    case Active = 'active';
    case Flagged = 'flagged';
    case Expired = 'expired';
    case Deleted = 'deleted';

    public function label(): string
    {
        return match ($this) {
            GoLoginProfileStatus::Active => 'Active',
            GoLoginProfileStatus::Flagged => 'Flagged',
            GoLoginProfileStatus::Expired => 'Expired',
            GoLoginProfileStatus::Deleted => 'Deleted',
        };
    }

    public function color(): string
    {
        return match ($this) {
            GoLoginProfileStatus::Active => 'green',
            GoLoginProfileStatus::Flagged => 'red',
            GoLoginProfileStatus::Expired => 'orange',
            GoLoginProfileStatus::Deleted => 'gray',
        };
    }
}
