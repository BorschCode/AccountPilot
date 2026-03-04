<?php

namespace App\Filament\Resources\PlatformAccounts;

use App\Filament\Resources\PlatformAccounts\Pages\CreatePlatformAccount;
use App\Filament\Resources\PlatformAccounts\Pages\EditPlatformAccount;
use App\Filament\Resources\PlatformAccounts\Pages\ListPlatformAccounts;
use App\Filament\Resources\PlatformAccounts\Schemas\PlatformAccountForm;
use App\Filament\Resources\PlatformAccounts\Tables\PlatformAccountsTable;
use App\Models\PlatformAccount;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PlatformAccountResource extends Resource
{
    protected static ?string $model = PlatformAccount::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedIdentification;

    protected static \UnitEnum|string|null $navigationGroup = 'Accounts';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'Platform Accounts';

    protected static ?string $recordTitleAttribute = 'login';

    public static function form(Schema $schema): Schema
    {
        return PlatformAccountForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PlatformAccountsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPlatformAccounts::route('/'),
            'create' => CreatePlatformAccount::route('/create'),
            'edit' => EditPlatformAccount::route('/{record}/edit'),
        ];
    }
}
