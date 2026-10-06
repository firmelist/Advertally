<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Filament\Support\Fields;
use App\Models\Post;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';

    protected static ?string $navigationGroup = 'Website Content';

    protected static ?string $navigationLabel = 'Insights';

    protected static ?string $modelLabel = 'insight';

    protected static ?int $navigationSort = 6;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Group::make()->columnSpan(2)->schema([
                Forms\Components\Section::make()->schema([
                    Fields::title(),
                    Fields::slug('posts'),
                    Forms\Components\Textarea::make('excerpt')->rows(3)->maxLength(400),
                    Fields::richText('content', 'Content'),
                ]),
                Fields::seo(),
            ]),
            Forms\Components\Group::make()->schema([
                Forms\Components\Section::make('Publishing')->schema([
                    Fields::status(),
                    Forms\Components\DateTimePicker::make('published_at')->native(false)->default(now()),
                    Forms\Components\Toggle::make('is_featured')->label('Featured'),
                    Forms\Components\Select::make('type')->options(Post::TYPES)->default('article')->required()->native(false),
                    Forms\Components\Select::make('post_category_id')->label('Category')->relationship('category', 'name')->preload()->native(false)->createOptionForm([
                        Forms\Components\TextInput::make('name')->required(), Forms\Components\TextInput::make('slug')->required(),
                    ]),
                    Forms\Components\Select::make('author_id')->relationship('author', 'name')->preload()->native(false),
                ]),
                Forms\Components\Section::make('Semantic links')->description('Connects this insight to services and industries (internal linking + schema).')->schema([
                    Forms\Components\Select::make('services')->relationship('services', 'title')->multiple()->preload()->searchable(),
                    Forms\Components\Select::make('industries')->relationship('industries', 'name')->multiple()->preload(),
                    Forms\Components\TagsInput::make('tags'),
                ]),
                Forms\Components\Section::make('Image')->schema([Fields::image('featured_image', 'Featured image', 'insights')]),
            ]),
        ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('published_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('title')->weight('bold')->searchable()->wrap(),
                Tables\Columns\TextColumn::make('category.name')->badge(),
                Tables\Columns\TextColumn::make('type')->formatStateUsing(fn ($state) => Post::TYPES[$state] ?? $state),
                Tables\Columns\TextColumn::make('author.name')->toggleable(),
                Fields::statusColumn(),
                Tables\Columns\TextColumn::make('published_at')->date()->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('post_category_id')->label('Category')->relationship('category', 'name'),
                Tables\Filters\SelectFilter::make('type')->options(Post::TYPES),
                Tables\Filters\SelectFilter::make('status')->options(['draft' => 'Draft', 'published' => 'Published']),
            ])
            ->actions([
                Tables\Actions\Action::make('view')->icon('heroicon-o-arrow-top-right-on-square')->url(fn (Post $record) => $record->url())->openUrlInNewTab(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
