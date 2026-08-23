<?php

namespace App\Filament\Resources\People\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PersonForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('first_name')
                    ->required()
                    ->maxLength(80),

                TextInput::make('last_name')
                    ->required()
                    ->maxLength(80),

                DateTimePicker::make('birth_datetime')
                    // Have Filament render the date in the same timezone it was parsed with,
                    // to ensure that we preserve the plain date and time values as-is.
                    ->timezone(date_default_timezone_get())
                    ->required(),

                Select::make('birth_timezone')
                    ->required()
                    ->options(array_combine(timezone_identifiers_list(), timezone_identifiers_list()))
                    ->searchable(),

                TextInput::make('residence')
                    ->maxLength(100),

                TextInput::make('phone')
                    ->tel()
                    ->maxLength(50),

                TextInput::make('email')
                    ->email()
                    ->maxLength(100),
            ]);
    }
}
