<?php

namespace App\Filament\Resources\CurriculaVitae\RelationManagers;

use App\Filament\Resources\Education\EducationResource;
use App\Models\CurriculumVitae;
use Filament\Actions\AttachAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DetachAction;
use Filament\Actions\DetachBulkAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class EducationRelationManager extends RelationManager
{
    protected static string $relationship = 'education';

    protected static ?string $relatedResource = EducationResource::class;

    protected static bool $isLazy = false;

    public function table(Table $table): Table
    {
        return $table
            ->inverseRelationship('curriculaVitae')
            ->headerActions([
                AttachAction::make()
                    ->recordSelectSearchColumns(['title', 'description', 'institution', 'location'])
                    ->recordSelectOptionsQuery(function (Builder $query) {
                        /** @var CurriculumVitae */
                        $cv = $this->getOwnerRecord();
                        $query->whereBelongsTo($cv->person, 'person');
                    })
                    ->preloadRecordSelect()
                    ->icon(Heroicon::Plus)
                    ->color('primary')
                    ->label('Add an education entry'),
            ])
            ->recordActions([
                DetachAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DetachBulkAction::make(),
                ]),
            ]);
    }
}
