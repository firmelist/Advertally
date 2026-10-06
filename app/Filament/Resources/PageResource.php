<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PageResource\Pages;
use App\Filament\Support\Fields;
use App\Models\Page;
use Filament\Forms;
use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

/**
 * Homepage and block-based pages. Each block maps to resources/views/blocks/{type}.blade.php.
 */
class PageResource extends Resource
{
    protected static ?string $model = Page::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-group';

    protected static ?string $navigationGroup = 'Website Content';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make()->schema([
                Fields::title(),
                Fields::slug('pages')->disabled(fn (?Page $record) => in_array($record?->slug, ['home', 'contact'], true))->dehydrated(),
                Forms\Components\Select::make('template')->options(['blocks' => 'Block page', 'legal' => 'Legal / plain text'])->default('blocks')->required()->live()->native(false),
                Fields::status(),
            ])->columns(2),

            Forms\Components\Builder::make('blocks')
                ->label('Page sections')
                ->visible(fn (Forms\Get $get) => $get('template') === 'blocks')
                ->blocks(self::blocks())
                ->collapsible()->collapsed()->cloneable()->reorderableWithButtons()
                ->blockNumbers(false)
                ->columnSpanFull(),

            Fields::richText('body', 'Content')->visible(fn (Forms\Get $get) => $get('template') === 'legal'),

