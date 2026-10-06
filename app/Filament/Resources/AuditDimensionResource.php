<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AuditDimensionResource\Pages;
use App\Filament\Resources\AuditDimensionResource\RelationManagers\QuestionsRelationManager;
use App\Models\AuditDimension;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

/**
 * Growth Score™ and AI Visibility dimensions, weights, questions and recommendation rules.
 */
class AuditDimensionResource extends Resource
{
    protected static ?string $model = AuditDimension::class;

    protected static ?string $navigationIcon = 'heroicon-o-chart-pie';

    protected static ?string $navigationGroup = 'Growth Score';

    protected static ?string $navigationLabel = 'Scoring dimensions';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make()->columns(3)->schema([
                Forms\Components\Select::make('type')->options(AuditDimension::TYPES)->required()->native(false)->disabledOn('edit'),
                Forms\Components\TextInput::make('key')->required()->alphaDash()->disabledOn('edit')
                    ->helperText('AI Visibility keys must match the analyser: ai_visibility, search_visibility, authority, entity_strength, content_coverage, conversion_readiness.'),
                Forms\Components\TextInput::make('name')->required(),
                Forms\Components\TextInput::make('weight')->numeric()->minValue(1)->maxValue(10)->default(1)->helperText('Relative weight in the overall score.'),
                Forms\Components\TextInput::make('sort_order')->numeric()->default(0),
                Forms\Components\Textarea::make('description')->rows(2)->columnSpanFull(),
            ]),
            Forms\Components\Section::make('Recommendation rules')
                ->description('Shown when this dimension scores below the threshold. Lowest threshold = most urgent.')
                ->visible(fn (Forms\Get $get) => $get('type') === 'growth_score')
                ->schema([
                    Forms\Components\Repeater::make('recommendations')->schema([
                        Forms\Components\TextInput::make('below')->label('Score below')->numeric()->minValue(1)->maxValue(101)->required(),
                        Forms\Components\Select::make('impact')->options(['high' => 'High', 'medium' => 'Medium', 'low' => 'Low'])->default('medium')->required(),
                        Forms\Components\TextInput::make('title')->required()->columnSpan(2),
                        Forms\Components\Textarea::make('description')->rows(2)->columnSpanFull(),
                    ])->columns(4)->defaultItems(0)->itemLabel(fn (array $state) => $state['title'] ?? null)->collapsible(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->defaultGroup('type')
            ->columns([
                Tables\Columns\TextColumn::make('name')->weight('bold')->description(fn ($record) => $record->description),
                Tables\Columns\TextColumn::make('key')->color('gray'),
                Tables\Columns\TextColumn::make('weight'),
                Tables\Columns\TextColumn::make('questions_count')->counts('questions')->label('Questions'),
            ])
            ->filters([Tables\Filters\SelectFilter::make('type')->options(AuditDimension::TYPES)])
            ->actions([Tables\Actions\EditAction::make()]);
    }

    public static function getRelations(): array
    {
        return [QuestionsRelationManager::class];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAuditDimensions::route('/'),
            'create' => Pages\CreateAuditDimension::route('/create'),
            'edit' => Pages\EditAuditDimension::route('/{record}/edit'),
        ];
    }
}
