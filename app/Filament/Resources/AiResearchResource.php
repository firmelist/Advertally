<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AiResearchResource\Pages;
use App\Filament\Support\Fields;
use App\Models\AiResearch;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AiResearchResource extends Resource
{
    protected static ?string $model = AiResearch::class;

    protected static ?string $navigationIcon = 'heroicon-o-beaker';

    protected static ?string $navigationGroup = 'Website Content';

    protected static ?string $navigationLabel = 'AI Search Lab';

    protected static ?string $modelLabel = 'research';

    protected static ?string $pluralModelLabel = 'research';

    protected static ?int $navigationSort = 7;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make()->columns(2)->schema([
                Fields::title(),
                Fields::slug('ai_research'),
                Forms\Components\Select::make('category')->options(AiResearch::CATEGORIES)->required()->native(false),
                Forms\Components\Select::make('author_id')->relationship('author', 'name')->preload()->native(false),
                Fields::status(),
                Forms\Components\DateTimePicker::make('published_at')->native(false)->default(now()),
                Forms\Components\Toggle::make('is_featured')->label('Featured'),
                Forms\Components\Textarea::make('summary')->label('Abstract / summary')->rows(3)->columnSpanFull(),
                Forms\Components\TagsInput::make('key_findings')->label('Key findings')->reorderable()->columnSpanFull(),
            ]),
            Forms\Components\Section::make('Research')->schema([
                Fields::richText('body', 'Full research'),
                Fields::richText('methodology', 'Methodology'),
            ]),
            Forms\Components\Section::make('Data & charts')->description('Publish only real data you can source.')->collapsible()->schema([
                Forms\Components\TagsInput::make('data.columns')->label('Table columns'),
                Forms\Components\Textarea::make('data.rows')->label('Table rows')->rows(6)
                    ->helperText('One row per line; separate cells with a vertical bar, e.g. GPTBot | OpenAI | Training')
                    ->formatStateUsing(fn ($state) => collect($state ?? [])->map(fn ($row) => implode(' | ', (array) $row))->implode("\n"))
                    ->dehydrateStateUsing(fn ($state) => collect(preg_split('/\R/', (string) $state))->filter(fn ($l) => trim($l) !== '')
                        ->map(fn ($l) => array_map('trim', explode('|', $l)))->values()->all()),
                Forms\Components\Repeater::make('charts')->schema([
                    Forms\Components\TextInput::make('title')->required(), Forms\Components\TextInput::make('unit')->maxLength(6),
                    Forms\Components\TagsInput::make('labels'), Forms\Components\TagsInput::make('values'),
                ])->columns(2)->defaultItems(0),
            ]),
            Forms\Components\Section::make('Sources & links')->collapsible()->schema([
                Forms\Components\Repeater::make('sources')->schema([
                    Forms\Components\TextInput::make('title')->required(), Forms\Components\TextInput::make('url')->url(),
                ])->columns(2)->defaultItems(0),
                Forms\Components\Select::make('services')->relationship('services', 'title')->multiple()->preload()->searchable(),
            ]),
            Fields::seo(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('published_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('title')->weight('bold')->searchable()->wrap(),
                Tables\Columns\TextColumn::make('category')->badge()->formatStateUsing(fn ($state) => AiResearch::CATEGORIES[$state] ?? $state),
                Tables\Columns\IconColumn::make('is_featured')->boolean()->label('Featured'),
                Fields::statusColumn(),
                Tables\Columns\TextColumn::make('published_at')->date()->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make('view')->icon('heroicon-o-arrow-top-right-on-square')->url(fn (AiResearch $record) => $record->url())->openUrlInNewTab(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAiResearch::route('/'),
            'create' => Pages\CreateAiResearch::route('/create'),
            'edit' => Pages\EditAiResearch::route('/{record}/edit'),
        ];
    }
}
