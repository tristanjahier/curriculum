<?php

namespace App\Filament\Resources\Experiences\Schemas;

use App\Models\Experience;
use App\Models\Person;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ExperienceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->maxLength(120)
                    ->required(),

                MarkdownEditor::make('description')
                    ->maxLength(1000)
                    ->columnSpanFull(),

                TextInput::make('company')
                    ->maxLength(80),

                TextInput::make('location')
                    ->maxLength(80),

                DatePicker::make('started_at')
                    ->required(),

                DatePicker::make('ended_at')
                    ->afterOrEqual('started_at'),

                Select::make('person_id')
                    ->required()
                    ->relationship(name: 'person')
                    ->getOptionLabelFromRecordUsing(fn (Person $p) => $p->full_name)
                    ->searchable(['first_name', 'last_name'])->preload()
                    ->disabled(fn (?Experience $record): bool => $record?->curriculaVitae()->exists() ?? false)
                    ->helperText(fn (?Experience $record): ?string => $record?->curriculaVitae()->exists() ?? false
                        ? 'Detach this experience from every CV before moving it to another person.'
                        : null),
            ]);
    }
}
