<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClientProjectResource\Pages;
use App\Filament\Support\Fields;
use App\Models\Brand;
use App\Models\ClientProject;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ClientProjectResource extends Resource
{
    protected static ?string $model = ClientProject::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

    protected static ?string $navigationGroup = 'Careers';

    protected static ?string $navigationLabel = 'Client projects';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Project')->columns(2)->schema([
                Forms\Components\TextInput::make('title')->required()->maxLength(190)->columnSpanFull(),
                Forms\Components\Select::make('brand_id')->label('Brand / client')->relationship('brand', 'name')->searchable()->preload()
                    ->helperText('If the brand is private, the public page shows "Confidential client" instead of its name.'),
                Forms\Components\TextInput::make('department')->datalist(['Digital Marketing', 'Development', 'Design', 'AI & Automation', 'Analytics']),
                Forms\Components\TextInput::make('industry'),
                Forms\Components\Select::make('status')->options(['active' => 'Active', 'inactive' => 'Inactive'])->default('active')->required()->native(false),
                Forms\Components\Textarea::make('description')->rows(3)->columnSpanFull(),
                Forms\Components\TagsInput::make('services'),
                Forms\Components\TagsInput::make('technologies'),
                Fields::image('image', 'Image (optional)', 'projects')->columnSpanFull(),
            ]),
            Forms\Components\Section::make('Confidentiality & display')->columns(2)->schema([
                Forms\Components\Radio::make('is_public')->label('Public visibility')->options([1 => 'Public', 0 => 'Private'])->default(0)->inline()->required(),
                Forms\Components\Radio::make('display_permission')->label('Display permission')->options(Brand::PERMISSIONS)->default('internal')->required(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->weight('bold')->searchable()->wrap(),
                Tables\Columns\TextColumn::make('brand.name')->label('Brand')->placeholder('—'),
                Tables\Columns\TextColumn::make('department')->badge(),
                Tables\Columns\TextColumn::make('visibility')->label('Public page')
                    ->state(fn (ClientProject $record) => $record->is_public && $record->display_permission === 'approved' && $record->status === 'active' ? 'Shown' : 'Hidden')
                    ->badge()->color(fn ($state) => $state === 'Shown' ? 'success' : 'gray'),
                Tables\Columns\TextColumn::make('internship_links_count')->counts('internshipLinks')->label('Internships'),
            ])
            ->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListClientProjects::route('/'),
            'create' => Pages\CreateClientProject::route('/create'),
            'edit' => Pages\EditClientProject::route('/{record}/edit'),
        ];
    }
}
