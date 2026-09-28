<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\RestrictsToRoles;
use App\Filament\Resources\PlanBuilderItemResource\Pages;
use App\Models\PlanBuilderItem;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PlanBuilderItemResource extends Resource
{
    use RestrictsToRoles;

    protected static array $roles = ['admin', 'editor'];

    protected static ?string $model = PlanBuilderItem::class;

    protected static ?string $navigationIcon = 'heroicon-o-calculator';

    protected static ?string $navigationGroup = 'Website Content';

    protected static ?string $navigationLabel = 'Pricing calculator items';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('group')->options(['marketing' => 'Marketing', 'websites' => 'Websites', 'crm' => 'CRM & Automation', 'hire' => 'Hire'])->required(),
            Forms\Components\TextInput::make('label')->required(),
            Forms\Components\TextInput::make('price')->numeric()->prefix('₹')->required(),
            Forms\Components\Select::make('unit')->options(['month' => 'per month', 'one-time' => 'one-time'])->required()->default('month'),
            Forms\Components\Toggle::make('is_active')->default(true),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->reorderable('sort_order')->defaultSort('sort_order')
            ->columns([
                Tables\Columns\TextColumn::make('group')->badge(),
                Tables\Columns\TextColumn::make('label')->weight('bold'),
                Tables\Columns\TextColumn::make('price')->money('INR')->description(fn ($record) => $record->unit),
                Tables\Columns\ToggleColumn::make('is_active')->label('Live'),
            ])
            ->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()]);
    }

    public static function getPages(): array
    {
        return ['index' => Pages\ManagePlanBuilderItems::route('/')];
    }
}
