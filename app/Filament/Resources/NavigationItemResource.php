<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NavigationItemResource\Pages;
use App\Models\NavigationItem;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class NavigationItemResource extends Resource
{
    protected static ?string $model = NavigationItem::class;

    protected static ?string $navigationIcon = 'heroicon-o-bars-3';

    protected static ?string $navigationGroup = 'Site';

    protected static ?string $navigationLabel = 'Navigation';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('menu')->options(NavigationItem::MENUS)->required()->live()->native(false),
            Forms\Components\Select::make('parent_id')->label('Parent item')
                ->options(fn (Forms\Get $get) => NavigationItem::query()->where('menu', $get('menu'))
                    ->with('parent')->get()->mapWithKeys(fn ($i) => [$i->id => ($i->parent ? $i->parent->label.' › ' : '').$i->label]))
                ->searchable()->helperText('Header supports three levels: top item › group › link.'),
            Forms\Components\TextInput::make('label')->required(),
            Forms\Components\TextInput::make('url')->helperText('Relative (/solutions/demand) or absolute URL. Leave empty for a non-clickable group.'),
            Forms\Components\TextInput::make('description')->maxLength(200)->columnSpanFull(),
            Forms\Components\TextInput::make('icon')->helperText('Icon key, e.g. search, users'),
            Forms\Components\TextInput::make('sort_order')->numeric()->default(0),
            Forms\Components\Toggle::make('is_active')->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->with("parent"))
            ->reorderable('sort_order')->defaultSort('sort_order')
            ->columns([
                Tables\Columns\TextColumn::make('label')->weight('bold')->searchable()
                    ->description(fn (NavigationItem $record) => $record->parent ? 'in '.$record->parent->label : 'Top level'),
                Tables\Columns\TextColumn::make('url')->color('gray')->placeholder('—'),
                Tables\Columns\TextColumn::make('menu')->badge(),
                Tables\Columns\ToggleColumn::make('is_active')->label('Active'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('menu')->options(NavigationItem::MENUS)->default('header'),
            ])
            ->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()]);
    }

    public static function getPages(): array
    {
        return ['index' => Pages\ManageNavigationItems::route('/')];
    }
}
