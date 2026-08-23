<?php

namespace App\Filament\Resources\CurriculaVitae\Widgets;

use App\Models\CurriculumVitae;
use Filament\Widgets\Widget;

class NoDefaultCvAlert extends Widget
{
    protected string $view = 'filament.resources.curricula-vitae.widgets.no-default-cv-alert';

    protected int|string|array $columnSpan = 'full';

    protected static bool $isLazy = false;

    public function defaultCvIsUndefined(): bool
    {
        return CurriculumVitae::query()->default()->doesntExist();
    }
}
