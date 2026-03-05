<?php

namespace App\Filament\Resources\SiteTemplates;

use App\Filament\Resources\SiteTemplates\Pages\CreateSiteTemplate;
use App\Filament\Resources\SiteTemplates\Pages\EditSiteTemplate;
use App\Filament\Resources\SiteTemplates\Pages\ListSiteTemplates;
use App\Filament\Resources\SiteTemplates\Schemas\SiteTemplateForm;
use App\Filament\Resources\SiteTemplates\Tables\SiteTemplatesTable;
use App\Models\SiteTemplate;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SiteTemplateResource extends Resource
{
    protected static ?string $model = SiteTemplate::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedGlobeAlt;

    protected static \UnitEnum|string|null $navigationGroup = 'Registrations';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'Site Templates';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return SiteTemplateForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SiteTemplatesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSiteTemplates::route('/'),
            'create' => CreateSiteTemplate::route('/create'),
            'edit' => EditSiteTemplate::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
