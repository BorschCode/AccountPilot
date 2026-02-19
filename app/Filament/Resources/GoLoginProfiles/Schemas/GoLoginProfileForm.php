<?php

namespace App\Filament\Resources\GoLoginProfiles\Schemas;

use App\Enums\GoLoginProfileStatus;
use App\Enums\Platform;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class GoLoginProfileForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Profile Details')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('e.g. LinkedIn Bot – Profile 1')
                            ->columnSpanFull(),

                        Select::make('platform')
                            ->options(collect(Platform::cases())->mapWithKeys(
                                fn (Platform $p) => [$p->value => $p->label()]
                            )->toArray())
                            ->required()
                            ->native(false),

                        Select::make('status')
                            ->options(collect(GoLoginProfileStatus::cases())->mapWithKeys(
                                fn (GoLoginProfileStatus $s) => [$s->value => $s->label()]
                            )->toArray())
                            ->default(GoLoginProfileStatus::Active->value)
                            ->required()
                            ->native(false),

                        TextInput::make('proxy_address')
                            ->placeholder('192.168.1.1:8080')
                            ->helperText('Optional. Format: host:port'),

                        TextInput::make('gologin_profile_id')
                            ->label('GoLogin Profile ID')
                            ->helperText('Auto-populated when creating via GoLogin API. Fill manually if profile already exists.')
                            ->maxLength(255),
                    ]),

                Section::make('Stats')
                    ->schema([
                        TextInput::make('risk_score')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100)
                            ->suffix('/100')
                            ->helperText('0 = clean, 100 = heavily flagged. Updated automatically when checked.'),
                    ]),
            ]);
    }
}
