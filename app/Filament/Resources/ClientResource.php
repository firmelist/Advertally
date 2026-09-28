<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\RestrictsToRoles;
use App\Filament\Resources\ClientResource\Pages;
use App\Models\Client;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ClientResource extends Resource
{
    use RestrictsToRoles;

    protected static array $roles = ['admin', 'sales'];

    protected static ?string $model = Client::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';

    protected static ?string $navigationGroup = 'Sales';

    protected static ?int $navigationSort = 3;

    protected static ?string $recordTitleAttribute = 'company';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->visibleTo(auth()->user());
    }

    public static function pillarOptions(): array
    {
        return collect(config('advertally.ladder'))->mapWithKeys(fn ($l, $k) => [$k => $l['step'].'. '.$l['title']])->all();
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Company')->columns(2)->schema([
                Forms\Components\TextInput::make('company')->required()->maxLength(190),
                Forms\Components\TextInput::make('contact_name')->maxLength(120),
                Forms\Components\TextInput::make('phone')->tel()->maxLength(20),
                Forms\Components\TextInput::make('email')->email(),
                Forms\Components\TextInput::make('city'),
                Forms\Components\Select::make('industry')->options(config('advertally.industries')),
                Forms\Components\Select::make('business_size')->options(config('advertally.business_sizes')),
                Forms\Components\Select::make('account_manager_id')->label('Account manager')
                    ->options(fn () => User::where('is_active', true)->pluck('name', 'id'))->searchable()
                    ->hidden(fn () => auth()->user()?->isSales()),
            ]),
            Forms\Components\Section::make('Engagement')->columns(3)->schema([
                Forms\Components\CheckboxList::make('active_pillars')->label('Services in use (growth ladder)')
                    ->options(static::pillarOptions())->columns(5)->columnSpanFull()
                    ->helperText('Unticked steps show up as upsell opportunities on the dashboard.'),
                Forms\Components\TextInput::make('monthly_value')->label('Monthly value (₹)')->numeric()->prefix('₹')->default(0),
                Forms\Components\DatePicker::make('since')->native(false),
                Forms\Components\Select::make('status')->options(['active' => 'Active', 'paused' => 'Paused', 'churned' => 'Churned'])->default('active')->required(),
                Forms\Components\Textarea::make('notes')->rows(3)->columnSpanFull(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('monthly_value', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('company')->searchable()->sortable()->weight('bold')->description(fn (Client $r) => $r->contact_name),
                Tables\Columns\TextColumn::make('active_pillars')->label('Using')->badge()
                    ->formatStateUsing(fn ($state) => config("advertally.ladder.{$state}.label") ?? $state),
                Tables\Columns\TextColumn::make('next_pillar_label')->label('Upsell next')->badge()->color('warning')->placeholder('Full ladder ✓'),
                Tables\Columns\TextColumn::make('monthly_value')->label('MRR')->money('INR')->sortable()
                    ->summarize(Tables\Columns\Summarizers\Sum::make()->money('INR')->label('Total MRR')),
                Tables\Columns\TextColumn::make('accountManager.name')->label('Manager')->toggleable(),
                Tables\Columns\TextColumn::make('status')->badge()->color(fn ($state) => ['active' => 'success', 'paused' => 'warning', 'churned' => 'gray'][$state] ?? 'gray'),
                Tables\Columns\TextColumn::make('since')->date('M Y')->sortable()->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')->options(['active' => 'Active', 'paused' => 'Paused', 'churned' => 'Churned'])->default('active'),
                Tables\Filters\SelectFilter::make('missing_pillar')->label('Not yet using')
                    ->options(static::pillarOptions())
                    ->query(fn (Builder $query, array $data) => $query->when($data['value'] ?? null, fn ($q, $p) => $q->where(fn ($q) => $q->whereNull('active_pillars')->orWhere('active_pillars', 'not like', '%"'.$p.'"%')))),
            ])
            ->actions([
                Tables\Actions\Action::make('whatsapp')->label('')->icon('heroicon-o-chat-bubble-left-right')->color('success')
                    ->visible(fn (Client $r) => filled($r->phone))
                    ->url(fn (Client $r) => 'https://wa.me/'.(strlen($p = preg_replace('/\D/', '', (string) $r->phone)) === 10 ? '91'.$p : $p), true),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([Tables\Actions\DeleteBulkAction::make()->visible(fn () => auth()->user()?->isAdmin())]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListClients::route('/'),
            'create' => Pages\CreateClient::route('/create'),
            'edit' => Pages\EditClient::route('/{record}/edit'),
        ];
    }
}
