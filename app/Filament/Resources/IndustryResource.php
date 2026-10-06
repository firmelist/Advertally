<?php

namespace App\Filament\Resources;

use App\Filament\Resources\IndustryResource\Pages;
use App\Filament\Support\Fields;
use App\Models\Industry;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class IndustryResource extends Resource
{
    protected static ?string $model = Industry::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';

    protected static ?string $navigationGroup = 'Website Content';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make()->columns(2)->schema([
                Fields::title('name', 'Name'),
                Fields::slug('industries'),
                Forms\Components\TextInput::make('headline')->columnSpanFull(),
                Forms\Components\Textarea::make('summary')->rows(2)->columnSpanFull(),
                Forms\Components\TextInput::make('icon'),
                Fields::status(),
                Fields::sortOrder(),
            ]),
            Forms\Components\Section::make('How the market buys')->schema([
                Forms\Components\Textarea::make('how_customers_search')->rows(3),
                Forms\Components\Textarea::make('ai_discovery')->label('How AI affects discovery')->rows(3),
                Forms\Components\TagsInput::make('challenges')->label('Typical acquisition challenges'),
                Fields::titleText('opportunities', 'Growth opportunities'),
                Fields::titleText('growth_system', 'Example growth system'),
            ]),
            Forms\Components\Section::make('Links & FAQs')->schema([
                Forms\Components\Select::make('services')->label('Relevant Advertally solutions')->relationship('services', 'title')->multiple()->preload()->searchable(),
                Fields::faqs(),
            ]),
            Fields::seo(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->reorderable('sort_order')->defaultSort('sort_order')
            ->columns([
                Tables\Columns\TextColumn::make('name')->weight('bold')->searchable()->description(fn ($record) => str($record->summary)->limit(80)),
                Tables\Columns\TextColumn::make('services_count')->counts('services')->label('Solutions'),
                Fields::statusColumn(),
            ])
            ->actions([
                Tables\Actions\Action::make('view')->icon('heroicon-o-arrow-top-right-on-square')->url(fn (Industry $record) => $record->url())->openUrlInNewTab(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListIndustries::route('/'),
            'create' => Pages\CreateIndustry::route('/create'),
            'edit' => Pages\EditIndustry::route('/{record}/edit'),
        ];
    }
}
