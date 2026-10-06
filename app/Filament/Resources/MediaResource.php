<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MediaResource\Pages;
use App\Models\Media;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;

/**
 * Central media library: upload once, copy the URL anywhere (rich text, settings, SEO images).
 */
class MediaResource extends Resource
{
    protected static ?string $model = Media::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';

    protected static ?string $navigationGroup = 'Website Content';

    protected static ?string $navigationLabel = 'Media library';

    protected static ?int $navigationSort = 12;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\FileUpload::make('path')->label('File')->required()
                ->disk('public')->directory('media')->visibility('public')
                ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/svg+xml', 'image/gif', 'application/pdf'])
                ->maxSize(8192)
                ->storeFileNamesIn('name')
                ->columnSpanFull(),
            Forms\Components\TextInput::make('alt')->label('Alt text')->helperText('Describe the image for screen readers and search.')->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\ImageColumn::make('path')->disk('public')->label('')->height(48),
                Tables\Columns\TextColumn::make('name')->searchable()->limit(40),
                Tables\Columns\TextColumn::make('mime_type')->badge()->color('gray'),
                Tables\Columns\TextColumn::make('size')->formatStateUsing(fn ($state) => $state ? number_format($state / 1024).' KB' : '—'),
                Tables\Columns\TextColumn::make('alt')->limit(30)->placeholder('Missing alt text')->toggleable(),
                Tables\Columns\TextColumn::make('created_at')->since(),
            ])
            ->actions([
                Tables\Actions\Action::make('url')->label('Copy URL')->icon('heroicon-o-link')
                    ->action(fn () => null)
                    ->extraAttributes(fn (Media $record) => ['x-on:click' => 'navigator.clipboard.writeText('.json_encode($record->url()).'); $tooltip("Copied")']),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    /** Fill size and MIME type from the stored file. */
    public static function hydrateFileMeta(array $data): array
    {
        if (! empty($data['path']) && Storage::disk('public')->exists($data['path'])) {
            $data['size'] = Storage::disk('public')->size($data['path']);
            $data['mime_type'] = Storage::disk('public')->mimeType($data['path']);
        }
        $data['name'] ??= basename((string) ($data['path'] ?? ''));
        $data['uploaded_by'] ??= auth()->id();

        return $data;
    }

    public static function getPages(): array
    {
        return ['index' => Pages\ManageMedia::route('/')];
    }
}
