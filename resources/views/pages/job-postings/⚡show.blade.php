<?php

use App\Actions\DispatchJobPostings;
use App\Enums\JobPostingStatus;
use App\Enums\PostingStatus;
use App\Models\JobPosting;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Component;

new class extends Component {
    #[Locked]
    public int $jobPostingId;

    public function mount(JobPosting $jobPosting): void
    {
        $this->jobPostingId = $jobPosting->id;
    }

    #[Computed]
    public function jobPosting(): JobPosting
    {
        return JobPosting::with(['platformPosts.goLoginProfile', 'creator'])
            ->findOrFail($this->jobPostingId);
    }

    public function dispatch(): void
    {
        $jobPosting = $this->jobPosting;

        if (! in_array($jobPosting->status, [JobPostingStatus::Draft, JobPostingStatus::Failed, JobPostingStatus::PartiallyPosted])) {
            return;
        }

        app(DispatchJobPostings::class)->execute($jobPosting);

        unset($this->jobPosting);
    }

    public function canDispatch(): bool
    {
        return in_array(
            $this->jobPosting->status,
            [JobPostingStatus::Draft, JobPostingStatus::Failed, JobPostingStatus::PartiallyPosted]
        );
    }
}; ?>

<div>
    <div class="mb-6 flex items-start justify-between">
        <div>
            <flux:link href="{{ route('job-postings.index') }}" wire:navigate icon="arrow-left" icon:variant="outline">
                Back to Postings
            </flux:link>
            <flux:heading size="xl" class="mt-2">{{ $this->jobPosting->title }}</flux:heading>
            <div class="flex items-center gap-3 mt-1">
                <flux:badge color="{{ $this->jobPosting->status->color() }}">
                    {{ $this->jobPosting->status->label() }}
                </flux:badge>
                <flux:text>{{ $this->jobPosting->location }}</flux:text>
                <flux:text>{{ $this->jobPosting->employment_type->label() }}</flux:text>
            </div>
        </div>

        @if ($this->canDispatch())
            <flux:button
                variant="primary"
                icon="paper-airplane"
                wire:click="dispatch"
                wire:confirm="Queue this job for posting to all selected platforms?"
                wire:loading.attr="disabled"
            >
                Post Now
            </flux:button>
        @endif
    </div>

    <div class="grid grid-cols-3 gap-6">
        {{-- Job Details --}}
        <div class="col-span-2 space-y-6">
            <div class="rounded-lg border border-zinc-200 dark:border-zinc-700 p-5">
                <flux:heading size="lg" class="mb-3">Description</flux:heading>
                <flux:text class="whitespace-pre-line">{{ $this->jobPosting->description }}</flux:text>
            </div>

            {{-- Platform Posts Table --}}
            <div class="rounded-lg border border-zinc-200 dark:border-zinc-700 overflow-hidden">
                <div class="px-5 py-4 border-b border-zinc-200 dark:border-zinc-700">
                    <flux:heading size="lg">Platform Postings</flux:heading>
                </div>
                <table class="w-full text-sm">
                    <thead class="bg-zinc-50 dark:bg-zinc-800">
                        <tr>
                            <th class="px-4 py-3 text-left font-medium text-zinc-600 dark:text-zinc-400">Platform</th>
                            <th class="px-4 py-3 text-left font-medium text-zinc-600 dark:text-zinc-400">Status</th>
                            <th class="px-4 py-3 text-left font-medium text-zinc-600 dark:text-zinc-400">Risk Score</th>
                            <th class="px-4 py-3 text-left font-medium text-zinc-600 dark:text-zinc-400">Posted At</th>
                            <th class="px-4 py-3 text-left font-medium text-zinc-600 dark:text-zinc-400">Link</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100 dark:divide-zinc-700">
                        @foreach ($this->jobPosting->platformPosts as $post)
                            <tr wire:key="{{ $post->id }}" class="bg-white dark:bg-zinc-900">
                                <td class="px-4 py-3 font-medium">{{ $post->platform->label() }}</td>
                                <td class="px-4 py-3">
                                    <flux:badge color="{{ $post->status->color() }}" size="sm">
                                        {{ $post->status->label() }}
                                    </flux:badge>
                                </td>
                                <td class="px-4 py-3">
                                    @if ($post->risk_score_at_posting !== null)
                                        <span @class([
                                            'font-mono text-xs px-2 py-0.5 rounded',
                                            'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300' => $post->risk_score_at_posting < 30,
                                            'bg-yellow-100 text-yellow-700 dark:bg-yellow-900 dark:text-yellow-300' => $post->risk_score_at_posting >= 30 && $post->risk_score_at_posting < 60,
                                            'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300' => $post->risk_score_at_posting >= 60,
                                        ])>{{ $post->risk_score_at_posting }}</span>
                                    @else
                                        <span class="text-zinc-400">—</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-zinc-500">
                                    {{ $post->posted_at?->diffForHumans() ?? '—' }}
                                </td>
                                <td class="px-4 py-3">
                                    @if ($post->external_url)
                                        <flux:link :href="$post->external_url" target="_blank" icon="arrow-top-right-on-square">
                                            View
                                        </flux:link>
                                    @elseif ($post->error_message)
                                        <flux:tooltip :content="$post->error_message">
                                            <flux:text class="text-red-500 cursor-help">Error</flux:text>
                                        </flux:tooltip>
                                    @else
                                        <span class="text-zinc-400">—</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach

                        @if ($this->jobPosting->platformPosts->isEmpty())
                            <tr>
                                <td colspan="5" class="px-4 py-6 text-center text-zinc-500">
                                    No platforms queued yet. Click "Post Now" to dispatch.
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Sidebar Meta --}}
        <div class="space-y-4">
            <div class="rounded-lg border border-zinc-200 dark:border-zinc-700 p-5 space-y-3">
                <flux:heading size="base">Details</flux:heading>
                <dl class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <flux:text class="text-zinc-500">Created by</flux:text>
                        <flux:text>{{ $this->jobPosting->creator->name }}</flux:text>
                    </div>
                    <div class="flex justify-between">
                        <flux:text class="text-zinc-500">Created</flux:text>
                        <flux:text>{{ $this->jobPosting->created_at->diffForHumans() }}</flux:text>
                    </div>
                    @if ($this->jobPosting->queued_at)
                        <div class="flex justify-between">
                            <flux:text class="text-zinc-500">Queued</flux:text>
                            <flux:text>{{ $this->jobPosting->queued_at->diffForHumans() }}</flux:text>
                        </div>
                    @endif
                    @if ($this->jobPosting->salary_min || $this->jobPosting->salary_max)
                        <div class="flex justify-between">
                            <flux:text class="text-zinc-500">Salary</flux:text>
                            <flux:text>
                                {{ $this->jobPosting->salary_currency }}
                                {{ number_format($this->jobPosting->salary_min) }}–{{ number_format($this->jobPosting->salary_max) }}
                            </flux:text>
                        </div>
                    @endif
                </dl>
            </div>

            <div class="rounded-lg border border-zinc-200 dark:border-zinc-700 p-5">
                <flux:heading size="base" class="mb-3">Target Platforms</flux:heading>
                <div class="space-y-1">
                    @foreach ($this->jobPosting->selectedPlatforms() as $platform)
                        <flux:badge color="zinc" class="mr-1">{{ $platform->label() }}</flux:badge>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
