<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CaseStudyResource\Pages;
use App\Filament\Support\Fields;
use App\Models\CaseStudy;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CaseStudyResource extends Resource
{
    protected static ?string $model = CaseStudy::class;

    protected static ?string $navigationIcon = 'heroicon-o-trophy';

    protected static ?string $navigationGroup = 'Website Content';

    protected static ?int $navigationSort = 5;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make()->columns(2)->schema([
                Fields::title(),
                Fields::slug('case_studies'),
                Forms\Components\TextInput::make('client_name')->required(),
                Forms\Components\Select::make('industry_id')->relationship('industry', 'name')->preload()->native(false),
                Forms\Components\Textarea::make('summary')->rows(2)->columnSpanFull(),
                Forms\Components\Toggle::make('is_sample')->label('Sample / illustrative case study')
                    ->helperText('Shows a "Sample case study" label and disclaimer everywhere. Turn off only for real, client-approved stories with verified data.')
                    ->default(true)->columnSpanFull(),
                Fields::status(),
                Forms\Components\DateTimePicker::make('published_at')->native(false),
                Forms\Components\Toggle::make('is_featured')->label('Featured on homepage'),
            ]),
            Forms\Components\Section::make('Growth story')->schema(collect(CaseStudy::SECTIONS)->map(fn ($label, $field) => Fields::richText($field, $label))->values()->all())->collapsible(),
            Forms\Components\Section::make('Results data')->schema([
                Forms\Components\Repeater::make('metrics')->relationship()->orderColumn('sort_order')->schema([
                    Forms\Components\TextInput::make('label')->required(),
                    Forms\Components\TextInput::make('value')->required()->helperText('e.g. +67%'),
                    Forms\Components\TextInput::make('before'), Forms\Components\TextInput::make('after'),
                ])->columns(4)->defaultItems(0)->helperText('Use verified data only.'),
                Forms\Components\Fieldset::make('Results chart')->schema([
                    Forms\Components\TextInput::make('chart.title'),
                    Forms\Components\TextInput::make('chart.unit')->maxLength(6),
                    Forms\Components\TagsInput::make('chart.labels')->label('Labels (x-axis)'),
                    Forms\Components\TagsInput::make('chart.values')->label('Values (numbers)'),
                ]),
            ])->collapsible(),
            Forms\Components\Section::make('Media & links')->columns(2)->schema([
                Fields::image('featured_image', 'Featured image', 'case-studies'),
                Forms\Components\FileUpload::make('gallery')->multiple()->image()->disk('public')->directory('case-studies')->reorderable()->maxFiles(8),
                Forms\Components\Select::make('services')->relationship('services', 'title')->multiple()->preload()->searchable(),
                Forms\Components\Select::make('testimonial_id')->relationship('testimonial', 'name')->preload()->native(false),
            ])->collapsible(),
            Fields::seo(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('published_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('title')->weight('bold')->searchable()->wrap()->description(fn ($record) => $record->client_name),
                Tables\Columns\TextColumn::make('industry.name')->badge(),
                Tables\Columns\IconColumn::make('is_sample')->label('Sample')->boolean()->trueColor('warning'),
                Tables\Columns\IconColumn::make('is_featured')->label('Featured')->boolean(),
                Fields::statusColumn(),
                Tables\Columns\TextColumn::make('published_at')->date()->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make('view')->icon('heroicon-o-arrow-top-right-on-square')->url(fn (CaseStudy $record) => $record->url())->openUrlInNewTab(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