            Fields::seo(),
        ]);
    }

    /** @return array<int, Block> */
    public static function blocks(): array
    {
        $heading = fn () => [
            Forms\Components\TextInput::make('eyebrow'),
            Forms\Components\TextInput::make('headline')->required(),
            Forms\Components\Textarea::make('intro')->rows(2)->columnSpanFull(),
        ];
        $strings = fn (string $name, string $label) => Forms\Components\TagsInput::make($name)->label($label)->reorderable()->columnSpanFull();

        return [
            Block::make('hero')->icon('heroicon-o-sparkles')->columns(2)->schema([
                Forms\Components\TextInput::make('eyebrow_tag')->label('Eyebrow tag')->maxLength(20),
                Forms\Components\TextInput::make('eyebrow'),
                Forms\Components\TextInput::make('headline')->required()->columnSpanFull(),
                Forms\Components\Textarea::make('subheadline')->rows(3)->columnSpanFull(),
                Forms\Components\TextInput::make('primary_label'), Forms\Components\TextInput::make('primary_url'),
                Forms\Components\TextInput::make('secondary_label'), Forms\Components\TextInput::make('secondary_url'),
                Forms\Components\Select::make('visual')->options(['dashboard' => 'Growth Intelligence dashboard (sample data)', 'signal' => 'Advertally Signal', 'none' => 'None'])->default('none')->native(false),
                $strings('trust_points', 'Check-marked points under the buttons'),
                Forms\Components\Repeater::make('dashboard_scores')->label('Dashboard scores (sample data)')->schema([
                    Forms\Components\TextInput::make('label')->required(), Forms\Components\TextInput::make('value')->numeric()->minValue(0)->maxValue(100)->required(),
                ])->columns(2)->defaultItems(0)->maxItems(4)->collapsed(),
                Forms\Components\Repeater::make('dashboard_funnel')->label('Dashboard funnel (sample data)')->schema([
                    Forms\Components\TextInput::make('label')->required(), Forms\Components\TextInput::make('value')->required(),
                ])->columns(2)->defaultItems(0)->maxItems(4)->collapsed(),
            ]),
            Block::make('trust')->label('Trust strip')->icon('heroicon-o-check-badge')->schema([
                Forms\Components\Repeater::make('points')->schema([
                    Forms\Components\TextInput::make('icon')->default('sparkles'), Forms\Components\TextInput::make('title')->required(), Forms\Components\TextInput::make('text'),
                ])->columns(3)->maxItems(3),
                Forms\Components\Toggle::make('show_logos')->label('Show client logos (only appears once logos are added)')->default(true),
                Forms\Components\TextInput::make('logos_title'),
            ]),
            Block::make('journey')->label('Customer journey has changed')->icon('heroicon-o-arrows-right-left')->columns(2)->schema([
                ...$heading(),
                Forms\Components\TextInput::make('old_label'), $strings('old_path', 'Traditional journey steps'), Forms\Components\Textarea::make('old_text')->rows(2)->columnSpanFull(),
                Forms\Components\TextInput::make('new_label'), $strings('new_path', 'Modern journey steps'), Forms\Components\Textarea::make('new_text')->rows(2)->columnSpanFull(),
                Forms\Components\TextInput::make('message')->columnSpanFull(),
            ]),
            Block::make('story')->label('Numbered story')->icon('heroicon-o-list-bullet')->columns(2)->schema([...$heading(), Fields::titleText('items', 'Statements')->columnSpanFull()]),
            Block::make('growth_os')->label('Growth OS (six engines)')->icon('heroicon-o-cpu-chip')->columns(2)->schema($heading()),
            Block::make('signal')->label('Advertally Signal (dark)')->icon('heroicon-o-signal')->columns(2)->schema([
                ...$heading(), $strings('steps', 'Signal steps'), Fields::titleText('points', 'Points')->columnSpanFull(),
            ]),
            Block::make('technology')->label('Growth Technology')->icon('heroicon-o-code-bracket')->columns(2)->schema([
                ...$heading(), Forms\Components\Textarea::make('message')->rows(2)->columnSpanFull(), Forms\Components\TextInput::make('cta_label'),
            ]),
            Block::make('score')->label('Growth Score promo')->icon('heroicon-o-chart-pie')->columns(2)->schema([...$heading(), Forms\Components\TextInput::make('cta_label')]),
            Block::make('case_studies')->label('Featured case studies')->icon('heroicon-o-trophy')->columns(2)->schema($heading()),
            Block::make('insights')->label('AI Search Lab + Insights')->icon('heroicon-o-light-bulb')->columns(2)->schema([
                Forms\Components\TextInput::make('lab_eyebrow'), Forms\Components\TextInput::make('lab_headline'), Forms\Components\Textarea::make('lab_intro')->rows(2)->columnSpanFull(),
                ...$heading(),
            ]),
            Block::make('testimonials')->icon('heroicon-o-chat-bubble-left-right')->columns(2)->schema([Forms\Components\TextInput::make('eyebrow'), Forms\Components\TextInput::make('headline')]),
            Block::make('talent')->label('Technology & Talent teaser')->icon('heroicon-o-user-group')->columns(2)->schema([
                ...$heading(),
                Forms\Components\Repeater::make('options')->schema([Forms\Components\TextInput::make('label')->required(), Forms\Components\TextInput::make('url')->required()])->columns(2)->columnSpanFull(),
                Forms\Components\TextInput::make('cta_label'), Forms\Components\TextInput::make('cta_url'),
            ]),
            Block::make('industries')->label('Industries grid')->icon('heroicon-o-building-office-2')->columns(2)->schema($heading()),
            Block::make('steps')->icon('heroicon-o-queue-list')->columns(2)->schema([
                ...$heading(), Forms\Components\Toggle::make('dark')->label('Dark section'),
                Forms\Components\Repeater::make('steps')->schema([Forms\Components\TextInput::make('icon'), Forms\Components\TextInput::make('title')->required(), Forms\Components\Textarea::make('text')->rows(2)->columnSpanFull()])->columns(2)->columnSpanFull(),
            ]),
            Block::make('comparison')->label('Before / after comparison')->icon('heroicon-o-scale')->columns(2)->schema([
                ...$heading(),
                Forms\Components\TextInput::make('left_title'), Forms\Components\TextInput::make('right_title'),
                $strings('left_items', 'Left items'), $strings('right_items', 'Right items'),
                Forms\Components\Textarea::make('left_text')->rows(2), Forms\Components\Textarea::make('right_text')->rows(2),
                Forms\Components\TextInput::make('conclusion')->columnSpanFull(),
            ]),
            Block::make('features')->label('Feature cards')->icon('heroicon-o-squares-2x2')->columns(2)->schema([
                ...$heading(),
                Forms\Components\Select::make('columns')->options([3 => '3 columns', 4 => '4 columns'])->default(3),
                Forms\Components\Select::make('background')->options(['white' => 'White', 'canvas' => 'Warm white'])->default('white'),
                Forms\Components\Repeater::make('items')->schema([Forms\Components\TextInput::make('icon'), Forms\Components\TextInput::make('title')->required(), Forms\Components\Textarea::make('text')->rows(2)->columnSpanFull()])->columns(2)->columnSpanFull(),
            ]),
            Block::make('equation')->label('Brand equation (dark)')->icon('heroicon-o-calculator')->columns(2)->schema([
                Forms\Components\TextInput::make('eyebrow'), Forms\Components\TextInput::make('result')->default('Revenue Growth'),
                $strings('terms', 'Terms'), Forms\Components\Textarea::make('text')->rows(3)->columnSpanFull(),
            ]),
            Block::make('rich_text')->label('Rich text')->icon('heroicon-o-document-text')->schema([
                Forms\Components\TextInput::make('eyebrow'), Forms\Components\TextInput::make('heading'), Fields::richText('body', 'Body'),
            ]),
            Block::make('faq')->label('FAQ')->icon('heroicon-o-question-mark-circle')->schema([Forms\Components\TextInput::make('title'), Fields::faqs('items')]),
            Block::make('cta')->label('Growth Score CTA band')->icon('heroicon-o-megaphone')->schema([Forms\Components\TextInput::make('title'), Forms\Components\Textarea::make('text')->rows(2)]),
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->searchable()->weight('bold'),
                Tables\Columns\TextColumn::make('slug')->prefix('/')->color('gray'),
                Tables\Columns\TextColumn::make('template')->badge(),
                Fields::statusColumn(),
                Tables\Columns\TextColumn::make('updated_at')->since()->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make('view')->icon('heroicon-o-arrow-top-right-on-square')->url(fn (Page $record) => $record->url())->openUrlInNewTab(),
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPages::route('/'),
            'create' => Pages\CreatePage::route('/create'),
            'edit' => Pages\EditPage::route('/{record}/edit'),
        ];
    }
}
