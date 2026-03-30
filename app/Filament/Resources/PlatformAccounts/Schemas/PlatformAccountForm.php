<?php

namespace App\Filament\Resources\PlatformAccounts\Schemas;

use App\Enums\Platform;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PlatformAccountForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Account Details')
                    ->columns(2)
                    ->schema([
                        Select::make('platform')
                            ->options(collect(Platform::cases())->mapWithKeys(
                                fn (Platform $p) => [$p->value => $p->label()]
                            )->toArray())
                            ->required()
                            ->native(false),

                        TextInput::make('geo_region')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('e.g. United States, Ukraine')
                            ->helperText('Country or region where this account was registered.'),

                        TextInput::make('login')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Login used to sign in'),

                        TextInput::make('password')
                            ->nullable()
                            ->maxLength(255)
                            ->placeholder('Account password'),

                        TextInput::make('username')
                            ->nullable()
                            ->maxLength(255)
                            ->placeholder('Public username / handle'),

                        TextInput::make('first_name')
                            ->nullable()
                            ->maxLength(255)
                            ->placeholder('First name on the account'),
                    ]),

                Section::make('Linked Email')
                    ->schema([
                        Select::make('email_id')
                            ->label('Email')
                            ->relationship('email', 'email')
                            ->searchable()
                            ->preload()
                            ->nullable()
                            ->native(false)
                            ->placeholder('None'),
                    ]),
            ]);
    }
}
