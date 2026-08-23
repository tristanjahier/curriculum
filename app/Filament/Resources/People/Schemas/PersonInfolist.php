<?php

namespace App\Filament\Resources\People\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class PersonInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('first_name'),

                TextEntry::make('last_name'),

                TextEntry::make('birth_datetime')
                    // Have Filament render the date in the same timezone it was parsed with,
                    // to ensure that we preserve the plain date and time values as-is.
                    ->dateTime(timezone: date_default_timezone_get()),

                TextEntry::make('birth_timezone'),

                TextEntry::make('residence')
                    ->placeholder('∅'),

                TextEntry::make('phone')
                    ->placeholder('∅'),

                TextEntry::make('email')
                    ->placeholder('∅'),

                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('∅'),

                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('∅'),
            ]);
    }
}
