<?php

namespace App\Filament\Resources\CurriculaVitae\Actions;

use App\Models\CurriculumVitae;
use Filament\Actions\Action;
use Filament\Support\Colors\Color;
use Filament\Support\Icons\Heroicon;
use Livewire\Component;

class RemoveAsDefaultAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'removeAsDefault';
    }

    public function setUp(): void
    {
        parent::setUp();

        $this->hidden(fn (CurriculumVitae $record) => ! $record->is_default);
        $this->icon(Heroicon::BookmarkSlash);
        $this->color(Color::Neutral);
        $this->requiresConfirmation();
        $this->successNotificationTitle('Default CV cleared');
        $this->modalIcon(Heroicon::BookmarkSlash);
        $this->modalSubmitAction(fn (Action $action) => $action->keyBindings(['enter']));

        $this->action(fn (CurriculumVitae $record) => $record->removeAsDefault());

        $this->after(fn (Component $livewire) => $livewire->dispatch('default-cv-updated', defaultIsSet: false));
    }
}
