<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ActivityLogResource\Pages;
use App\Models\ActivityLog;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ActivityLogResource extends Resource
{
    protected static ?string $model = ActivityLog::class;

    protected static ?string $navigationIcon = 'heroicon-o-clock';

    protected static ?string $navigationGroup = 'System';

    protected static ?string $navigationLabel = 'Activity log';

    protected static ?int $navigationSort = 3;

    public static function canCreate(): bool
    {
        return false;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('created_at')->dateTime('d M Y, H:i')->sortable(),
                Tables\Columns\TextColumn::make('user.name')->placeholder('System')->searchable(),
                Tables\Columns\TextColumn::make('action')->badge()->color(fn ($state) => match ($state) {
                    'created' => 'success', 'deleted' => 'danger', 'login' => 'info', default => 'gray',
                }),
                Tables\Columns\TextColumn::make('description')->searchable(),
                Tables\Columns\TextColumn::make('properties')->label('Changed fields')
                    ->state(fn (ActivityLog $record) => implode(', ', $record->properties['fields'] ?? []))->limit(60)->toggleable(),
                Tables\Columns\TextColumn::make('ip_address')->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('action')->options(['created' => 'Created', 'updated' => 'Updated', 'deleted' => 'Deleted', 'login' => 'Login']),
                Tables\Filters\SelectFilter::make('user_id')->label('User')->relationship('user', 'name'),
            ]);
    }

    public static function getPages(): array
    {
        return ['index' => Pages\ListActivityLogs::route('/')];
    }
}
