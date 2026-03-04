<?php

namespace App\Filament\Resources\Emails\Schemas;

use App\Enums\ProxyType;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class EmailForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Email Details')
                    ->columns(2)
                    ->schema([
                        TextInput::make('email')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->columnSpanFull(),

                        TextInput::make('password')
                            ->password()
                            ->revealable()
                            ->maxLength(255)
                            ->helperText('Optional. Store the mailbox password.'),

                        TextInput::make('geo_region')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('e.g. United States, Ukraine')
                            ->helperText('Country or region where this email was created.'),
                    ]),

                Section::make('Proxy')
                    ->columns(2)
                    ->schema([
                        Select::make('proxy_type')
                            ->options(collect(ProxyType::cases())->mapWithKeys(
                                fn (ProxyType $p) => [$p->value => $p->label()]
                            )->toArray())
                            ->nullable()
                            ->native(false)
                            ->placeholder('None'),

                        TextInput::make('proxy_address')
                            ->placeholder('192.168.1.1:8080')
                            ->helperText('Optional. Format: host:port'),
                    ]),
            ]);
    }
}
