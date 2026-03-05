<?php

namespace App\Filament\Resources\SiteRegistrations;

use App\Filament\Resources\SiteRegistrations\Pages\CreateSiteRegistration;
use App\Filament\Resources\SiteRegistrations\Pages\EditSiteRegistration;
use App\Filament\Resources\SiteRegistrations\Pages\ListSiteRegistrations;
use App\Filament\Resources\SiteRegistrations\Pages\ViewSiteRegistration;
use App\Filament\Resources\SiteRegistrations\Schemas\SiteRegistrationForm;
use App\Filament\Resources\SiteRegistrations\Tables\SiteRegistrationsTable;
use App\Models\SiteRegistration;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SiteRegistrationResource extends Resource
{
    protected static ?string $model = SiteRegistration::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserPlus;

    protected static \UnitEnum|string|null $navigationGroup = 'Registrations';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'Registration Jobs';

    public static function form(Schema $schema): Schema
    {
        return SiteRegistrationForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SiteRegistrationsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSiteRegistrations::route('/'),
            'create' => CreateSiteRegistration::route('/create'),
            'view' => ViewSiteRegistration::route('/{record}'),
            'edit' => EditSiteRegistration::route('/{record}/edit'),
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
