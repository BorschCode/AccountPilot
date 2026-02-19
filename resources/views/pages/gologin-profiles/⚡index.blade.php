<?php

use App\Enums\GoLoginProfileStatus;
use App\Enums\Platform;
use App\Models\GoLoginProfile;
use App\Services\GoLoginService;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Validate;
use Livewire\Component;

new class extends Component {
    public bool $showCreateModal = false;

    #[Validate('required|string|max:255')]
    public string $newName = '';

    #[Validate(['required', 'string'])]
    public string $newPlatform = '';

    #[Validate('nullable|string|max:255')]
    public string $newProxy = '';

    public ?int $checkingScoreForProfileId = null;

    public function mount(): void
    {
        $this->newPlatform = Platform::LinkedIn->value;
    }

    #[Computed]
    public function profiles()
    {
        return GoLoginProfile::query()
            ->orderBy('platform')
            ->orderBy('status')
            ->get();
    }

    #[Computed]
    public function platforms(): array
    {
        return Platform::cases();
    }

    public function openCreateModal(): void
    {
        $this->resetValidation();
        $this->newName = '';
        $this->newProxy = '';
        $this->showCreateModal = true;
    }

    public function createProfile(GoLoginService $goLoginService): void
    {
        $this->validate();

        $platform = Platform::from($this->newPlatform);

        $gologinProfileId = $goLoginService->createProfile([
            'name' => $this->newName,
            'os' => 'lin',
        ]);

        GoLoginProfile::create([
            'gologin_profile_id' => $gologinProfileId,
            'name' => $this->newName,
            'platform' => $platform->value,
            'proxy_address' => $this->newProxy ?: null,
            'status' => GoLoginProfileStatus::Active->value,
        ]);

        $this->showCreateModal = false;
        unset($this->profiles);
    }

    public function checkScore(int $profileId): void
    {
        $this->checkingScoreForProfileId = $profileId;

        $profile = GoLoginProfile::findOrFail($profileId);

        // This would normally be dispatched to a queue, but for immediate
        // feedback we call directly from the UI
        $antCaptcha = app(\App\Services\AntCaptchaService::class);

        try {
            $score = $antCaptcha->checkProfileScore(
                $profile->platform->baseUrl(),
                $profile->proxy_address ?? '127.0.0.1:8080'
            );

            $profile->update([
                'risk_score' => $score,
                'last_score_checked_at' => now(),
                'status' => $score >= config('gologin.risk_score.manual')
                    ? GoLoginProfileStatus::Flagged
                    : GoLoginProfileStatus::Active,
            ]);
        } finally {
            $this->checkingScoreForProfileId = null;
            unset($this->profiles);
        }
    }

    public function deleteProfile(int $profileId, GoLoginService $goLoginService): void
    {
        $profile = GoLoginProfile::findOrFail($profileId);

        $goLoginService->deleteProfile($profile->gologin_profile_id);

        $profile->delete();

        unset($this->profiles);
    }
}; ?>

