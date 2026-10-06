<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Validation\Rules\Password;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'System';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')->required(),
            Forms\Components\TextInput::make('email')->email()->required()->unique(ignoreRecord: true),
            Forms\Components\TextInput::make('phone')->tel(),
            // Only super admins can grant super-admin roles.
            Forms\Components\Select::make('role_id')->label('Role')->required()->preload()->native(false)
                ->relationship('role', 'label', fn ($query) => auth()->user()?->isSuperAdmin() ? $query : $query->where('is_super', false)),
            Forms\Components\TextInput::make('password')->password()->revealable()
                ->rule(Password::min(10)->mixedCase()->numbers())
                ->required(fn (string $operation) => $operation === 'create')
                ->dehydrated(fn ($state) => filled($state))
                ->helperText('Minimum 10 characters with upper/lower case and a number. Leave blank to keep the current password.'),
            Forms\Components\Toggle::make('is_active')->default(true)->helperText('Inactive users cannot sign in.'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->weight('bold')->searchable()->description(fn ($record) => $record->email),
                Tables\Columns\TextColumn::make('role.label')->badge(),
                Tables\Columns\IconColumn::make('is_active')->boolean(),
                Tables\Columns\TextColumn::make('last_login_at')->since()->placeholder('Never'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()->hidden(fn (User $record) => $record->is(auth()->user())),
            ]);
    }

    public static function getPages(): array
    {
        return ['index' => Pages\ManageUsers::route('/')];
    }
}
