<?php

namespace App\Actions;

use App\Enums\SiteRegistrationStatus;
use App\Jobs\ProcessSiteRegistration;
use App\Models\SiteRegistration;

class DispatchSiteRegistration
{
    /**
     * Dispatch the site registration job to the queue.
     */
    public function execute(SiteRegistration $siteRegistration): void
    {
        ProcessSiteRegistration::dispatch($siteRegistration->id)
            ->onQueue('automation');

        $siteRegistration->update([
            'status' => SiteRegistrationStatus::Queued,
            'queued_at' => now(),
        ]);
    }
}
