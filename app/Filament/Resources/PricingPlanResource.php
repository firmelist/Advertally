<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\RestrictsToRoles;
use App\Filament\Resources\PricingPlanResource\Pages;
use App\Models\PricingPlan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PricingPlanResource extends Resource
{
    use RestrictsToRoles;

    protected static array $roles = ['admin', 'editor'];

    protected static ?string $model = PricingPlan::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-rupee';

    protected static ?string $navigationGroup = 'Website Content';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make()->columns(3)->schema([
                Forms\Components\Select::make('category')->options(PricingPlan::CATEGORIES)->required(),
                Forms\Components\TextInput::make('name')->required(),
                Forms\Components\TextInput::make('tagline'),
                Forms\Components\TextInput::make('price')->numeric()->prefix('₹')->required(),
                Forms\Components\Select::make('price_unit')->options(['month' => 'per month', 'one-time' => 'one-time', 'hour' => 'per hour'])->required()->default('month'),
                Forms\Components\Select::make('audience')->label('Best for')->options(['micro' => 'Micro', 'small' => 'Small', 'medium' => 'Medium']),
                Forms\Components\TagsInput::make('features')->placeholder('Add a feature and press Enter')->columnSpanFull()->reorderable(),
                Forms\Components\TextInput::make('cta_label')->label('Button text')->default('Get Started'),
                Forms\Components\Toggle::make('is_popular')->label('Highlight as "Most popular"'),
                Forms\Components\Toggle::make('is_active')->default(true),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->reorderable('sort_order')->defaultSort('sort_order')
            ->groups([Tables\Grouping\Group::make('category')->getTitleFromRecordUsing(fn ($record) => PricingPlan::CATEGORIES[$record->category] ?? $record->category)])
            ->defaultGroup('category')
            ->columns([
                Tables\Columns\TextColumn::make('name')->weight('bold')->description(fn ($record) => $record->tagline),
                Tables\Columns\TextColumn::make('price')->money('INR')->description(fn ($record) => $record->unit_label),
                Tables\Columns\IconColumn::make('is_popular')->boolean()->label('Popular'),
                Tables\Columns\ToggleColumn::make('is_active')->label('Live'),
            ])
            ->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPricingPlans::route('/'),
            'create' => Pages\CreatePricingPlan::route('/create'),
            'edit' => Pages\EditPricingPlan::route('/{record}/edit'),
        ];
    }
}
