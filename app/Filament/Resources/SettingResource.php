<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SettingResource\Pages;
use App\Models\Setting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SettingResource extends Resource
{
    protected static ?string $model = Setting::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationGroup = 'Site';

    protected static ?int $navigationSort = 3;

    public static function canCreate(): bool
    {
        return auth()->user()?->isSuperAdmin() ?? false;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('label')->required()->disabledOn('edit')->dehydrated(),
            Forms\Components\TextInput::make('key')->required()->alphaDash()->disabledOn('edit')->dehydrated(),
            Forms\Components\Select::make('group')->options(Setting::GROUPS)->default('general')->native(false)->disabledOn('edit')->dehydrated(),
            Forms\Components\Select::make('type')->options(['text' => 'Text', 'textarea' => 'Long text', 'url' => 'URL', 'email' => 'Email'])->default('text')->live()->native(false)->disabledOn('edit')->dehydrated(),
            Forms\Components\Textarea::make('value')->rows(fn (Forms\Get $get) => $get('type') === 'textarea' ? 4 : 1)->columnSpanFull()
                ->rules(fn (Forms\Get $get) => match ($get('type')) { 'url' => ['nullable', 'url'], 'email' => ['nullable', 'email'], default => ['nullable'] })
                ->helperText('Leave blank to hide this item on the website.'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultGroup('group')
            ->columns([
                Tables\Columns\TextColumn::make('label')->weight('bold')->description(fn ($record) => $record->key),
                Tables\Columns\TextColumn::make('value')->limit(60)->placeholder('Not set — hidden on site'),
            ])
            ->actions([Tables\Actions\EditAction::make()]);
    }

    public static function getPages(): array
    {
        return ['index' => Pages\ManageSettings::route('/')];
    }
}
