<?php

namespace App\Filament\Resources\SiteTemplates\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SiteTemplateForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Site Details')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),

                        TextInput::make('site_url')
                            ->label('Registration URL')
                            ->url()
                            ->required()
                            ->maxLength(2048)
                            ->columnSpanFull()
                            ->placeholder('https://example.com/signup'),

                        Textarea::make('description')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),

                Section::make('Email Confirmation')
                    ->columns(2)
                    ->schema([
                        Toggle::make('expects_email_confirmation')
                            ->label('Requires email confirmation')
                            ->helperText('Enable if the site sends a confirmation email after registration.')
                            ->live()
                            ->columnSpanFull(),

                        TextInput::make('confirmation_timeout')
                            ->label('Confirmation timeout (seconds)')
                            ->numeric()
                            ->minValue(30)
                            ->maxValue(3600)
                            ->default(300)
                            ->helperText('How long to wait for the confirmation email before switching to manual mode.')
                            ->visible(fn ($get) => $get('expects_email_confirmation')),
                    ]),

                Section::make('Notes')
                    ->schema([
                        Textarea::make('notes')
                            ->rows(3)
                            ->hiddenLabel(),
                    ]),
            ]);
    }
}
