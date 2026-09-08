<?php

namespace App\Filament\Resources\Content\Motorcycles\Tables;

use App\Models\Content\Motorcycle;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class MotorcyclesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('arabic_name')
                    ->label('الاسم')
                    ->state(fn (Motorcycle $record): string => $record->translate('ar')?->name ?? '—'),
                TextColumn::make('english_name')
                    ->label('Name')
                    ->state(fn (Motorcycle $record): string => $record->translate('en')?->name ?? '—'),
                TextColumn::make('category_name')
                    ->label('Category')
                    ->state(fn (Motorcycle $record): string => $record->category?->translateOrDefault(app()->getLocale())?->name ?? '—'),
                TextColumn::make('slug')
                    ->searchable(),
                TextColumn::make('manufacturer')
                    ->searchable(),
                TextColumn::make('model_year')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('engine_cc')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('power_hp')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('price')
                    ->money()
                    ->sortable(),
                TextColumn::make('currency')
                    ->searchable(),
                IconColumn::make('is_featured')
                    ->boolean(),
                TextColumn::make('published_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
