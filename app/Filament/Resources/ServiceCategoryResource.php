<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ServiceCategoryResource\Pages;
use App\Filament\Support\Fields;
use App\Models\ServiceCategory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ServiceCategoryResource extends Resource
{
    protected static ?string $model = ServiceCategory::class;

    protected static ?string $navigationIcon = 'heroicon-o-cpu-chip';

    protected static ?string $navigationGroup = 'Website Content';

    protected static ?string $navigationLabel = 'Solutions (engines)';

    protected static ?string $modelLabel = 'solution engine';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Tabs::make()->columnSpanFull()->tabs([
                Forms\Components\Tabs\Tab::make('Overview')->columns(2)->schema([
                    Forms\Components\TextInput::make('name')->required(),
                    Fields::slug('service_categories'),
                    Forms\Components\Select::make('group')->options(ServiceCategory::GROUPS)->required()->native(false),
                    Forms\Components\TextInput::make('number')->maxLength(4)->helperText('e.g. 01'),
                    Forms\Components\TextInput::make('tagline')->helperText('e.g. "Be Found."'),
                    Forms\Components\TextInput::make('icon')->helperText('Icon key, e.g. search, megaphone'),
                    Forms\Components\TextInput::make('headline')->label('Page H1')->columnSpanFull(),
                    Forms\Components\Textarea::make('subheadline')->rows(2)->columnSpanFull(),
                    Forms\Components\Textarea::make('summary')->label('Card summary')->rows(2)->columnSpanFull(),
                    Forms\Components\Textarea::make('principle')->label('Positioning statement')->rows(2)->columnSpanFull(),
                    Forms\Components\TextInput::make('cta_label'), Forms\Components\TextInput::make('cta_url'),
                    Fields::status(), Fields::sortOrder(),
                ]),
                Forms\Components\Tabs\Tab::make('Content')->schema([
                    Fields::richText('intro', 'Introduction'),
                    Fields::titleText('highlights', 'Highlights'),
                    Forms\Components\TagsInput::make('capabilities')->reorderable(),
                ]),
                Forms\Components\Tabs\Tab::make('Flow & metrics')->columns(2)->schema([
                    Forms\Components\TextInput::make('flow_title'),
                    Forms\Components\TagsInput::make('flow')->reorderable()->helperText('Sequence, e.g. Ad Spend → Revenue'),
                    Forms\Components\TextInput::make('metrics_title'),
                    Forms\Components\TagsInput::make('metrics')->reorderable(),
                ]),
                Forms\Components\Tabs\Tab::make('Process & FAQs')->schema([
                    Fields::titleText('process', 'Default delivery process (used by services without their own)'),
                    Fields::faqs(),
                ]),
            ]),
            Fields::seo(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->reorderable('sort_order')->defaultSort('sort_order')
            ->columns([
                Tables\Columns\TextColumn::make('number')->label('#'),
                Tables\Columns\TextColumn::make('name')->weight('bold')->searchable()->description(fn ($record) => $record->tagline),
                Tables\Columns\TextColumn::make('group')->badge()->formatStateUsing(fn ($state) => ServiceCategory::GROUPS[$state] ?? $state),
                Tables\Columns\TextColumn::make('services_count')->counts('services')->label('Services'),
                Fields::statusColumn(),
            ])
            ->filters([Tables\Filters\SelectFilter::make('group')->options(ServiceCategory::GROUPS)])
            ->actions([
                Tables\Actions\Action::make('view')->icon('heroicon-o-arrow-top-right-on-square')->url(fn ($record) => $record->url())->openUrlInNewTab(),
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListServiceCategories::route('/'),
            'create' => Pages\CreateServiceCategory::route('/create'),
            'edit' => Pages\EditServiceCategory::route('/{record}/edit'),
        ];
    }
}
