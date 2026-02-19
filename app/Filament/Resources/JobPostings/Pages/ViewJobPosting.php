<?php

namespace App\Filament\Resources\JobPostings\Pages;

use App\Actions\DispatchJobPostings;
use App\Enums\JobPostingStatus;
use App\Filament\Resources\JobPostings\JobPostingResource;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewJobPosting extends ViewRecord
{
    protected static string $resource = JobPostingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('dispatch')
                ->label('Post Now')
                ->icon('heroicon-o-paper-airplane')
                ->color('success')
                ->requiresConfirmation()
                ->visible(fn () => in_array($this->record->status, [
                    JobPostingStatus::Draft,
                    JobPostingStatus::Failed,
                    JobPostingStatus::PartiallyPosted,
                ]))
                ->action(function () {
                    app(DispatchJobPostings::class)->execute($this->record);
                    $this->refreshFormData(['status', 'queued_at']);
                }),

            EditAction::make(),
        ];
    }
}
