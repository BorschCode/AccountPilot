<?php

namespace App\Enums;

enum SiteRegistrationStatus: string
{
    case Draft = 'draft';
    case Queued = 'queued';
    case Registering = 'registering';
    case WaitingConfirmation = 'waiting_confirmation';
    case Completed = 'completed';
    case Failed = 'failed';

    public function label(): string
    {
        return match ($this) {
            SiteRegistrationStatus::Draft => 'Draft',
            SiteRegistrationStatus::Queued => 'Queued',
            SiteRegistrationStatus::Registering => 'Registering',
            SiteRegistrationStatus::WaitingConfirmation => 'Waiting Confirmation',
            SiteRegistrationStatus::Completed => 'Completed',
            SiteRegistrationStatus::Failed => 'Failed',
        };
    }

    public function color(): string
    {
        return match ($this) {
            SiteRegistrationStatus::Draft => 'gray',
            SiteRegistrationStatus::Queued => 'blue',
            SiteRegistrationStatus::Registering => 'yellow',
            SiteRegistrationStatus::WaitingConfirmation => 'orange',
            SiteRegistrationStatus::Completed => 'green',
            SiteRegistrationStatus::Failed => 'red',
        };
    }
}
