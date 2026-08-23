<?php

namespace App\Filament\Resources\CurriculaVitae\Tables;

use App\Filament\Resources\CurriculaVitae\Actions\RemoveAsDefaultAction;
use App\Filament\Resources\CurriculaVitae\Actions\SetAsDefaultAction;
use App\Filament\Tables\Columns\StackColumn;
use App\Models\CurriculumVitae;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\IconSize;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Livewire\Component;

class CurriculaVitaeTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                IconColumn::make('is_default')
                    ->boolean()
                    ->true(icon: Heroicon::Bookmark, color: Color::Violet)
                    ->false(icon: false)
                    ->size(IconSize::Medium)
                    ->label('')
                    ->tooltip(fn ($state) => $state ? 'This CV is the default' : null),

                TextColumn::make('name')
                    ->limit(40)
                    ->searchable(['name', 'slug'])
                    ->sortable()
                    ->description(fn (CurriculumVitae $record) => '/'.$record->slug),

                TextColumn::make('person.full_name')
                    ->searchable(['first_name', 'last_name'])
                    ->sortable(['first_name', 'last_name'])
                    ->limit(30),

                StackColumn::make('headline_and_summary')
                    ->stack([
                        TextColumn::make('headline')
                            ->placeholder('∅')
                            ->wrap()
                            ->lineClamp(1),
                        TextColumn::make('summary')
                            ->placeholder('∅')
                            ->wrap()
                            ->lineClamp(2),
                    ])
                    ->searchable(['headline', 'summary'])
                    ->sortable(['headline'])
                    ->grow(),

                TextColumn::make('published_at')
                    ->dateTime()
                    ->sortable()
                    ->placeholder('Unpublished'),

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
                    SetAsDefaultAction::make(),
                    RemoveAsDefaultAction::make(),
                    DeleteAction::make()
                        ->after(fn (Component $livewire) => $livewire->dispatch(
                            'default-cv-updated',
                            defaultIsSet: CurriculumVitae::query()->default()->exists(),
                        )),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->after(fn (Component $livewire) => $livewire->dispatch(
                            'default-cv-updated',
                            defaultIsSet: CurriculumVitae::query()->default()->exists(),
                        )),
                ]),
            ])
            ->stackedOnMobile();
    }
}
