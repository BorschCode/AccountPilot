<?php

namespace App\Filament\Resources\SiteTemplates\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class SiteTemplatesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('site_url')
                    ->label('URL')
                    ->searchable()
                    ->limit(50)
                    ->url(fn ($record) => $record->site_url, true),

                IconColumn::make('expects_email_confirmation')
                    ->label('Email Confirm')
                    ->boolean(),

                TextColumn::make('confirmation_timeout')
                    ->label('Timeout')
                    ->formatStateUsing(fn ($state) => $state.'s')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('siteRegistrations_count')
                    ->label('Registrations')
                    ->counts('siteRegistrations')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->since()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
