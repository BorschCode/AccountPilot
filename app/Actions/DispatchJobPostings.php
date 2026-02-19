<?php

namespace App\Actions;

use App\Enums\JobPostingStatus;
use App\Enums\PostingStatus;
use App\Jobs\PostJobToPlatform;
use App\Models\JobPlatformPost;
use App\Models\JobPosting;

class DispatchJobPostings
{
    /**
     * Create per-platform records and dispatch queue jobs for a job posting.
     */
    public function execute(JobPosting $jobPosting): void
    {
        $platforms = $jobPosting->selectedPlatforms();

        foreach ($platforms as $platform) {
            $platformPost = JobPlatformPost::firstOrCreate(
                [
                    'job_posting_id' => $jobPosting->id,
                    'platform' => $platform->value,
                ],
                ['status' => PostingStatus::Pending->value]
            );

            PostJobToPlatform::dispatch($jobPosting->id, $platformPost->id)
                ->onQueue('automation');
        }

        $jobPosting->update([
            'status' => JobPostingStatus::Queued,
            'queued_at' => now(),
        ]);
    }
}
