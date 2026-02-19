<?php

namespace App\Filament\Resources\JobPostings\Schemas;

use App\Enums\EmploymentType;
use App\Enums\JobPostingStatus;
use App\Enums\Platform;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class JobPostingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Job Details')
                    ->columns(2)
                    ->schema([
                        TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),

                        TextInput::make('location')
                            ->required()
                            ->maxLength(255),

                        Select::make('employment_type')
                            ->options(EmploymentType::class)
                            ->default(EmploymentType::FullTime)
                            ->required(),

                        Textarea::make('description')
                            ->required()
                            ->rows(8)
                            ->columnSpanFull(),
                    ]),

                Section::make('Compensation')
                    ->columns(3)
                    ->schema([
                        TextInput::make('salary_min')
                            ->numeric()
                            ->minValue(0)
                            ->prefix('$'),

                        TextInput::make('salary_max')
                            ->numeric()
                            ->minValue(0)
                            ->prefix('$'),

                        Select::make('salary_currency')
                            ->options([
                                'USD' => 'USD',
                                'EUR' => 'EUR',
                                'UAH' => 'UAH',
                            ])
                            ->default('USD')
                            ->required(),
                    ]),

                Section::make('Posting Targets')
                    ->schema([
                        CheckboxList::make('platforms')
                            ->options(collect(Platform::cases())->mapWithKeys(
                                fn (Platform $p) => [$p->value => $p->label()]
                            )->toArray())
                            ->required()
                            ->columns(3),
                    ]),

                Section::make('Meta')
                    ->columns(2)
                    ->schema([
                        Select::make('created_by')
                            ->label('Created By')
                            ->relationship('creator', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Select::make('status')
                            ->options(JobPostingStatus::class)
                            ->default(JobPostingStatus::Draft)
                            ->required(),
                    ]),
            ]);
    }
}
