<?php

namespace App\Filament\Resources\SiteRegistrations\Pages;

use App\Filament\Resources\SiteRegistrations\SiteRegistrationResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSiteRegistrations extends ListRecords
{
    protected static string $resource = SiteRegistrationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
