<?php

namespace App\Jobs;

use App\Enums\SiteRegistrationStatus;
use App\Models\SiteRegistration;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ProcessSiteRegistration implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public int $backoff = 1800;

    public function __construct(public readonly int $siteRegistrationId) {}

    public function handle(): void
    {
        $registration = SiteRegistration::findOrFail($this->siteRegistrationId);

        $registration->update(['status' => SiteRegistrationStatus::Registering]);

        // TODO: Implement browser automation for site registration.
        // Steps:
        // 1. Resolve a GoLogin profile for the browser session.
        // 2. Open the site_template->site_url and fill the registration form.
        // 3. If site_template->expects_email_confirmation, poll the email inbox
        //    via IMAP for a confirmation link up to confirmation_timeout seconds.
        //    On timeout, set status to WaitingConfirmation for manual intervention.
        // 4. On success, store result_data and set status to Completed.
        // 5. On failure, store error_message and set status to Failed.

        $registration->update([
            'status' => SiteRegistrationStatus::Completed,
            'completed_at' => now(),
        ]);
    }

    public function failed(\Throwable $exception): void
    {
        SiteRegistration::where('id', $this->siteRegistrationId)->update([
            'status' => SiteRegistrationStatus::Failed,
            'error_message' => $exception->getMessage(),
        ]);
    }
}
