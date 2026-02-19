<?php

namespace App\Filament\Resources\JobPostings\Tables;

use App\Actions\DispatchJobPostings;
use App\Enums\JobPostingStatus;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class JobPostingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('creator.name')
                    ->label('Created By')
                    ->sortable(),

                TextColumn::make('location')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('employment_type')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state->label()),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn ($state) => $state->color())
                    ->formatStateUsing(fn ($state) => $state->label()),

                TextColumn::make('platformPosts_count')
                    ->label('Posts')
                    ->counts('platformPosts')
                    ->sortable(),

                TextColumn::make('posted_at')
                    ->since()
                    ->sortable()
                    ->placeholder('—'),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(JobPostingStatus::class),

                TrashedFilter::make(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                Action::make('dispatch')
                    ->label('Post Now')
                    ->icon('heroicon-o-paper-airplane')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn ($record) => in_array($record->status, [
                        JobPostingStatus::Draft,
                        JobPostingStatus::Failed,
                        JobPostingStatus::PartiallyPosted,
                    ]))
                    ->action(fn ($record) => app(DispatchJobPostings::class)->execute($record)),
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
