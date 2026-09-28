<?php

namespace App\Filament\Resources\LeadResource\RelationManagers;

use App\Models\LeadNote;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class NotesRelationManager extends RelationManager
{
    protected static string $relationship = 'notes';

    protected static ?string $title = 'Activity timeline';

    public function isReadOnly(): bool
    {
        return false;
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('type')->options(collect(LeadNote::TYPES)->except('status'))->default('note')->required(),
            Forms\Components\Textarea::make('body')->label('Note')->required()->rows(3)->columnSpanFull(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('type')->badge()
                    ->formatStateUsing(fn ($state) => LeadNote::TYPES[$state] ?? $state)
                    ->color(fn ($state) => match ($state) { 'status' => 'gray', 'call' => 'info', 'whatsapp' => 'success', 'meeting' => 'primary', default => 'warning' }),
                Tables\Columns\TextColumn::make('body')->wrap()->label('Details'),
                Tables\Columns\TextColumn::make('user.name')->label('By')->placeholder('System'),
                Tables\Columns\TextColumn::make('created_at')->label('When')->since()->tooltip(fn ($record) => $record->created_at->format('d M Y, h:i A')),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->label('Add note')
                    ->mutateFormDataUsing(fn (array $data) => [...$data, 'user_id' => auth()->id()]),
            ])
            ->actions([
                Tables\Actions\DeleteAction::make()->visible(fn () => auth()->user()?->isAdmin()),
            ])
            ->paginated([10, 25]);
    }
}
