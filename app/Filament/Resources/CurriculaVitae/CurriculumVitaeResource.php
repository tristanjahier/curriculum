<?php

namespace App\Filament\Resources\CurriculaVitae;

use App\Filament\Resources\CurriculaVitae\Pages\CreateCurriculumVitae;
use App\Filament\Resources\CurriculaVitae\Pages\EditCurriculumVitae;
use App\Filament\Resources\CurriculaVitae\Pages\ListCurriculaVitae;
use App\Filament\Resources\CurriculaVitae\Pages\ViewCurriculumVitae;
use App\Filament\Resources\CurriculaVitae\Schemas\CurriculumVitaeForm;
use App\Filament\Resources\CurriculaVitae\Schemas\CurriculumVitaeInfolist;
use App\Filament\Resources\CurriculaVitae\Tables\CurriculaVitaeTable;
use App\Filament\Resources\CurriculaVitae\Widgets\NoDefaultCvAlert;
use App\Models\CurriculumVitae;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CurriculumVitaeResource extends Resource
{
    protected static ?string $model = CurriculumVitae::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::DocumentText;

    protected static ?string $pluralModelLabel = 'curricula vitae';

    protected static ?string $slug = 'curricula-vitae';

    protected static ?string $recordTitleAttribute = 'name';

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'slug', 'headline', 'summary'];
    }

    public static function form(Schema $schema): Schema
    {
        return CurriculumVitaeForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return CurriculumVitaeInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CurriculaVitaeTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ExperiencesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCurriculaVitae::route('/'),
            'create' => CreateCurriculumVitae::route('/create'),
            'view' => ViewCurriculumVitae::route('/{record}'),
            'edit' => EditCurriculumVitae::route('/{record}/edit'),
        ];
    }

    public static function getWidgets(): array
    {
        return [
            NoDefaultCvAlert::class,
        ];
    }
}
