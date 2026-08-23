<?php

namespace App\Filament\Resources\People\Tables;

use App\Filament\Tables\Columns\StackColumn;
use App\Models\Person;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class PeopleTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('full_name')
                    ->searchable(['first_name', 'last_name'])
                    ->limit(100),

                TextColumn::make('born_at')
                    ->formatStateUsing(function (mixed $state, Person $record, Table $table) {
                        return Carbon::make($state)->translatedFormat($table->getDefaultDateTimeDisplayFormat())
                            ." ({$record->birth_timezone})";
                    })
                    ->sortable(
                        query: function (Builder $query, string $direction): Builder {
                            return $query->orderByRaw('birth_datetime AT TIME ZONE birth_timezone '.
                                ($direction === 'asc' ? 'asc' : 'desc'));
                        }
                    )
                    ->searchable(['birth_datetime', 'birth_timezone']),

                TextColumn::make('residence')
                    ->searchable()
                    ->limit(30)
                    ->placeholder('∅'),

                StackColumn::make('contact_details')
                    ->label('Contact Details')
                    ->stack([
                        TextColumn::make('phone')
                            ->limit(30)
                            ->icon(Heroicon::Phone)
                            ->placeholder('∅'),

                        TextColumn::make('email')
                            ->limit(30)
                            ->icon(Heroicon::Envelope)
                            ->placeholder('∅'),
                    ])
                    ->searchable(['phone', 'email']),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                ActionGroup::make([
                    DeleteAction::make(),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->stackedOnMobile();
    }
}
