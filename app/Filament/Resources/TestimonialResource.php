<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\RestrictsToRoles;
use App\Filament\Resources\TestimonialResource\Pages;
use App\Models\Testimonial;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TestimonialResource extends Resource
{
    use RestrictsToRoles;

    protected static array $roles = ['admin', 'editor'];

    protected static ?string $model = Testimonial::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-bottom-center-text';

    protected static ?string $navigationGroup = 'Website Content';

    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make()->columns(2)->description('Only publish genuine testimonials you have written permission to use.')->schema([
                Forms\Components\TextInput::make('name')->required(),
                Forms\Components\TextInput::make('designation'),
                Forms\Components\TextInput::make('company'),
                Forms\Components\TextInput::make('city'),
                Forms\Components\Select::make('industry')->options(config('advertally.industries')),
                Forms\Components\Select::make('rating')->options([5 => '★★★★★', 4 => '★★★★'])->default(5),
                Forms\Components\Textarea::make('quote')->required()->rows(4)->columnSpanFull(),
                Forms\Components\FileUpload::make('photo')->image()->avatar()->disk('public')->directory('testimonials')->maxSize(1024),
                Forms\Components\TextInput::make('video_url')->url()->label('Video URL (YouTube)'),
                Forms\Components\Toggle::make('is_active')->default(true),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->reorderable('sort_order')->defaultSort('sort_order')
            ->columns([
                Tables\Columns\ImageColumn::make('photo')->disk('public')->circular()->defaultImageUrl(fn ($record) => 'https://ui-avatars.com/api/?background=DCE6FF&color=1F3FA6&name='.urlencode($record->name)),
                Tables\Columns\TextColumn::make('name')->weight('bold')->description(fn ($record) => $record->company)->searchable(),
                Tables\Columns\TextColumn::make('quote')->limit(80)->wrap(),
                Tables\Columns\ToggleColumn::make('is_active')->label('Live'),
            ])
            ->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()]);
    }

    public static function getPages(): array
    {
        return ['index' => Pages\ManageTestimonials::route('/')];
    }
}
