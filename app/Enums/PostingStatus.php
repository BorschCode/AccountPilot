<?php

namespace App\Enums;

enum PostingStatus: string
{
    case Pending = 'pending';
    case Posting = 'posting';
    case Posted = 'posted';
    case Failed = 'failed';
    case Skipped = 'skipped';

    public function label(): string
    {
        return match ($this) {
            PostingStatus::Pending => 'Pending',
            PostingStatus::Posting => 'Posting',
            PostingStatus::Posted => 'Posted',
            PostingStatus::Failed => 'Failed',
            PostingStatus::Skipped => 'Skipped (Profile Flagged)',
        };
    }

    public function color(): string
    {
        return match ($this) {
            PostingStatus::Pending => 'gray',
            PostingStatus::Posting => 'yellow',
            PostingStatus::Posted => 'green',
            PostingStatus::Failed => 'red',
            PostingStatus::Skipped => 'orange',
        };
    }
}
