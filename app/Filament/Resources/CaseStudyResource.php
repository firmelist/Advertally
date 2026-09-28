<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\RestrictsToRoles;
use App\Filament\Resources\CaseStudyResource\Pages;
use App\Models\CaseStudy;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class CaseStudyResource extends Resource
{
    use RestrictsToRoles;

    protected static array $roles = ['admin', 'editor'];

    protected static ?string $model = CaseStudy::class;

    protected static ?string $navigationIcon = 'heroicon-o-trophy';

    protected static ?string $navigationGroup = 'Website Content';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Story')->columns(2)->schema([
                Forms\Components\TextInput::make('title')->required()->columnSpanFull()->live(onBlur: true)
                    ->afterStateUpdated(fn (Forms\Set $set, ?string $state, string $operation) => $operation === 'create' ? $set('slug', Str::slug((string) $state)) : null),
                Forms\Components\TextInput::make('slug')->required()->alphaDash()->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('client')->required(),
                Forms\Components\Select::make('industry')->options(config('advertally.industries')),
                Forms\Components\TextInput::make('city'),
                Forms\Components\TagsInput::make('services')->label('Services used')->columnSpanFull(),
                Forms\Components\Textarea::make('summary')->rows(2)->maxLength(300)->columnSpanFull(),
                Forms\Components\Textarea::make('challenge')->rows(4)->columnSpanFull(),
                Forms\Components\Textarea::make('solution')->rows(4)->columnSpanFull(),
                Forms\Components\Textarea::make('testimonial')->rows(3)->columnSpanFull(),
            ]),
            Forms\Components\Section::make('Results')->schema([
                Forms\Components\Repeater::make('results')->schema([
                    Forms\Components\TextInput::make('value')->required()->placeholder('+212%'),
                    Forms\Components\TextInput::make('label')->required()->placeholder('Qualified leads'),
                ])->columns(2)->maxItems(4)->defaultItems(0)->addActionLabel('Add result metric'),
            ]),
            Forms\Components\Section::make('Publishing')->columns(3)->schema([
                Forms\Components\FileUpload::make('image')->image()->disk('public')->directory('case-studies')->maxSize(2048),
                Forms\Components\Toggle::make('is_featured')->label('Show on homepage'),
                Forms\Components\Toggle::make('is_published')->default(true),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->reorderable('sort_order')->defaultSort('sort_order')
            ->columns([
                Tables\Columns\TextColumn::make('title')->searchable()->wrap()->weight('bold')->description(fn ($record) => $record->client),
                Tables\Columns\TextColumn::make('industry')->badge()->formatStateUsing(fn ($state) => config('advertally.industries')[$state] ?? $state),
                Tables\Columns\IconColumn::make('is_featured')->boolean()->label('Home'),
                Tables\Columns\ToggleColumn::make('is_published')->label('Live'),
            ])
            ->actions([
                Tables\Actions\Action::make('view')->label('')->icon('heroicon-o-arrow-top-right-on-square')->url(fn ($record) => route('case-studies.show', $record), true),
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCaseStudies::route('/'),
            'create' => Pages\CreateCaseStudy::route('/create'),
            'edit' => Pages\EditCaseStudy::route('/{record}/edit'),
        ];
    }
}
