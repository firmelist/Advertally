<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClientLogoResource\Pages;
use App\Filament\Support\Fields;
use App\Models\ClientLogo;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ClientLogoResource extends Resource
{
    protected static ?string $model = ClientLogo::class;

    protected static ?string $navigationIcon = 'heroicon-o-star';

    protected static ?string $navigationGroup = 'Website Content';

    protected static ?int $navigationSort = 11;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Placeholder::make('rule')->hiddenLabel()->columnSpanFull()
                ->content('Only add logos of real clients who have agreed to be shown. The logo strip stays hidden until at least one logo is active.'),
            Forms\Components\TextInput::make('name')->required(),
            Forms\Components\TextInput::make('url')->url(),
            Fields::image('logo', 'Logo (SVG or transparent PNG)', 'logos')->required(),
            Forms\Components\Toggle::make('is_active')->default(true),
            Fields::sortOrder(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->reorderable('sort_order')->defaultSort('sort_order')
            ->columns([
                Tables\Columns\ImageColumn::make('logo')->disk('public')->height(32),
                Tables\Columns\TextColumn::make('name')->weight('bold'),
                Tables\Columns\ToggleColumn::make('is_active')->label('Active'),
            ])
            ->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()]);
    }

    public static function getPages(): array
    {
        return ['index' => Pages\ManageClientLogos::route('/')];
    }
}
