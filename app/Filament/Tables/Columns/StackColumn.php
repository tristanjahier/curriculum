<?php

namespace App\Filament\Tables\Columns;

use Filament\Support\Components\Contracts\HasEmbeddedView;
use Filament\Support\Enums\Alignment;
use Filament\Tables\Columns\Column;
use Filament\Tables\Columns\Concerns\HasSpace;
use Illuminate\Contracts\Support\Htmlable;

/**
 * A component halfway between a standard Column and a Stack.
 *
 * It enables stacking multiple columns together without switching the whole table layout,
 * thus keeping column headers and other capabilities like `sortable`, `searchable`, `toggleable`, etc.
 */
class StackColumn extends Column implements HasEmbeddedView
{
    use HasSpace;

    /**
     * @var array<Column>
     */
    protected array $stack = [];

    /**
     * @param  array<Column>  $columns
     */
    public function stack(array $columns): static
    {
        $this->stack = array_values($columns);

        /*
         * Collapse/merge the padding *between* the stacked columns: every column but the first drops its top padding,
         * so two neighbours are separated by a single padding instead of two, while keeping the outer padding
         * (before the first column and after the last).
         *
         * Doing it on the stack element instead would lose the responsive behaviour,
         * as Filament would drop that padding below `sm` on a table using `stackedOnMobile()`.
         *
         * `inline()` is not an option here: `.fi-ta-table-stacked-on-mobile .fi-ta-text` takes over
         * `.fi-ta-text:not(.fi-inline)` and re-applies the padding anyway.
         */
        foreach (array_slice($this->stack, 1) as $column) {
            $column->extraAttributes(['style' => 'padding-top: 0;'], merge: true);
        }

        return $this;
    }

    public function getLabel(): string|Htmlable
    {
        $label = $this->evaluate($this->label)
            ?? collect($this->stack)->map->getLabel()->join(', ', ' & ');

        return $this->shouldTranslateLabel ? __($label) : $label;
    }

    public function toEmbeddedHtml(): string
    {
        $alignment = $this->getAlignment() ?? Alignment::Start;

        if (! $alignment instanceof Alignment) {
            $alignment = filled($alignment) ? (Alignment::tryFrom($alignment) ?? $alignment) : null;
        }

        $attributes = $this->getExtraAttributeBag()
            ->class([
                'fi-ta-stack',
                ($alignment instanceof Alignment) ? "fi-align-{$alignment->value}" : $alignment,
                match ($space = $this->getSpace()) {
                    1 => 'fi-gap-sm',
                    2 => 'fi-gap-md',
                    3 => 'fi-gap-lg',
                    default => $space,
                },
            ]);

        $table = $this->getTable();
        $record = $this->getRecord();
        $recordKey = $this->getRecordKey();
        $rowLoop = $this->getRowLoop();

        ob_start(); ?>

        <div <?= $attributes->toHtml() ?>>
            <?php foreach ($this->stack as $column) { ?>
                <?= $column->table($table)->record($record)->recordKey($recordKey)->rowLoop($rowLoop)->renderInLayout() ?>
            <?php } ?>
        </div>

        <?php $html = ob_get_clean();

        return $html === false ? '' : $html;
    }
}
