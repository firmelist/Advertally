<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\RestrictsToRoles;
use App\Filament\Resources\FaqResource\Pages;
use App\Models\Faq;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class FaqResource extends Resource
{
    use RestrictsToRoles;

    protected static array $roles = ['admin', 'editor'];

    protected static ?string $model = Faq::class;

    protected static ?string $navigationIcon = 'heroicon-o-question-mark-circle';

    protected static ?string $navigationGroup = 'Website Content';

    protected static ?int $navigationSort = 6;

    protected static ?string $navigationLabel = 'FAQs';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('page')->options(Faq::PAGES)->placeholder('— Service FAQ —')
                ->helperText('Choose a page, OR a service below.'),
            Forms\Components\Select::make('service_id')->relationship('service', 'title')->searchable()->preload(),
            Forms\Components\TextInput::make('question')->required()->columnSpanFull(),
            Forms\Components\Textarea::make('answer')->required()->rows(4)->columnSpanFull(),
            Forms\Components\Toggle::make('is_active')->default(true),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->reorderable('sort_order')->defaultSort('sort_order')
            ->columns([
                Tables\Columns\TextColumn::make('question')->searchable()->wrap()->weight('bold'),
                Tables\Columns\TextColumn::make('page')->badge()->placeholder('—'),
                Tables\Columns\TextColumn::make('service.title')->placeholder('—')->toggleable(),
                Tables\Columns\ToggleColumn::make('is_active')->label('Live'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('page')->options(Faq::PAGES),
                Tables\Filters\SelectFilter::make('service_id')->label('Service')->relationship('service', 'title')->searchable(),
            ])
            ->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()]);
    }

    public static function getPages(): array
    {
        return ['index' => Pages\ManageFaqs::route('/')];
    }
}
