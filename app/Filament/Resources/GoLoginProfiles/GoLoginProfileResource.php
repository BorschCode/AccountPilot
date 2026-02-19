<?php

namespace App\Filament\Resources\GoLoginProfiles;

use App\Filament\Resources\GoLoginProfiles\Pages\CreateGoLoginProfile;
use App\Filament\Resources\GoLoginProfiles\Pages\EditGoLoginProfile;
use App\Filament\Resources\GoLoginProfiles\Pages\ListGoLoginProfiles;
use App\Filament\Resources\GoLoginProfiles\Schemas\GoLoginProfileForm;
use App\Filament\Resources\GoLoginProfiles\Tables\GoLoginProfilesTable;
use App\Models\GoLoginProfile;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class GoLoginProfileResource extends Resource
{
    protected static ?string $model = GoLoginProfile::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedGlobeAlt;

    protected static \UnitEnum|string|null $navigationGroup = 'Job Posting';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'Browser Profiles';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return GoLoginProfileForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return GoLoginProfilesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListGoLoginProfiles::route('/'),
            'create' => CreateGoLoginProfile::route('/create'),
            'edit' => EditGoLoginProfile::route('/{record}/edit'),
        ];
    }
}
