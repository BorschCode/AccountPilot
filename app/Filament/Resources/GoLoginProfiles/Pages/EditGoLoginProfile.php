<?php

namespace App\Filament\Resources\GoLoginProfiles\Pages;

use App\Filament\Resources\GoLoginProfiles\GoLoginProfileResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditGoLoginProfile extends EditRecord
{
    protected static string $resource = GoLoginProfileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
