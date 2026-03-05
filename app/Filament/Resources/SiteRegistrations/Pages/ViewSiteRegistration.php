<?php

namespace App\Filament\Resources\SiteRegistrations\Pages;

use App\Actions\DispatchSiteRegistration;
use App\Enums\SiteRegistrationStatus;
use App\Filament\Resources\SiteRegistrations\SiteRegistrationResource;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewSiteRegistration extends ViewRecord
{
    protected static string $resource = SiteRegistrationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('dispatch')
                ->label('Run Now')
                ->icon('heroicon-o-play')
                ->color('success')
                ->requiresConfirmation()
                ->visible(fn () => in_array($this->record->status, [
                    SiteRegistrationStatus::Draft,
                    SiteRegistrationStatus::Failed,
                ]))
                ->action(function () {
                    app(DispatchSiteRegistration::class)->execute($this->record);
                    $this->refreshFormData(['status', 'queued_at']);
                }),

            EditAction::make(),
        ];
    }
}
