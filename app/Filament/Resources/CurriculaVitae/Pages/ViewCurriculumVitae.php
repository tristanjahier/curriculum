<?php

namespace App\Filament\Resources\CurriculaVitae\Pages;

use App\Filament\Resources\CurriculaVitae\Actions\PublishAction;
use App\Filament\Resources\CurriculaVitae\Actions\RemoveAsDefaultAction;
use App\Filament\Resources\CurriculaVitae\Actions\SetAsDefaultAction;
use App\Filament\Resources\CurriculaVitae\Actions\UnpublishAction;
use App\Filament\Resources\CurriculaVitae\CurriculumVitaeResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewCurriculumVitae extends ViewRecord
{
    protected static string $resource = CurriculumVitaeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
            SetAsDefaultAction::make(),
            RemoveAsDefaultAction::make(),
            PublishAction::make(),
            UnpublishAction::make(),
        ];
    }
}
