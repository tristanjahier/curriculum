<?php

namespace App\Filament\Resources\CurriculaVitae\Schemas;

use App\Models\Person;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class CurriculumVitaeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(80)
                    ->unique(ignoreRecord: true)
                    ->live(condition: fn (string $operation) => $operation === 'create', debounce: 300)
                    ->afterStateUpdated(function (Set $set, ?string $state, string $operation) {
                        if ($operation === 'create') {
                            $set('slug', Str::slug($state));
                        }
                    }),

                TextInput::make('slug')
                    ->required()
                    ->maxLength(80)
                    ->unique(ignoreRecord: true)
                    ->rules(['alpha_dash:ascii']),

                Select::make('person_id')
                    ->required()
                    ->relationship(name: 'person')
                    ->getOptionLabelFromRecordUsing(fn (Person $p) => $p->full_name)
                    ->searchable(['first_name', 'last_name'])->preload(),

                TextInput::make('headline')
                    ->maxLength(100),

                Textarea::make('summary')
                    ->columnSpanFull()
                    ->maxLength(500),

                Toggle::make('show_photo'),
                Toggle::make('show_age'),
                Toggle::make('show_residence'),
                Toggle::make('show_phone'),
                Toggle::make('show_email'),

                DateTimePicker::make('published_at'),
            ]);
    }
}
