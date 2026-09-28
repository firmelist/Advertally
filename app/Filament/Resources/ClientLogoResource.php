<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\RestrictsToRoles;
use App\Filament\Resources\ClientLogoResource\Pages;
use App\Models\ClientLogo;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ClientLogoResource extends Resource
{
    use RestrictsToRoles;

    protected static array $roles = ['admin', 'editor'];

    protected static ?string $model = ClientLogo::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';

    protected static ?string $navigationGroup = 'Website Content';

    protected static ?int $navigationSort = 7;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')->required(),
            Forms\Components\FileUpload::make('logo')->image()->disk('public')->directory('logos')->maxSize(512)
                ->helperText('Transparent PNG or SVG, ~200×60px. Without a logo, the name is shown as text.'),
            Forms\Components\Toggle::make('is_active')->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->reorderable('sort_order')->defaultSort('sort_order')
            ->columns([
                Tables\Columns\ImageColumn::make('logo')->disk('public')->height(28),
                Tables\Columns\TextColumn::make('name')->weight('bold'),
                Tables\Columns\ToggleColumn::make('is_active')->label('Live'),
            ])
            ->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()]);
    }

    public static function getPages(): array
    {
        return ['index' => Pages\ManageClientLogos::route('/')];
    }
}