<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <flux:heading size="xl">GoLogin Profiles</flux:heading>
            <flux:subheading>Manage anti-detect browser profiles for each platform.</flux:subheading>
        </div>
        <flux:button icon="plus" variant="primary" wire:click="openCreateModal">
            New Profile
        </flux:button>
    </div>

    <div class="rounded-lg border border-zinc-200 dark:border-zinc-700 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-zinc-50 dark:bg-zinc-800 border-b border-zinc-200 dark:border-zinc-700">
                <tr>
                    <th class="px-4 py-3 text-left font-medium text-zinc-600 dark:text-zinc-400">Name</th>
                    <th class="px-4 py-3 text-left font-medium text-zinc-600 dark:text-zinc-400">Platform</th>
                    <th class="px-4 py-3 text-left font-medium text-zinc-600 dark:text-zinc-400">Status</th>
                    <th class="px-4 py-3 text-left font-medium text-zinc-600 dark:text-zinc-400">Risk Score</th>
                    <th class="px-4 py-3 text-left font-medium text-zinc-600 dark:text-zinc-400">Last Checked</th>
                    <th class="px-4 py-3 text-left font-medium text-zinc-600 dark:text-zinc-400">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-100 dark:divide-zinc-700">
                @forelse ($this->profiles as $profile)
                    <tr wire:key="{{ $profile->id }}" class="bg-white dark:bg-zinc-900">
                        <td class="px-4 py-3">
                            <div class="font-medium">{{ $profile->name }}</div>
                            @if ($profile->proxy_address)
                                <div class="text-xs text-zinc-400 font-mono">{{ $profile->proxy_address }}</div>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <flux:badge color="zinc">{{ $profile->platform->label() }}</flux:badge>
                        </td>
                        <td class="px-4 py-3">
                            <flux:badge color="{{ $profile->status->color() }}">
                                {{ $profile->status->label() }}
                            </flux:badge>
                        </td>
                        <td class="px-4 py-3">
                            @if ($profile->risk_score !== null)
                                <span @class([
                                    'font-mono text-xs px-2 py-0.5 rounded',
                                    'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300' => $profile->risk_score < 30,
                                    'bg-yellow-100 text-yellow-700 dark:bg-yellow-900 dark:text-yellow-300' => $profile->risk_score >= 30 && $profile->risk_score < 60,
                                    'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300' => $profile->risk_score >= 60,
                                ])>{{ $profile->risk_score }}/100</span>
                            @else
                                <span class="text-zinc-400">Not checked</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-zinc-500">
                            {{ $profile->last_score_checked_at?->diffForHumans() ?? '—' }}
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <flux:tooltip content="Check Risk Score">
                                    <flux:button
                                        size="sm"
                                        variant="ghost"
                                        icon="shield-check"
                                        wire:click="checkScore({{ $profile->id }})"
                                        wire:loading.attr="disabled"
                                        :disabled="$checkingScoreForProfileId === $profile->id"
                                    />
                                </flux:tooltip>
                                <flux:tooltip content="Delete Profile">
                                    <flux:button
                                        size="sm"
                                        variant="ghost"
                                        icon="trash"
                                        wire:click="deleteProfile({{ $profile->id }})"
                                        wire:confirm="Permanently delete this GoLogin profile and remove it from GoLogin Cloud?"
                                    />
                                </flux:tooltip>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-12 text-center text-zinc-500">
                            No GoLogin profiles yet. Create one to get started.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Create Profile Modal --}}
    <flux:modal wire:model="showCreateModal" class="max-w-md">
        <div class="space-y-5">
            <div>
                <flux:heading size="lg">New GoLogin Profile</flux:heading>
                <flux:subheading>This will create a new browser profile in GoLogin Cloud.</flux:subheading>
            </div>

            <flux:field>
                <flux:label>Profile Name</flux:label>
                <flux:input wire:model="newName" placeholder="e.g. LinkedIn Bot – Profile 1" />
                <flux:error name="newName" />
            </flux:field>

            <flux:field>
                <flux:label>Platform</flux:label>
                <flux:select wire:model="newPlatform">
                    @foreach ($this->platforms as $platform)
                        <flux:select.option value="{{ $platform->value }}">{{ $platform->label() }}</flux:select.option>
                    @endforeach
                </flux:select>
                <flux:error name="newPlatform" />
            </flux:field>

            <flux:field>
                <flux:label>Proxy Address</flux:label>
                <flux:input wire:model="newProxy" placeholder="192.168.1.1:8080" />
                <flux:description>Optional. Format: host:port</flux:description>
                <flux:error name="newProxy" />
            </flux:field>

            <div class="flex gap-3">
                <flux:button
                    variant="primary"
                    wire:click="createProfile"
                    wire:loading.attr="disabled"
                    class="flex-1"
                >
                    <span wire:loading.remove wire:target="createProfile">Create Profile</span>
                    <span wire:loading wire:target="createProfile">Creating...</span>
                </flux:button>
                <flux:button variant="ghost" wire:click="$set('showCreateModal', false)">
                    Cancel
                </flux:button>
            </div>
        </div>
    </flux:modal>
</div>
