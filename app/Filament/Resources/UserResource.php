<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\RestrictsToRoles;
use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class UserResource extends Resource
{
    use RestrictsToRoles;

    protected static array $roles = ['admin'];

    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'Settings';

    protected static ?string $navigationLabel = 'Team & roles';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')->required(),
            Forms\Components\TextInput::make('email')->email()->required()->unique(ignoreRecord: true),
            Forms\Components\TextInput::make('phone')->tel()->helperText('WhatsApp number — receives new-lead alerts for leads assigned to this user.'),
            Forms\Components\Select::make('role')->options(User::ROLES)->required()->default('sales')
                ->helperText('Sales: only their own leads & clients. Editor: website content. Admin: everything.'),
            Forms\Components\TextInput::make('password')->password()->revealable()
                ->rule(\Illuminate\Validation\Rules\Password::defaults())
                ->required(fn (string $operation) => $operation === 'create')
                ->dehydrated(fn (?string $state) => filled($state))
                ->helperText('Leave blank to keep the current password.'),
            Forms\Components\Toggle::make('is_active')->default(true)->helperText('Inactive users cannot log in and get no new leads.'),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->weight('bold')->description(fn ($record) => $record->email)->searchable(),
                Tables\Columns\TextColumn::make('role')->badge()->formatStateUsing(fn ($state) => User::ROLES[$state] ?? $state)
                    ->color(fn ($state) => ['admin' => 'danger', 'editor' => 'info', 'sales' => 'success'][$state] ?? 'gray'),
                Tables\Columns\TextColumn::make('leads_count')->counts('leads')->label('Leads'),
                Tables\Columns\IconColumn::make('is_active')->boolean(),
            ])
            ->actions([Tables\Actions\EditAction::make()]);
    }

    public static function getPages(): array
    {
        return ['index' => Pages\ManageUsers::route('/')];
    }
}
