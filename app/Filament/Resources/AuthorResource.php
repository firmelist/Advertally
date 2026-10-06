<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AuthorResource\Pages;
use App\Filament\Support\Fields;
use App\Models\Author;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AuthorResource extends Resource
{
    protected static ?string $model = Author::class;

    protected static ?string $navigationIcon = 'heroicon-o-identification';

    protected static ?string $navigationGroup = 'Website Content';

    protected static ?int $navigationSort = 9;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Fields::title('name', 'Name'),
            Fields::slug('authors'),
            Forms\Components\Select::make('type')->options(['person' => 'Person (Person schema)', 'team' => 'Team (Organization schema)'])->default('person')->required()->native(false),
            Forms\Components\TextInput::make('job_title'),
            Forms\Components\Textarea::make('bio')->rows(3)->columnSpanFull(),
            Forms\Components\TagsInput::make('expertise')->columnSpanFull(),
            Forms\Components\TextInput::make('linkedin_url')->url(),
            Forms\Components\TextInput::make('x_url')->label('X URL')->url(),
            Fields::image('photo', 'Photo', 'authors'),
            Forms\Components\Select::make('user_id')->label('Linked admin user')->relationship('user', 'name')->preload()->native(false),
            Forms\Components\Toggle::make('is_active')->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('photo')->disk('public')->circular(),
                Tables\Columns\TextColumn::make('name')->weight('bold')->description(fn ($record) => $record->job_title),
                Tables\Columns\TextColumn::make('type')->badge(),
                Tables\Columns\TextColumn::make('posts_count')->counts('posts')->label('Insights'),
                Tables\Columns\IconColumn::make('is_active')->boolean(),
            ])
            ->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()]);
    }

    public static function getPages(): array
    {
        return ['index' => Pages\ManageAuthors::route('/')];
    }
}
