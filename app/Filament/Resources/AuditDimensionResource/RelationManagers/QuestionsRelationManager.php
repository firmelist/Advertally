<?php

namespace App\Filament\Resources\AuditDimensionResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class QuestionsRelationManager extends RelationManager
{
    protected static string $relationship = 'questions';

    protected static ?string $title = 'Growth Score questions';

    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        return $ownerRecord->type === 'growth_score';
    }

    public function isReadOnly(): bool
    {
        return false;
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('question')->required()->columnSpanFull(),
            Forms\Components\TextInput::make('help_text')->columnSpanFull(),
            Forms\Components\Repeater::make('options')->label('Answer options (points 0–100)')->schema([
                Forms\Components\TextInput::make('label')->required()->columnSpan(3),
                Forms\Components\TextInput::make('points')->numeric()->minValue(0)->maxValue(100)->required(),
            ])->columns(4)->minItems(2)->maxItems(6)->reorderable()->columnSpanFull(),
            Forms\Components\Toggle::make('is_active')->default(true),
            Forms\Components\TextInput::make('sort_order')->numeric()->default(0),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->reorderable('sort_order')->defaultSort('sort_order')
            ->columns([
                Tables\Columns\TextColumn::make('question')->wrap()->weight('bold'),
                Tables\Columns\TextColumn::make('options')->label('Options')->state(fn ($record) => count($record->options ?? [])),
                Tables\Columns\ToggleColumn::make('is_active')->label('Active'),
            ])
            ->headerActions([Tables\Actions\CreateAction::make()])
            ->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()]);
    }
}
