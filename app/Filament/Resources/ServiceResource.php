<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\RestrictsToRoles;
use App\Filament\Resources\ServiceResource\Pages;
use App\Models\Service;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class ServiceResource extends Resource
{
    use RestrictsToRoles;

    protected static array $roles = ['admin', 'editor'];

    protected static ?string $model = Service::class;

    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';

    protected static ?string $navigationGroup = 'Website Content';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'title';

    public const ICONS = ['trending-up', 'monitor', 'users', 'workflow', 'code', 'search', 'target', 'megaphone', 'share', 'map-pin', 'message', 'briefcase', 'star', 'layout', 'cart', 'refresh', 'shield', 'phone', 'pen', 'database', 'mail', 'sparkles', 'factory', 'plug', 'cloud', 'zap', 'gauge', 'bar-chart'];

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Tabs::make()->columnSpanFull()->tabs([
                Forms\Components\Tabs\Tab::make('Basics')->columns(2)->schema([
                    Forms\Components\Select::make('parent_id')->label('Parent hub')
                        ->relationship('parent', 'title', fn ($query) => $query->whereNull('parent_id'))
                        ->placeholder('— This is a hub (top-level) —')->live(),
                    Forms\Components\Select::make('pillar')->label('Growth ladder step')
                        ->options(collect(config('advertally.ladder'))->mapWithKeys(fn ($l, $k) => [$k => $l['step'].'. '.$l['label'].' — '.$l['title']]))
                        ->required()->helperText('Child services inherit the hub\'s step automatically.'),
                    Forms\Components\TextInput::make('title')->required()->maxLength(190)->live(onBlur: true)
                        ->afterStateUpdated(fn (Forms\Set $set, ?string $state, string $operation) => $operation === 'create' ? $set('slug', Str::slug((string) $state)) : null),
                    Forms\Components\TextInput::make('slug')->required()->maxLength(190)->alphaDash()
                        ->helperText('Hub slugs are fixed in config/advertally.php (ladder).'),
                    Forms\Components\Select::make('icon')->options(array_combine(self::ICONS, self::ICONS))->searchable()->default('sparkles'),
                    Forms\Components\Textarea::make('short_description')->rows(2)->maxLength(300)->required()->columnSpanFull(),
                    Forms\Components\TextInput::make('starting_price')->numeric()->prefix('₹'),
                    Forms\Components\Select::make('price_unit')->options(['month' => 'per month', 'project' => 'per project', 'hour' => 'per hour'])->default('month'),
                    Forms\Components\Toggle::make('is_active')->default(true),
                    Forms\Components\Toggle::make('is_featured'),
                ]),
                Forms\Components\Tabs\Tab::make('Page content')->schema([
                    Forms\Components\TextInput::make('hero_title')->maxLength(190),
                    Forms\Components\Textarea::make('hero_subtitle')->rows(2),
                    Forms\Components\TagsInput::make('problems')->label('Pain points (problem section)')->placeholder('Add a pain point and press Enter')
                        ->helperText('Leave empty on child services to reuse the hub\'s pain points.'),
                    Forms\Components\RichEditor::make('body')->label('Our solution')->toolbarButtons(['bold', 'italic', 'link', 'bulletList', 'orderedList', 'h3', 'undo', 'redo']),
                    Forms\Components\TagsInput::make('deliverables')->label('What\'s included')->placeholder('Add a deliverable and press Enter'),
                    Forms\Components\Repeater::make('process')->label('Process steps')->schema([
                        Forms\Components\TextInput::make('title')->required(),
                        Forms\Components\Textarea::make('text')->rows(2)->required(),
                    ])->columns(2)->maxItems(6)->defaultItems(0)->collapsible()->itemLabel(fn (array $state) => $state['title'] ?? null),
                    Forms\Components\Repeater::make('rate_card')->label('Rate card (Hire hub)')->schema([
                        Forms\Components\TextInput::make('model')->required(),
                        Forms\Components\TextInput::make('price')->numeric()->prefix('₹'),
                        Forms\Components\Select::make('unit')->options(['hour' => 'hour', 'month' => 'month', 'project' => 'project']),
                        Forms\Components\TextInput::make('note'),
                    ])->columns(4)->defaultItems(0)->collapsible()->collapsed(),
                ]),
                Forms\Components\Tabs\Tab::make('SEO')->schema([
                    Forms\Components\TextInput::make('meta_title')->maxLength(70)->helperText('50–65 characters. Include service + "India" or city.'),
                    Forms\Components\Textarea::make('meta_description')->rows(3)->maxLength(320)->helperText('120–160 characters with a call to action.'),
                ]),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->reorderable('sort_order')
            ->defaultSort('sort_order')
            ->columns([
                Tables\Columns\TextColumn::make('title')->searchable()->weight('bold')->description(fn (Service $r) => $r->parent?->title ?? 'Hub page'),
                Tables\Columns\TextColumn::make('pillar')->badge()->formatStateUsing(fn ($state) => config("advertally.ladder.{$state}.label") ?? $state),
                Tables\Columns\TextColumn::make('starting_price')->money('INR')->label('From')->description(fn (Service $r) => '/'.$r->price_unit),
                Tables\Columns\ToggleColumn::make('is_active')->label('Live'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('parent_id')->label('Hub')->relationship('parent', 'title', fn ($query) => $query->whereNull('parent_id')),
                Tables\Filters\TernaryFilter::make('hubs')->label('Type')->placeholder('All')->trueLabel('Hubs only')->falseLabel('Child services only')
                    ->queries(true: fn ($query) => $query->whereNull('parent_id'), false: fn ($query) => $query->whereNotNull('parent_id')),
            ])
            ->actions([
                Tables\Actions\Action::make('view')->label('')->icon('heroicon-o-arrow-top-right-on-square')->url(fn (Service $r) => $r->url, true),
                Tables\Actions\EditAction::make(),
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
