<?php

namespace App\Filament\Resources\PlatformAccounts\Pages;

use App\Filament\Resources\PlatformAccounts\PlatformAccountResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPlatformAccount extends EditRecord
{
    protected static string $resource = PlatformAccountResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
