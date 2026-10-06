<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TestimonialResource\Pages;
use App\Filament\Support\Fields;
use App\Models\Testimonial;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TestimonialResource extends Resource
{
    protected static ?string $model = Testimonial::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    protected static ?string $navigationGroup = 'Website Content';

    protected static ?int $navigationSort = 10;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Placeholder::make('rule')->hiddenLabel()->columnSpanFull()
                ->content('Publish only genuine testimonials with the client\'s written permission. Testimonials stay hidden on the site until published.'),
            Forms\Components\TextInput::make('name')->required(),
            Forms\Components\TextInput::make('role'),
            Forms\Components\TextInput::make('company'),
            Fields::image('photo', 'Photo', 'testimonials'),
            Forms\Components\Textarea::make('quote')->required()->rows(4)->columnSpanFull(),
            Forms\Components\Toggle::make('is_published')->label('Published (client approved)'),
            Fields::sortOrder(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->reorderable('sort_order')->defaultSort('sort_order')
            ->columns([
                Tables\Columns\TextColumn::make('name')->weight('bold')->description(fn ($record) => collect([$record->role, $record->company])->filter()->implode(', ')),
                Tables\Columns\TextColumn::make('quote')->limit(80)->wrap(),
                Tables\Columns\ToggleColumn::make('is_published')->label('Published'),
            ])
            ->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()]);
    }

    public static function getPages(): array
    {
        return ['index' => Pages\ManageTestimonials::route('/')];
    }
}
