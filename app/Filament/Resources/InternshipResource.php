<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InternshipResource\Pages;
use App\Filament\Support\Fields;
use App\Models\Brand;
use App\Models\ClientProject;
use App\Models\Internship;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class InternshipResource extends Resource
{
    protected static ?string $model = Internship::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $navigationGroup = 'Careers';

    protected static ?string $navigationLabel = 'Internships';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Tabs::make()->columnSpanFull()->persistTabInQueryString()->tabs([
                Forms\Components\Tabs\Tab::make('Basics')->columns(2)->schema([
                    Fields::title(),
                    Fields::slug('internships'),
                    Forms\Components\TextInput::make('department')->datalist(['Digital Marketing', 'Development', 'Design', 'AI & Automation', 'Analytics']),
                    Forms\Components\TextInput::make('headline')->label('Hero headline')->helperText('Wrap words in *asterisks* to highlight them. Defaults to the title.'),
                    Forms\Components\Textarea::make('summary')->label('Hero summary')->rows(3)->required()->maxLength(500)->columnSpanFull(),
                    Fields::status(), Fields::sortOrder(),
                    Forms\Components\Toggle::make('is_featured')->label('Featured'),
                ]),
                Forms\Components\Tabs\Tab::make('Details')->columns(3)->schema([
                    Forms\Components\TextInput::make('location')->default('Remote'),
                    Forms\Components\Select::make('work_mode')->options(Internship::MODES)->native(false),
                    Forms\Components\TextInput::make('duration')->placeholder('3 months'),
                    Forms\Components\TextInput::make('stipend')->placeholder('Performance-based / Unpaid / ₹X per month'),
                    Forms\Components\TextInput::make('openings')->placeholder('2'),
                    Forms\Components\TextInput::make('hours')->label('Hours')->placeholder('Full-time, Mon–Fri'),
                    Forms\Components\TextInput::make('start_date')->label('Start date')->placeholder('Immediate / 1 November'),
                    Forms\Components\DatePicker::make('apply_by')->label('Apply by')->native(false)->helperText('Applications close after this date. Leave blank for rolling.'),
                ]),
                Forms\Components\Tabs\Tab::make('Content')->schema([
                    Fields::titleText('why', 'Why this internship'),
                    Forms\Components\Textarea::make('team_intro')->label('Join the team from day one')->rows(3),
                    Forms\Components\TagsInput::make('team_points')->label('Day-one points')->reorderable(),
                    Forms\Components\Repeater::make('work_on')->label("What you'll work on")
                        ->schema([
                            Forms\Components\TextInput::make('title')->required(),
                            Forms\Components\TagsInput::make('items')->reorderable(),
                        ])->collapsible()->itemLabel(fn (array $state) => $state['title'] ?? null)->defaultItems(0),
                    Forms\Components\Grid::make(2)->schema([
                        Forms\Components\TagsInput::make('toolkit')->label('Toolkit')->reorderable(),
                        Forms\Components\TagsInput::make('skills')->label('Skills you will build')->reorderable(),
                        Forms\Components\TagsInput::make('learn')->label("What you'll learn")->reorderable(),
                        Forms\Components\TagsInput::make('who_should_apply')->label('Who should apply')->reorderable(),
                        Forms\Components\TagsInput::make('requirements')->reorderable(),
                        Forms\Components\TagsInput::make('career_paths')->label('Career paths')->reorderable(),
                    ]),
                    Fields::titleText('benefits', 'What you get'),
                    Fields::titleText('journey', 'Internship journey'),
                    Forms\Components\Textarea::make('career_growth')->label('Career growth')->rows(3),
                ]),
                Forms\Components\Tabs\Tab::make('Brands')->schema([
                    Forms\Components\Placeholder::make('confidentiality')->hiddenLabel()
                        ->content('Only brands that are Public, Approved, Active and enabled for internship pages are shown publicly. Private or internal-only brands can be linked here for internal records and will never appear on the website or in structured data.'),
                    Forms\Components\Toggle::make('show_brands')->label('Show "Brands you\'ll work with" section')->default(true),
                    Forms\Components\Select::make('brands')->label('Assigned brands')
                        ->relationship('brands', 'name')->multiple()->searchable()->preload()
                        ->getOptionLabelFromRecordUsing(fn (Brand $record) => $record->name.($record->isDisplayable() ? '' : ' (hidden publicly)'))
                        ->createOptionForm(BrandResource::formSchema())
                        ->createOptionModalHeading('Add new brand'),
                    Forms\Components\Grid::make(2)->schema([
                        Forms\Components\TextInput::make('brands_heading')->label('Section heading')->datalist(Internship::BRAND_HEADINGS)
                            ->placeholder(Internship::BRAND_HEADINGS[0]),
                        Forms\Components\TextInput::make('brands_tagline')->label('Supporting line')->placeholder('Learn by contributing to work that matters'),
                    ]),
                    Forms\Components\Textarea::make('brands_description')->label('Description')->rows(3)->placeholder(Internship::DEFAULT_BRANDS_DESCRIPTION),
                    Forms\Components\TagsInput::make('exposure')->label('What you may work on')->reorderable()
                        ->placeholder('e.g. SEO audits'),
                    Forms\Components\Grid::make(2)->schema([
                        Forms\Components\Toggle::make('show_industries')->label('Show industries exposure')->default(true),
                        Forms\Components\Toggle::make('show_statistics')->label('Show statistics')->default(false)
                            ->helperText('Uses Careers → Statistics. Only add figures you can verify.'),
                    ]),
                ]),
                Forms\Components\Tabs\Tab::make('Projects')->schema([
                    Forms\Components\Repeater::make('projectLinks')->label('Project experience')->relationship()
                        ->schema([
                            Forms\Components\Select::make('client_project_id')->label('Project')->required()->searchable()->preload()
                                ->relationship('project', 'title')
                                ->getOptionLabelFromRecordUsing(fn (ClientProject $record) => $record->title.' — '.($record->brand?->name ?? 'No brand')),
                            Forms\Components\Textarea::make('intern_contribution')->label('Intern contribution')->rows(2),
                        ])
                        ->orderColumn('sort_order')->collapsible()->defaultItems(0)
                        ->helperText('Hidden projects (private / internal) are never shown. A private brand is shown as "Confidential client".'),
                ]),
                Forms\Components\Tabs\Tab::make('FAQs')->schema([Fields::faqs()]),
            ]),
            Fields::seo(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->reorderable('sort_order')->defaultSort('sort_order')
            ->columns([
                Tables\Columns\TextColumn::make('title')->weight('bold')->searchable()->description(fn (Internship $record) => $record->department),
                Tables\Columns\TextColumn::make('work_mode')->formatStateUsing(fn ($state) => Internship::MODES[$state] ?? $state)->badge(),
                Tables\Columns\TextColumn::make('brands_count')->counts('brands')->label('Brands'),
                Tables\Columns\TextColumn::make('applications_count')->counts('applications')->label('Applications'),
                Tables\Columns\TextColumn::make('apply_by')->date()->placeholder('Rolling'),
                Fields::statusColumn(),
            ])
            ->actions([
                Tables\Actions\Action::make('view')->icon('heroicon-o-arrow-top-right-on-square')->url(fn (Internship $record) => $record->url(), true),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInternships::route('/'),
            'create' => Pages\CreateInternship::route('/create'),
            'edit' => Pages\EditInternship::route('/{record}/edit'),
        ];
    }
}
