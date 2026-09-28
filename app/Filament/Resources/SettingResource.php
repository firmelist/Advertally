<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\RestrictsToRoles;
use App\Filament\Resources\SettingResource\Pages;
use App\Models\Setting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SettingResource extends Resource
{
    use RestrictsToRoles;

    protected static array $roles = ['admin'];

    protected static ?string $model = Setting::class;

    protected static ?string $navigationIcon = 'heroicon-o-adjustments-horizontal';

    protected static ?string $navigationGroup = 'Settings';

    protected static ?string $navigationLabel = 'Site settings';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('label')->disabled()->dehydrated(false),
            Forms\Components\TextInput::make('key')->disabled()->dehydrated(false),
            Forms\Components\Textarea::make('value')->rows(3)->columnSpanFull(),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('group')
            ->groups(['group'])->defaultGroup('group')
            ->paginated(false)
            ->columns([
                Tables\Columns\TextColumn::make('label')->weight('bold')->description(fn ($record) => $record->key),
                Tables\Columns\TextInputColumn::make('value')->extraAttributes(['style' => 'min-width: 22rem']),
            ])
            ->actions([Tables\Actions\EditAction::make()]);
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canDelete(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return false;
    }

    public static function getPages(): array
    {
        return ['index' => Pages\ManageSettings::route('/')];
    }
}
