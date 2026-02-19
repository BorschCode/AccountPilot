<?php

use App\Actions\DispatchJobPostings;
use App\Enums\EmploymentType;
use App\Enums\JobPostingStatus;
use App\Enums\Platform;
use App\Models\JobPosting;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use Livewire\Component;

new class extends Component {
    #[Validate('required|string|max:255')]
    public string $title = '';

    #[Validate('required|string')]
    public string $description = '';

    #[Validate('required|string|max:255')]
    public string $location = '';

    #[Validate('required|string')]
    public string $employmentType = '';

    #[Validate('nullable|integer|min:0')]
    public ?int $salaryMin = null;

    #[Validate('nullable|integer|min:0')]
    public ?int $salaryMax = null;

    #[Validate('required|string|size:3')]
    public string $salaryCurrency = 'USD';

    /** @var string[] */
    #[Validate('required|array|min:1')]
    public array $selectedPlatforms = [];

    public bool $dispatchImmediately = false;

    public function mount(): void
    {
        $this->employmentType = EmploymentType::FullTime->value;
    }

    public function save(): void
    {
        $this->validate();

        $jobPosting = JobPosting::create([
            'created_by' => Auth::id(),
            'title' => $this->title,
            'description' => $this->description,
            'location' => $this->location,
            'employment_type' => $this->employmentType,
            'salary_min' => $this->salaryMin,
            'salary_max' => $this->salaryMax,
            'salary_currency' => $this->salaryCurrency,
            'platforms' => $this->selectedPlatforms,
            'status' => JobPostingStatus::Draft,
        ]);

        if ($this->dispatchImmediately) {
            app(DispatchJobPostings::class)->execute($jobPosting);
        }

        $this->redirect(route('job-postings.show', $jobPosting), navigate: true);
    }

    public function employmentTypes(): array
    {
        return EmploymentType::cases();
    }

    public function platforms(): array
    {
        return Platform::cases();
    }
}; ?>

<div>
    <div class="mb-6">
        <flux:link href="{{ route('job-postings.index') }}" wire:navigate icon="arrow-left" icon:variant="outline">
            Back to Postings
        </flux:link>
        <flux:heading size="xl" class="mt-2">Create Job Posting</flux:heading>
        <flux:subheading>Fill in the details and select which platforms to post to.</flux:subheading>
    </div>

    <form wire:submit="save" class="max-w-2xl space-y-6">

        <flux:field>
            <flux:label>Job Title</flux:label>
            <flux:input wire:model="title" placeholder="e.g. Senior PHP Developer" />
            <flux:error name="title" />
        </flux:field>

        <flux:field>
            <flux:label>Location</flux:label>
            <flux:input wire:model="location" placeholder="e.g. Kyiv, Ukraine (Remote)" />
            <flux:error name="location" />
        </flux:field>

        <flux:field>
            <flux:label>Employment Type</flux:label>
            <flux:select wire:model="employmentType">
                @foreach ($this->employmentTypes() as $type)
                    <flux:select.option value="{{ $type->value }}">{{ $type->label() }}</flux:select.option>
                @endforeach
            </flux:select>
            <flux:error name="employmentType" />
        </flux:field>

        <div class="grid grid-cols-3 gap-3">
            <flux:field>
                <flux:label>Salary Min</flux:label>
                <flux:input wire:model="salaryMin" type="number" min="0" placeholder="30000" />
                <flux:error name="salaryMin" />
            </flux:field>
            <flux:field>
                <flux:label>Salary Max</flux:label>
                <flux:input wire:model="salaryMax" type="number" min="0" placeholder="80000" />
                <flux:error name="salaryMax" />
            </flux:field>
            <flux:field>
                <flux:label>Currency</flux:label>
                <flux:select wire:model="salaryCurrency">
                    <flux:select.option value="USD">USD</flux:select.option>
                    <flux:select.option value="EUR">EUR</flux:select.option>
                    <flux:select.option value="UAH">UAH</flux:select.option>
                </flux:select>
                <flux:error name="salaryCurrency" />
            </flux:field>
        </div>

        <flux:field>
            <flux:label>Description</flux:label>
            <flux:textarea wire:model="description" rows="8" placeholder="Describe the role, responsibilities, requirements..." />
            <flux:error name="description" />
        </flux:field>

        <flux:fieldset>
            <flux:legend>Platforms</flux:legend>
            <flux:description>Select which platforms this job should be posted to.</flux:description>
            <div class="grid grid-cols-2 gap-2 mt-2">
                @foreach ($this->platforms() as $platform)
                    <flux:checkbox
                        wire:model="selectedPlatforms"
                        value="{{ $platform->value }}"
                        :label="$platform->label()"
                    />
                @endforeach
            </div>
            <flux:error name="selectedPlatforms" />
        </flux:fieldset>

        <flux:separator />

        <div class="flex items-center gap-4">
            <flux:button type="submit" variant="primary" wire:loading.attr="disabled">
                <span wire:loading.remove>Save as Draft</span>
                <span wire:loading>Saving...</span>
            </flux:button>

            <flux:button
                type="button"
                variant="filled"
                wire:click="$set('dispatchImmediately', true)"
                wire:then="save"
                wire:loading.attr="disabled"
            >
                Save & Post Now
            </flux:button>

            <flux:button variant="ghost" href="{{ route('job-postings.index') }}" wire:navigate>
                Cancel
            </flux:button>
        </div>
    </form>
</div>
