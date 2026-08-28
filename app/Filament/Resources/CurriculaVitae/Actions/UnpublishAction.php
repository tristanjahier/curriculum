<?php

namespace App\Filament\Resources\CurriculaVitae\Actions;

use App\Models\CurriculumVitae;
use Filament\Actions\Action;
use Filament\Support\Colors\Color;
use Filament\Support\Icons\Heroicon;
use Livewire\Component;

class UnpublishAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'unpublish';
    }

    public function setUp(): void
    {
        parent::setUp();

        $this->visible(fn (CurriculumVitae $record) => $record->isPublished());

        $this->icon(Heroicon::SignalSlash);
        $this->modalIcon(Heroicon::SignalSlash);
        $this->color(Color::Neutral);

        $this->requiresConfirmation();
        $this->modalHeading('Unpublish this curriculum vitae?');
        $this->modalSubmitAction(fn (Action $action) => $action->keyBindings(['enter']));
        $this->modalContentFooter(fn (CurriculumVitae $record) => $record->is_default
            ? view('filament.resources.curricula-vitae.actions.default-cv-warning')
            : null);

        $this->action(fn (CurriculumVitae $record) => $record->unpublish());

        $this->successNotificationTitle('CV unpublished');

        $this->after(fn (Component $livewire) => $livewire->dispatch(
            'default-cv-updated',
            defaultIsSet: CurriculumVitae::query()->default()->exists(),
        ));
    }
}
