<?php

namespace App\Filament\Resources\JobPostings;

use App\Filament\Resources\JobPostings\Pages\CreateJobPosting;
use App\Filament\Resources\JobPostings\Pages\EditJobPosting;
use App\Filament\Resources\JobPostings\Pages\ListJobPostings;
use App\Filament\Resources\JobPostings\Pages\ViewJobPosting;
use App\Filament\Resources\JobPostings\Schemas\JobPostingForm;
use App\Filament\Resources\JobPostings\Tables\JobPostingsTable;
use App\Models\JobPosting;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class JobPostingResource extends Resource
{
    protected static ?string $model = JobPosting::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBriefcase;

    protected static \UnitEnum|string|null $navigationGroup = 'Job Posting';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return JobPostingForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return JobPostingsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListJobPostings::route('/'),
            'create' => CreateJobPosting::route('/create'),
            'view' => ViewJobPosting::route('/{record}'),
            'edit' => EditJobPosting::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
