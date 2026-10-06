<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SeoMetadataResource\Pages;
use App\Filament\Support\Fields;
use App\Models\SeoMetadata;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

/**
 * Overview of every SEO override across the site. Overrides are created from each content editor's SEO section.
 */
class SeoMetadataResource extends Resource
{
    protected static ?string $model = SeoMetadata::class;

    protected static ?string $navigationIcon = 'heroicon-o-magnifying-glass';

    protected static ?string $navigationGroup = 'Site';

    protected static ?string $navigationLabel = 'SEO overrides';

    protected static ?string $modelLabel = 'SEO override';

    protected static ?int $navigationSort = 2;

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('title')->label('SEO title')->maxLength(70),
            Forms\Components\TextInput::make('canonical')->url(),
            Forms\Components\Textarea::make('description')->label('Meta description')->rows(2)->maxLength(320)->columnSpanFull(),
            Forms\Components\Select::make('robots')->options(['index,follow' => 'Index, follow', 'noindex,follow' => 'Noindex, follow', 'noindex,nofollow' => 'Noindex, nofollow'])->native(false),
            Fields::image('og_image', 'Open Graph image', 'seo'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('seoable_type')->label('Type')->formatStateUsing(fn ($state) => class_basename($state))->badge(),
                Tables\Columns\TextColumn::make('page')->state(fn (SeoMetadata $record) => $record->seoable?->title ?? $record->seoable?->name ?? '#'.$record->seoable_id)->weight('bold'),
                Tables\Columns\TextColumn::make('title')->label('SEO title')->limit(50)->placeholder('Default')
                    ->color(fn ($state) => $state && mb_strlen($state) > 60 ? 'warning' : null),
                Tables\Columns\TextColumn::make('description')->label('Description')->limit(60)->placeholder('Default'),
                Tables\Columns\TextColumn::make('robots')->placeholder('index,follow')->color(fn ($state) => str_contains((string) $state, 'noindex') ? 'danger' : 'gray'),
            ])
            ->actions([
                Tables\Actions\Action::make('view')->icon('heroicon-o-arrow-top-right-on-square')
                    ->visible(fn (SeoMetadata $record) => method_exists($record->seoable ?? new \stdClass, 'url'))
                    ->url(fn (SeoMetadata $record) => $record->seoable?->url())->openUrlInNewTab(),
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return ['index' => Pages\ManageSeoMetadata::route('/')];
    }
}
