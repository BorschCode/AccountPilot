<?php

namespace App\Filament\Resources\PlatformAccounts\Tables;

use App\Enums\Platform;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PlatformAccountsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('platform')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state->label())
                    ->sortable(),

                TextColumn::make('login')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('username')
                    ->searchable()
                    ->placeholder('—')
                    ->toggleable(),

                TextColumn::make('first_name')
                    ->label('First Name')
                    ->searchable()
                    ->placeholder('—')
                    ->toggleable(),

                TextColumn::make('geo_region')
                    ->label('Region')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('email.email')
                    ->label('Email')
                    ->searchable()
                    ->placeholder('—')
                    ->url(fn ($record) => $record->email_id
                        ? route('filament.admin.resources.emails.edit', $record->email_id)
                        : null
                    )
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->date()
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('platform')
                    ->options(collect(Platform::cases())->mapWithKeys(
                        fn (Platform $p) => [$p->value => $p->label()]
                    )->toArray()),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
