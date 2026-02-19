<?php

namespace App\Enums;

enum JobPostingStatus: string
{
    case Draft = 'draft';
    case Queued = 'queued';
    case Posting = 'posting';
    case Posted = 'posted';
    case PartiallyPosted = 'partially_posted';
    case Failed = 'failed';

    public function label(): string
    {
        return match ($this) {
            JobPostingStatus::Draft => 'Draft',
            JobPostingStatus::Queued => 'Queued',
            JobPostingStatus::Posting => 'Posting',
            JobPostingStatus::Posted => 'Posted',
            JobPostingStatus::PartiallyPosted => 'Partially Posted',
            JobPostingStatus::Failed => 'Failed',
        };
    }

    public function color(): string
    {
        return match ($this) {
            JobPostingStatus::Draft => 'gray',
            JobPostingStatus::Queued => 'blue',
            JobPostingStatus::Posting => 'yellow',
            JobPostingStatus::Posted => 'green',
            JobPostingStatus::PartiallyPosted => 'orange',
            JobPostingStatus::Failed => 'red',
        };
    }
}
