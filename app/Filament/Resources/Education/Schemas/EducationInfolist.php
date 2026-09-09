<?php

namespace App\Filament\Resources\Education\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class EducationInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('title'),

                TextEntry::make('description')
                    ->placeholder('∅')
                    ->columnSpanFull(),

                TextEntry::make('institution')
                    ->placeholder('∅'),

                TextEntry::make('location')
                    ->placeholder('∅'),

                TextEntry::make('started_at')
                    ->date(),

                TextEntry::make('ended_at')
                    ->date()
                    ->placeholder('Ongoing'),

                TextEntry::make('person.full_name')
                    ->label('Person'),

                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('∅'),

                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('∅'),
            ]);
    }
}
