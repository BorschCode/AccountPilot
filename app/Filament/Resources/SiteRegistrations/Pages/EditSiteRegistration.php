<?php

namespace App\Filament\Resources\SiteRegistrations\Pages;

use App\Filament\Resources\SiteRegistrations\SiteRegistrationResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditSiteRegistration extends EditRecord
{
    protected static string $resource = SiteRegistrationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
