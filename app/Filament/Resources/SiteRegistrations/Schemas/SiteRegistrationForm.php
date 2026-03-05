<?php

namespace App\Filament\Resources\SiteRegistrations\Schemas;

use App\Enums\SiteRegistrationStatus;
use App\Models\Email;
use App\Models\GoLoginProfile;
use App\Models\SiteTemplate;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SiteRegistrationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Registration Target')
                    ->columns(2)
                    ->schema([
                        Select::make('site_template_id')
                            ->label('Site Template')
                            ->options(SiteTemplate::query()->pluck('name', 'id'))
                            ->searchable()
                            ->preload()
                            ->required()
                            ->columnSpanFull(),

                        Select::make('email_id')
                            ->label('Email Account')
                            ->options(Email::query()->pluck('email', 'id'))
                            ->searchable()
                            ->preload()
                            ->required(),

                        Select::make('go_login_profile_id')
                            ->label('Browser Profile')
                            ->options(GoLoginProfile::query()->pluck('name', 'id'))
                            ->searchable()
                            ->preload()
                            ->nullable()
                            ->placeholder('Auto-select'),
                    ]),

                Section::make('Registration Data')
                    ->columns(2)
                    ->schema([
                        TextInput::make('first_name')
                            ->maxLength(255),

                        TextInput::make('last_name')
                            ->maxLength(255),

                        TextInput::make('username')
                            ->maxLength(255),

                        TextInput::make('phone_number')
                            ->tel()
                            ->maxLength(50),

                        TextInput::make('password')
                            ->password()
                            ->revealable()
                            ->maxLength(255)
                            ->helperText('Password to set for the new account.')
                            ->columnSpanFull(),
                    ]),

                Section::make('Meta')
                    ->schema([
                        Select::make('status')
                            ->options(SiteRegistrationStatus::class)
                            ->default(SiteRegistrationStatus::Draft)
                            ->required(),
                    ]),
            ]);
    }
}
