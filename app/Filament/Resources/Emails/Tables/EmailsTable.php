<?php

namespace App\Filament\Resources\Emails\Tables;

use App\Enums\ProxyType;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class EmailsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('email')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->weight('bold'),

                TextColumn::make('geo_region')
                    ->label('Region')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('proxy_type')
                    ->badge()
                    ->color(fn ($state) => $state?->color())
                    ->formatStateUsing(fn ($state) => $state?->label() ?? '—')
                    ->sortable(),

                TextColumn::make('proxy_address')
                    ->label('Proxy Address')
                    ->fontFamily('mono')
                    ->placeholder('—')
                    ->toggleable(),

                IconColumn::make('password')
                    ->label('Has Password')
                    ->boolean()
                    ->getStateUsing(fn ($record) => filled($record->password))
                    ->trueIcon('heroicon-o-lock-closed')
                    ->falseIcon('heroicon-o-lock-open')
                    ->trueColor('success')
                    ->falseColor('gray'),

                TextColumn::make('platform_accounts_count')
                    ->label('Accounts')
                    ->counts('platformAccounts')
                    ->sortable()
                    ->badge()
                    ->color('info'),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->date()
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('proxy_type')
                    ->options(collect(ProxyType::cases())->mapWithKeys(
                        fn (ProxyType $p) => [$p->value => $p->label()]
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
