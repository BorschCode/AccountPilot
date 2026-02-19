<?php

namespace App\Filament\Resources\GoLoginProfiles\Tables;

use App\Enums\GoLoginProfileStatus;
use App\Enums\Platform;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class GoLoginProfilesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('platform')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state->label())
                    ->sortable(),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn ($state) => $state->color())
                    ->formatStateUsing(fn ($state) => $state->label())
                    ->sortable(),

                TextColumn::make('risk_score')
                    ->label('Risk Score')
                    ->sortable()
                    ->color(fn ($state) => match (true) {
                        $state === null => 'gray',
                        $state < 30 => 'success',
                        $state < 60 => 'warning',
                        default => 'danger',
                    })
                    ->formatStateUsing(fn ($state) => $state !== null ? $state.'/100' : '—'),

                TextColumn::make('proxy_address')
                    ->label('Proxy')
                    ->fontFamily('mono')
                    ->placeholder('—')
                    ->toggleable(),

                TextColumn::make('last_score_checked_at')
                    ->label('Score Checked')
                    ->since()
                    ->placeholder('Never')
                    ->sortable(),

                TextColumn::make('last_used_at')
                    ->label('Last Used')
                    ->since()
                    ->placeholder('Never')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('platform')
                    ->options(collect(Platform::cases())->mapWithKeys(
                        fn (Platform $p) => [$p->value => $p->label()]
                    )->toArray()),

                SelectFilter::make('status')
                    ->options(collect(GoLoginProfileStatus::cases())->mapWithKeys(
                        fn (GoLoginProfileStatus $s) => [$s->value => $s->label()]
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
            ->defaultSort('platform');
    }
}
