<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LeadResource\Pages;
use App\Filament\Resources\LeadResource\RelationManagers\ActivitiesRelationManager;
use App\Models\Lead;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class LeadResource extends Resource
{
    protected static ?string $model = Lead::class;

    protected static ?string $navigationIcon = 'heroicon-o-bolt';

    protected static ?string $navigationGroup = 'Growth';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'name';

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'email', 'company'];
    }

    public static function getNavigationBadge(): ?string
    {
        $count = static::getEloquentQuery()->where('status', 'new')->count();

        return $count ? (string) $count : null;
    }

    /** Growth consultants only see leads assigned to them. */
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = auth()->user();

        if ($user && ! $user->isSuperAdmin() && $user->role?->name === 'growth-consultant') {
            $query->where('assigned_to', $user->id);
        }

        return $query;
    }

    public static function form(Form $form): Form
    {
        $opts = fn (string $key) => config("advertally.{$key}");

        return $form->schema([
            Forms\Components\Section::make('Contact')->columns(3)->schema([
                Forms\Components\TextInput::make('name')->required(),
                Forms\Components\TextInput::make('email')->email()->required(),
                Forms\Components\TextInput::make('phone'),
                Forms\Components\TextInput::make('company'),
                Forms\Components\TextInput::make('job_title'),
                Forms\Components\TextInput::make('website'),
            ]),
            Forms\Components\Section::make('Qualification')->columns(3)->schema([
                Forms\Components\Select::make('industry')->options($opts('industries'))->native(false),
                Forms\Components\Select::make('service_interest')->options($opts('service_interests'))->native(false),
                Forms\Components\Select::make('budget')->options($opts('budgets'))->native(false),
                Forms\Components\Select::make('challenge')->options($opts('challenges'))->native(false),
                Forms\Components\Select::make('objective')->options($opts('objectives'))->native(false),
                Forms\Components\Select::make('form_type')->options(Lead::FORM_TYPES)->default('contact')->native(false),
                Forms\Components\Textarea::make('message')->rows(3)->columnSpanFull(),
            ]),
            Forms\Components\Section::make('Pipeline')->columns(3)->schema([
                Forms\Components\Select::make('status')->options(Lead::STATUSES)->required()->native(false),
                Forms\Components\Select::make('assigned_to')->label('Owner')->options(fn () => User::query()->where('is_active', true)->pluck('name', 'id'))->native(false),
                Forms\Components\TextInput::make('estimated_value')->numeric()->prefix('₹'),
                Forms\Components\DateTimePicker::make('next_follow_up_at')->native(false),
                Forms\Components\TextInput::make('score')->numeric()->minValue(0)->maxValue(100)->helperText('Fit score (0–100)'),
                Forms\Components\Textarea::make('notes')->rows(3)->columnSpanFull(),
            ]),
        ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        $label = fn (string $field) => fn (Lead $record) => $record->label($field);

        return $infolist->schema([
            Infolists\Components\Section::make('Contact')->columns(3)->schema([
                Infolists\Components\TextEntry::make('name')->weight('bold'),
                Infolists\Components\TextEntry::make('email')->copyable()->url(fn ($record) => "mailto:{$record->email}"),
                Infolists\Components\TextEntry::make('phone')->placeholder('—'),
                Infolists\Components\TextEntry::make('company')->placeholder('—'),
                Infolists\Components\TextEntry::make('job_title')->placeholder('—'),
                Infolists\Components\TextEntry::make('website')->placeholder('—')->url(fn ($record) => $record->website ? (str_starts_with($record->website, 'http') ? $record->website : "https://{$record->website}") : null, true),
            ]),
            Infolists\Components\Section::make('Request')->columns(3)->schema([
                Infolists\Components\TextEntry::make('form_type')->badge()->state($label('form_type')),
                Infolists\Components\TextEntry::make('industry')->state($label('industry'))->placeholder('—'),
                Infolists\Components\TextEntry::make('service_interest')->state($label('service_interest'))->placeholder('—'),
                Infolists\Components\TextEntry::make('challenge')->state($label('challenge'))->placeholder('—'),
                Infolists\Components\TextEntry::make('objective')->state($label('objective'))->placeholder('—'),
                Infolists\Components\TextEntry::make('budget')->state($label('budget'))->placeholder('—'),
                Infolists\Components\TextEntry::make('message')->columnSpanFull()->placeholder('—'),
            ]),
            Infolists\Components\Section::make('Attribution')->columns(3)->collapsible()->schema([
                Infolists\Components\TextEntry::make('source')->badge(),
                Infolists\Components\TextEntry::make('first_touch_source')->label('First touch')->placeholder('—'),
                Infolists\Components\TextEntry::make('last_touch_source')->label('Last touch')->placeholder('—'),
                Infolists\Components\TextEntry::make('utm_source')->placeholder('—'),
                Infolists\Components\TextEntry::make('utm_medium')->placeholder('—'),
                Infolists\Components\TextEntry::make('utm_campaign')->placeholder('—'),
                Infolists\Components\TextEntry::make('utm_term')->placeholder('—'),
                Infolists\Components\TextEntry::make('utm_content')->placeholder('—'),
                Infolists\Components\TextEntry::make('device')->placeholder('—'),
                Infolists\Components\TextEntry::make('landing_page')->columnSpanFull()->placeholder('—'),
                Infolists\Components\TextEntry::make('first_touch_landing_page')->label('First landing page')->columnSpanFull()->placeholder('—'),
                Infolists\Components\TextEntry::make('source_page')->label('Form submitted on')->columnSpanFull()->placeholder('—'),
                Infolists\Components\TextEntry::make('referrer')->columnSpanFull()->placeholder('—'),
            ]),
            Infolists\Components\Section::make('Pipeline')->columns(4)->schema([
                Infolists\Components\TextEntry::make('status')->badge()->state($label('status')),
                Infolists\Components\TextEntry::make('score')->label('Fit score')->suffix('/100'),
                Infolists\Components\TextEntry::make('assignee.name')->label('Owner')->placeholder('Unassigned'),
                Infolists\Components\TextEntry::make('created_at')->dateTime(),
                Infolists\Components\TextEntry::make('notes')->columnSpanFull()->placeholder('—'),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('name')->weight('bold')->searchable()->description(fn (Lead $record) => $record->company),
                Tables\Columns\TextColumn::make('email')->searchable()->toggleable(),
                Tables\Columns\TextColumn::make('form_type')->badge()->formatStateUsing(fn ($state) => Lead::FORM_TYPES[$state] ?? $state),
                Tables\Columns\TextColumn::make('service_interest')->formatStateUsing(fn ($state) => config("advertally.service_interests.{$state}", $state))->toggleable()->wrap(),
                Tables\Columns\TextColumn::make('source')->badge()->color('gray'),
                Tables\Columns\TextColumn::make('score')->label('Fit')->sortable()->color(fn ($state) => $state >= 60 ? 'success' : ($state >= 35 ? 'warning' : 'gray')),
                Tables\Columns\SelectColumn::make('status')->options(Lead::STATUSES)->selectablePlaceholder(false),
                Tables\Columns\TextColumn::make('assignee.name')->label('Owner')->placeholder('—')->toggleable(),
                Tables\Columns\TextColumn::make('created_at')->since()->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')->options(Lead::STATUSES)->multiple(),
                Tables\Filters\SelectFilter::make('form_type')->options(Lead::FORM_TYPES),
                Tables\Filters\SelectFilter::make('industry')->options(config('advertally.industries')),
                Tables\Filters\SelectFilter::make('assigned_to')->label('Owner')->relationship('assignee', 'name'),
                Tables\Filters\Filter::make('created_at')->form([
                    Forms\Components\DatePicker::make('from'), Forms\Components\DatePicker::make('until'),
                ])->query(fn (Builder $q, array $data) => $q
                    ->when($data['from'] ?? null, fn ($q, $d) => $q->whereDate('created_at', '>=', $d))
                    ->when($data['until'] ?? null, fn ($q, $d) => $q->whereDate('created_at', '<=', $d))),
            ])
            ->actions([Tables\Actions\ViewAction::make(), Tables\Actions\EditAction::make()])
            ->bulkActions([
                Tables\Actions\BulkAction::make('export')->label('Export CSV')->icon('heroicon-o-arrow-down-tray')
                    ->action(fn ($records) => response()->streamDownload(function () use ($records) {
                        $out = fopen('php://output', 'w');
                        fputcsv($out, ['Name', 'Email', 'Phone', 'Company', 'Website', 'Industry', 'Interest', 'Budget', 'Status', 'Fit', 'Form', 'Source', 'First touch', 'UTM campaign', 'Created']);
                        foreach ($records as $record) {
                            fputcsv($out, [$record->name, $record->email, $record->phone, $record->company, $record->website, $record->label('industry'), $record->label('service_interest'), $record->label('budget'), $record->label('status'), $record->score, $record->label('form_type'), $record->source, $record->first_touch_source, $record->utm_campaign, $record->created_at]);
                        }
                        fclose($out);
                    }, 'advertally-leads-'.now()->format('Y-m-d').'.csv')),
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [ActivitiesRelationManager::class];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLeads::route('/'),
            'create' => Pages\CreateLead::route('/create'),
            'view' => Pages\ViewLead::route('/{record}'),
            'edit' => Pages\EditLead::route('/{record}/edit'),
        ];
    }
}
