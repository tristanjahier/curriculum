<?php

namespace App\Filament\Resources\CurriculaVitae\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class CurriculumVitaeInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name'),

                TextEntry::make('slug'),

                TextEntry::make('person.full_name')->label('Person'),

                TextEntry::make('headline')
                    ->placeholder('∅'),

                TextEntry::make('summary')
                    ->placeholder('∅')
                    ->columnSpanFull(),

                IconEntry::make('show_photo')
                    ->boolean(),

                IconEntry::make('show_age')
                    ->boolean(),

                IconEntry::make('show_residence')
                    ->boolean(),

                IconEntry::make('show_phone')
                    ->boolean(),

                IconEntry::make('show_email')
                    ->boolean(),

                IconEntry::make('is_default')
                    ->boolean(),

                TextEntry::make('published_at')
                    ->dateTime()
                    ->placeholder('Unpublished'),

                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('∅'),

                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('∅'),
            ]);
    }
}
