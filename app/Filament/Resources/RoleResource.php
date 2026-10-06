<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RoleResource\Pages;
use App\Models\Permission;
use App\Models\Role;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class RoleResource extends Resource
{
    protected static ?string $model = Role::class;

    protected static ?string $navigationIcon = 'heroicon-o-shield-check';

    protected static ?string $navigationGroup = 'System';

    protected static ?string $navigationLabel = 'Roles & permissions';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make()->columns(2)->schema([
                Forms\Components\TextInput::make('label')->required(),
                Forms\Components\TextInput::make('name')->required()->alphaDash()->unique(ignoreRecord: true),
                Forms\Components\Textarea::make('description')->rows(2)->columnSpanFull(),
                Forms\Components\Toggle::make('is_super')->label('Super admin (bypasses all permissions)')
                    ->visible(fn () => auth()->user()?->isSuperAdmin())->live(),
            ]),
            Forms\Components\Section::make('Permissions')
                ->hidden(fn (Forms\Get $get) => $get('is_super'))
                ->schema([
                    Forms\Components\CheckboxList::make('permissions')
                        ->relationship('permissions', 'label', fn ($query) => $query->orderBy('group')->orderBy('id'))
                        ->columns(3)->bulkToggleable()->searchable()->hiddenLabel(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('label')->weight('bold')->description(fn ($record) => $record->description),
                Tables\Columns\IconColumn::make('is_super')->boolean()->label('Super'),
                Tables\Columns\TextColumn::make('permissions_count')->counts('permissions')->label('Permissions'),
                Tables\Columns\TextColumn::make('users_count')->counts('users')->label('Users'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()->hidden(fn (Role $record) => $record->is_super || $record->users()->exists()),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRoles::route('/'),
            'create' => Pages\CreateRole::route('/create'),
            'edit' => Pages\EditRole::route('/{record}/edit'),
        ];
    }
}
