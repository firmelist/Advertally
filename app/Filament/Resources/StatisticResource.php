<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StatisticResource\Pages;
use App\Models\Statistic;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class StatisticResource extends Resource
{
    protected static ?string $model = Statistic::class;

    protected static ?string $navigationIcon = 'heroicon-o-hashtag';

    protected static ?string $navigationGroup = 'Careers';

    protected static ?string $navigationLabel = 'Statistics';

    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Placeholder::make('rule')->hiddenLabel()->columnSpanFull()
                ->content('Use only figures you can verify. Shown on internship pages that have "Show statistics" switched on.'),
            Forms\Components\TextInput::make('value')->label('Statistic number')->required()->maxLength(40)->placeholder('50+'),
            Forms\Components\TextInput::make('label')->label('Statistic label')->required()->maxLength(120)->placeholder('Brands supported'),
            Forms\Components\Hidden::make('context')->default('internships'),
            Forms\Components\Toggle::make('is_visible')->label('Display')->default(true),
            Forms\Components\TextInput::make('sort_order')->numeric()->default(0),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->reorderable('sort_order')->defaultSort('sort_order')
            ->columns([
                Tables\Columns\TextColumn::make('value')->weight('bold'),
                Tables\Columns\TextColumn::make('label'),
                Tables\Columns\ToggleColumn::make('is_visible')->label('Display'),
            ])
            ->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()]);
    }

    public static function getPages(): array
    {
        return ['index' => Pages\ManageStatistics::route('/')];
    }
}
