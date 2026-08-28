<?php

namespace App\Filament\Resources\CurriculaVitae\Actions;

use App\Models\CurriculumVitae;
use Filament\Actions\Action;
use Filament\Support\Colors\Color;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\HtmlString;

class PublishAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'publish';
    }

    public function setUp(): void
    {
        parent::setUp();

        $this->hidden(fn (CurriculumVitae $record) => $record->isPublished());

        $this->icon(Heroicon::Signal);
        $this->modalIcon(Heroicon::Signal);
        $this->color(Color::Emerald);

        $this->requiresConfirmation();
        $this->modalHeading('Publish this curriculum vitae?');
        $this->modalDescription(new HtmlString('Are you sure you would like to publish this CV?<br>It will be publicly accessible to anyone on the Internet.'));
        $this->modalSubmitAction(fn (Action $action) => $action->keyBindings(['enter']));

        $this->action(fn (CurriculumVitae $record) => $record->publish());

        $this->successNotificationTitle('CV published');
    }
}
