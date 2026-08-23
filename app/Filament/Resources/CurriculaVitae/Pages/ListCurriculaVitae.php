<?php

namespace App\Filament\Resources\CurriculaVitae\Pages;

use App\Filament\Resources\CurriculaVitae\CurriculumVitaeResource;
use App\Filament\Resources\CurriculaVitae\Widgets\NoDefaultCvAlert;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Icons\Heroicon;

class ListCurriculaVitae extends ListRecords
{
    protected static string $resource = CurriculumVitaeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->icon(Heroicon::Plus),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            NoDefaultCvAlert::class,
        ];
    }
}
