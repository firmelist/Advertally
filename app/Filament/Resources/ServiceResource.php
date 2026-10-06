<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ServiceResource\Pages;
use App\Filament\Support\Fields;
use App\Models\Service;
use App\Models\ServiceCategory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ServiceResource extends Resource
{
    protected static ?string $model = Service::class;

    protected static ?string $navigationIcon = 'heroicon-o-squares-plus';

    protected static ?string $navigationGroup = 'Website Content';

    protected static ?int $navigationSort = 3;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Tabs::make()->columnSpanFull()->tabs([
                Forms\Components\Tabs\Tab::make('Basics')->columns(2)->schema([
                    Fields::title(),
                    Fields::slug('services'),
                    Forms\Components\Select::make('service_category_id')->label('Engine / vertical')->relationship('category', 'name')->required()->preload()->native(false),
                    Forms\Components\TextInput::make('icon')->helperText('Icon key, e.g. search, bot, chart'),
                    Forms\Components\Textarea::make('short_description')->required()->rows(2)->maxLength(300)->columnSpanFull(),
                    Fields::status(), Fields::sortOrder(),
                    Forms\Components\Toggle::make('is_featured')->label('Featured'),
                ]),
                Forms\Components\Tabs\Tab::make('Hero & content')->schema([
                    Forms\Components\TextInput::make('hero_title')->helperText('Page H1. Defaults to the title.'),
                    Forms\Components\Textarea::make('hero_subtitle')->rows(2),
                    Fields::richText('long_description', 'Long description'),
                    Fields::titleText('benefits', 'Business outcomes'),
                    Forms\Components\TagsInput::make('deliverables')->label('What is included')->reorderable(),
                ]),
                Forms\Components\Tabs\Tab::make('Process & FAQs')->schema([
                    Fields::titleText('process', 'Process (leave empty to use the engine default)'),
                    Fields::faqs(),
                ]),
                Forms\Components\Tabs\Tab::make('Relationships')->schema([
                    Forms\Components\Select::make('related')->label('Related services')->relationship('related', 'title')->multiple()->preload()->searchable(),
                    Forms\Components\Select::make('industries')->label('Related industries')->relationship('industries', 'name')->multiple()->preload(),
                ]),
            ]),
            Fields::seo(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->columns([
                Tables\Columns\TextColumn::make('title')->weight('bold')->searchable()->description(fn ($record) => str($record->short_description)->limit(70)),
                Tables\Columns\TextColumn::make('category.name')->label('Engine')->badge(),
                Tables\Columns\IconColumn::make('is_featured')->boolean()->label('Featured'),
                Fields::statusColumn(),
                Tables\Columns\TextColumn::make('updated_at')->since()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('service_category_id')->label('Engine')->options(fn () => ServiceCategory::query()->orderBy('sort_order')->pluck('name', 'id')),
                Tables\Filters\SelectFilter::make('status')->options(['draft' => 'Draft', 'published' => 'Published']),
            ])
            ->actions([
                Tables\Actions\Action::make('view')->icon('heroicon-o-arrow-top-right-on-square')->url(fn (Service $record) => $record->url())->openUrlInNewTab(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListServices::route('/'),
            'create' => Pages\CreateService::route('/create'),
            'edit' => Pages\EditService::route('/{record}/edit'),
        ];
    }
}
