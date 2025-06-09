<?php

namespace App\Filament\Resources\PatientResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TreatmentsRelationManager extends RelationManager
{
    protected static string $relationship = 'treatments';
    protected static ?string $relationshipLabel = '治療';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('description')
                    ->label('治療')
                    ->required()
                    ->maxLength(255)
                    ->columnSpan('full'),

                Forms\Components\Textarea::make('notes')
                    ->label('経過')
                    ->maxLength(65535)
                    ->columnSpan('full'),

                Forms\Components\TextInput::make('price')
                    ->label('診察料')
                    ->numeric()
                    ->prefix('￥')
                    ->maxValue(42949672.95)
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('description')
            ->columns([
                Tables\Columns\TextColumn::make('description')->label('治療'),
                Tables\Columns\TextColumn::make('notes')->label('経過'),
                Tables\Columns\TextColumn::make('price')
                    ->label('診察料')
                    ->money('JPY')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('受診日')
                    ->dateTime('Y年m月d日(D) H時i分s秒(T)'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
