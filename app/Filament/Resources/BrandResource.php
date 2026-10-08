<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BrandResource\Pages;
use App\Models\Brand;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class BrandResource extends Resource
{
    protected static ?string $model = Brand::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-storefront';

    protected static ?string $navigationGroup = 'Careers';

    protected static ?string $navigationLabel = 'Brands & clients';

    protected static ?int $navigationSort = 2;

    /** Shared with the inline "+ Add New Brand" form on internships. */
    public static function formSchema(): array
    {
        return [
            Forms\Components\Section::make('Brand')->columns(2)->schema([
                Forms\Components\TextInput::make('name')->label('Brand name')->required()->maxLength(150)
                    ->live(onBlur: true)->afterStateUpdated(fn (Set $set, ?string $state, ?string $operation) => $operation !== 'edit' ? $set('slug', Str::slug((string) $state)) : null),
                Forms\Components\TextInput::make('slug')->required()->alphaDash()->unique('brands', 'slug', ignoreRecord: true),
                Forms\Components\FileUpload::make('logo')->label('Logo')->required()
                    ->disk('public')->directory('brands')->visibility('public')
                    ->acceptedFileTypes(['image/svg+xml', 'image/png', 'image/webp'])->maxSize(2048)
                    ->helperText('SVG, PNG or WebP. Raster logos are optimised and responsive sizes generated automatically; SVGs are sanitised.')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('industry')->datalist(['Education', 'Healthcare', 'Technology', 'SaaS', 'E-commerce', 'Real Estate', 'Finance', 'Travel', 'Professional Services', 'Manufacturing', 'Startups', 'Recruitment']),
                Forms\Components\TextInput::make('category')->label('Brand category'),
                Forms\Components\TextInput::make('website_url')->label('Website')->url()->helperText('Shown only when the brand is public and approved.'),
                Forms\Components\TextInput::make('work_summary')->label('Work (shown on hover)')->placeholder('Digital Marketing / SEO / Performance Marketing'),
                Forms\Components\Textarea::make('description')->label('Short description')->rows(2)->maxLength(400)->columnSpanFull(),
            ]),
            Forms\Components\Section::make('Confidentiality & display')
                ->description('A brand appears on public pages only when it is Public, Approved for website display, Active and enabled for internship pages. Otherwise it stays internal (it can still be linked to projects and internships).')
                ->columns(2)->schema([
                    Forms\Components\Radio::make('is_public')->label('Public visibility')
                        ->options([1 => 'Public', 0 => 'Private'])->default(0)->inline()->required()
                        ->helperText('Private brands never show their name, logo or URL publicly and are excluded from structured data.'),
                    Forms\Components\Radio::make('display_permission')->label('Display permission')->options(Brand::PERMISSIONS)->default('internal')->required(),
                    Forms\Components\Toggle::make('show_on_internships')->label('Show on internship pages')->default(true),
                    Forms\Components\Select::make('status')->options(['active' => 'Active', 'inactive' => 'Inactive'])->default('active')->required()->native(false),
                    Forms\Components\TextInput::make('sort_order')->label('Display order')->numeric()->default(0),
                ]),
        ];
    }

    public static function form(Form $form): Form
    {
        return $form->schema(self::formSchema());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->reorderable('sort_order')->defaultSort('sort_order')
            ->columns([
                Tables\Columns\ImageColumn::make('logo')->disk('public')->height(32),
                Tables\Columns\TextColumn::make('name')->weight('bold')->searchable()->description(fn (Brand $record) => $record->industry),
                Tables\Columns\TextColumn::make('visibility')->label('Public page')
                    ->state(fn (Brand $record) => $record->isDisplayable() ? 'Shown' : 'Hidden')
                    ->badge()->color(fn ($state) => $state === 'Shown' ? 'success' : 'gray')
                    ->tooltip(fn (Brand $record) => $record->isDisplayable() ? 'Public + approved + active' : 'Private, internal-only, inactive or disabled for internships'),
                Tables\Columns\IconColumn::make('is_public')->label('Public')->boolean(),
                Tables\Columns\TextColumn::make('display_permission')->label('Permission')->formatStateUsing(fn ($state) => Brand::PERMISSIONS[$state] ?? $state)->badge()
                    ->color(fn ($state) => $state === 'approved' ? 'success' : 'warning'),
                Tables\Columns\TextColumn::make('internships_count')->counts('internships')->label('Internships'),
                Tables\Columns\TextColumn::make('status')->badge()->color(fn ($state) => $state === 'active' ? 'success' : 'gray'),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_public')->label('Public'),
                Tables\Filters\SelectFilter::make('display_permission')->options(Brand::PERMISSIONS),
                Tables\Filters\SelectFilter::make('status')->options(['active' => 'Active', 'inactive' => 'Inactive']),
            ])
            ->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBrands::route('/'),
            'create' => Pages\CreateBrand::route('/create'),
            'edit' => Pages\EditBrand::route('/{record}/edit'),
        ];
    }
}
