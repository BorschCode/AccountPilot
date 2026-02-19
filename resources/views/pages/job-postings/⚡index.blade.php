<?php

use App\Actions\DispatchJobPostings;
use App\Enums\JobPostingStatus;
use App\Models\JobPosting;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

new class extends Component {
    use WithPagination;

    public string $statusFilter = '';
    public string $search = '';

    public function updatedStatusFilter(): void
    {
        $this->resetPage();
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function dispatch(int $jobPostingId): void
    {
        $jobPosting = JobPosting::findOrFail($jobPostingId);

        if (! in_array($jobPosting->status, [JobPostingStatus::Draft, JobPostingStatus::Failed])) {
            return;
        }

        app(DispatchJobPostings::class)->execute($jobPosting);

        $this->dispatch('posting-queued');
    }

    #[Computed]
    public function statuses(): array
    {
        return JobPostingStatus::cases();
    }

    #[Computed]
    public function jobPostings()
    {
        return JobPosting::query()
            ->with(['creator', 'platformPosts'])
            ->when($this->search, fn ($q) => $q->where('title', 'like', "%{$this->search}%"))
            ->when($this->statusFilter, fn ($q) => $q->where('status', $this->statusFilter))
            ->latest()
            ->paginate(15);
    }
}; ?>

<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <flux:heading size="xl">Job Postings</flux:heading>
            <flux:subheading>Manage and dispatch job postings across all platforms.</flux:subheading>
        </div>
        <flux:button icon="plus" variant="primary" href="{{ route('job-postings.create') }}" wire:navigate>
            New Posting
        </flux:button>
    </div>

    <div class="flex gap-3 mb-4">
        <flux:input
            wire:model.live.debounce="search"
            placeholder="Search by title..."
            icon="magnifying-glass"
            class="max-w-xs"
        />
        <flux:select wire:model.live="statusFilter" class="max-w-xs">
            <flux:select.option value="">All Statuses</flux:select.option>
            @foreach ($this->statuses as $status)
                <flux:select.option value="{{ $status->value }}">{{ $status->label() }}</flux:select.option>
            @endforeach
        </flux:select>
    </div>

    <div class="rounded-lg border border-zinc-200 dark:border-zinc-700 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-zinc-50 dark:bg-zinc-800 border-b border-zinc-200 dark:border-zinc-700">
                <tr>
                    <th class="px-4 py-3 text-left font-medium text-zinc-600 dark:text-zinc-400">Title</th>
                    <th class="px-4 py-3 text-left font-medium text-zinc-600 dark:text-zinc-400">Platforms</th>
                    <th class="px-4 py-3 text-left font-medium text-zinc-600 dark:text-zinc-400">Status</th>
                    <th class="px-4 py-3 text-left font-medium text-zinc-600 dark:text-zinc-400">Posted At</th>
                    <th class="px-4 py-3 text-left font-medium text-zinc-600 dark:text-zinc-400">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-100 dark:divide-zinc-700">
                @forelse ($this->jobPostings as $posting)
                    <tr wire:key="{{ $posting->id }}" class="bg-white dark:bg-zinc-900 hover:bg-zinc-50 dark:hover:bg-zinc-800 transition-colors">
                        <td class="px-4 py-3">
                            <div class="font-medium">{{ $posting->title }}</div>
                            <div class="text-xs text-zinc-500">{{ $posting->location }}</div>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex flex-wrap gap-1">
                                @foreach ($posting->selectedPlatforms() as $platform)
                                    <flux:badge size="sm" color="zinc">{{ $platform->label() }}</flux:badge>
                                @endforeach
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <flux:badge color="{{ $posting->status->color() }}">
                                {{ $posting->status->label() }}
                            </flux:badge>
                        </td>
                        <td class="px-4 py-3 text-zinc-500">
                            {{ $posting->posted_at?->diffForHumans() ?? '—' }}
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <flux:button
                                    size="sm"
                                    variant="ghost"
                                    icon="eye"
                                    href="{{ route('job-postings.show', $posting) }}"
                                    wire:navigate
                                />
                                @if (in_array($posting->status, [App\Enums\JobPostingStatus::Draft, App\Enums\JobPostingStatus::Failed]))
                                    <flux:button
                                        size="sm"
                                        variant="ghost"
                                        icon="paper-airplane"
                                        wire:click="dispatch({{ $posting->id }})"
                                        wire:confirm="Queue this job for posting to all selected platforms?"
                                        wire:loading.attr="disabled"
                                    />
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-12 text-center text-zinc-500">
                            No job postings found.
                            <flux:link href="{{ route('job-postings.create') }}" wire:navigate>Create one now.</flux:link>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $this->jobPostings->links() }}
    </div>
</div>
