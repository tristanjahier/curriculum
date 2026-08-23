<?php

namespace App\Filament\Resources\CurriculaVitae\Actions;

use App\Models\CurriculumVitae;
use Filament\Actions\Action;
use Filament\Support\Colors\Color;
use Filament\Support\Icons\Heroicon;
use Livewire\Component;

class SetAsDefaultAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'setAsDefault';
    }

    public function setUp(): void
    {
        parent::setUp();

        $this->hidden(fn (CurriculumVitae $record) => $record->is_default);
        $this->icon(Heroicon::BookmarkSquare);
        $this->color(Color::Violet);
        $this->requiresConfirmation();
        $this->successNotificationTitle('Default CV updated');
        $this->modalIcon(Heroicon::BookmarkSquare);
        $this->modalSubmitAction(fn (Action $action) => $action->keyBindings(['enter']));

        $this->action(fn (CurriculumVitae $record) => $record->setAsDefault());

        $this->after(fn (Component $livewire) => $livewire->dispatch('default-cv-updated', defaultIsSet: true));
    }
}
