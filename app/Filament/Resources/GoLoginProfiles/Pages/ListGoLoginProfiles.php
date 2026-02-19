<?php

namespace App\Filament\Resources\GoLoginProfiles\Pages;

use App\Filament\Resources\GoLoginProfiles\GoLoginProfileResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListGoLoginProfiles extends ListRecords
{
    protected static string $resource = GoLoginProfileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
