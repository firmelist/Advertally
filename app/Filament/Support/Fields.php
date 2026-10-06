<?php

namespace App\Filament\Support;

use Filament\Forms;
use Filament\Forms\Set;
use Filament\Tables;
use Illuminate\Support\Str;

/**
 * Reusable admin form/table building blocks so every content type behaves the same way.
 */
class Fields
{
    /** Title input that fills the slug on create. */
    public static function title(string $name = 'title', string $label = 'Title'): Forms\Components\TextInput
    {
        return Forms\Components\TextInput::make($name)->label($label)->required()->maxLength(255)
            ->live(onBlur: true)
            ->afterStateUpdated(fn (Set $set, ?string $state, string $operation) => $operation === 'create' ? $set('slug', Str::slug((string) $state)) : null);
    }

    public static function slug(string $table): Forms\Components\TextInput
    {
        return Forms\Components\TextInput::make('slug')->required()->maxLength(150)
            ->alphaDash()->unique($table, 'slug', ignoreRecord: true)
            ->helperText('Used in the URL. Changing it breaks existing links.');
    }

    public static function status(): Forms\Components\Select
    {
        return Forms\Components\Select::make('status')->options(['draft' => 'Draft', 'published' => 'Published'])->default('draft')->required()->native(false);
    }

    public static function statusColumn(): Tables\Columns\TextColumn
    {
        return Tables\Columns\TextColumn::make('status')->badge()->color(fn (string $state) => $state === 'published' ? 'success' : 'gray');
    }

    public static function richText(string $name, string $label): Forms\Components\RichEditor
    {
        return Forms\Components\RichEditor::make($name)->label($label)
            ->toolbarButtons(['h2', 'h3', 'bold', 'italic', 'link', 'bulletList', 'orderedList', 'blockquote', 'redo', 'undo'])
            ->columnSpanFull();
    }

    public static function image(string $name, string $label, string $directory): Forms\Components\FileUpload
    {
        return Forms\Components\FileUpload::make($name)->label($label)
            ->image()->disk('public')->directory($directory)->visibility('public')
            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/svg+xml'])
            ->maxSize(4096);
    }

    /** Repeater of {title, text}. */
    public static function titleText(string $name, string $label): Forms\Components\Repeater
    {
        return Forms\Components\Repeater::make($name)->label($label)
            ->schema([
                Forms\Components\TextInput::make('title')->required(),
                Forms\Components\Textarea::make('text')->rows(2),
            ])
            ->columns(2)->collapsible()->itemLabel(fn (array $state) => $state['title'] ?? null)->defaultItems(0);
    }

    public static function faqs(string $name = 'faqs'): Forms\Components\Repeater
    {
        return Forms\Components\Repeater::make($name)->label('FAQs (also output as FAQPage schema)')
            ->schema([
                Forms\Components\TextInput::make('question')->required()->columnSpanFull(),
                Forms\Components\Textarea::make('answer')->required()->rows(3)->columnSpanFull(),
            ])
            ->collapsible()->itemLabel(fn (array $state) => $state['question'] ?? null)->defaultItems(0);
    }

    /** Per-record SEO overrides (morphOne seo). Blank values fall back to generated defaults. */
    public static function seo(): Forms\Components\Section
    {
        return Forms\Components\Section::make('SEO')
            ->description('Leave blank to use sensible defaults generated from the content.')
            ->relationship('seo')
            ->collapsed()
            ->schema([
                Forms\Components\TextInput::make('title')->label('SEO title')->maxLength(70)->helperText('Aim for 50–60 characters.'),
                Forms\Components\TextInput::make('canonical')->label('Canonical URL')->url()->maxLength(255),
                Forms\Components\Textarea::make('description')->label('Meta description')->rows(2)->maxLength(320)->helperText('Aim for 140–160 characters.')->columnSpanFull(),
                Forms\Components\Select::make('robots')->options([
                    'index,follow' => 'Index, follow (default)', 'noindex,follow' => 'Noindex, follow', 'noindex,nofollow' => 'Noindex, nofollow',
                ])->native(false),
                self::image('og_image', 'Open Graph image (1200×630)', 'seo'),
                Forms\Components\Textarea::make('schema')->label('Additional JSON-LD (array of nodes)')->rows(4)->columnSpanFull()
                    ->helperText('Advanced. Only add schema that reflects visible page content.')
                    ->formatStateUsing(fn ($state) => $state ? json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) : null)
                    ->dehydrateStateUsing(fn ($state) => filled($state) ? json_decode($state, true) : null)
                    ->rule('nullable')->rule('json'),
            ])->columns(2);
    }

    public static function sortOrder(): Forms\Components\TextInput
    {
        return Forms\Components\TextInput::make('sort_order')->numeric()->default(0)->minValue(0);
    }
}
