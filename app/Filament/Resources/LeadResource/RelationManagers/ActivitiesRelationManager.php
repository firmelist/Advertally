<?php

namespace App\Filament\Resources\LeadResource\RelationManagers;

use App\Models\LeadActivity;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ActivitiesRelationManager extends RelationManager
{
    protected static string $relationship = 'activities';

    protected static ?string $title = 'Notes & activity';

    public function isReadOnly(): bool
    {
        return false;
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('type')->options(LeadActivity::TYPES)->default('note')->required()->native(false),
            Forms\Components\Textarea::make('body')->label('Details')->required()->rows(3)->columnSpanFull(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('type')->badge()->formatStateUsing(fn ($state) => LeadActivity::TYPES[$state] ?? $state),
                Tables\Columns\TextColumn::make('body')->wrap(),
                Tables\Columns\TextColumn::make('user.name')->label('By')->placeholder('System'),
                Tables\Columns\TextColumn::make('created_at')->since(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->label('Add note')
                    ->mutateFormDataUsing(fn (array $data) => [...$data, 'user_id' => auth()->id()]),
            ])
            ->actions([Tables\Actions\DeleteAction::make()]);
    }
}
