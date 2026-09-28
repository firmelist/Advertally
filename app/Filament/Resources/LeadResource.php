<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\RestrictsToRoles;
use App\Filament\Resources\LeadResource\Pages;
use App\Filament\Resources\LeadResource\RelationManagers\NotesRelationManager;
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
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;

class LeadResource extends Resource
{
    use RestrictsToRoles;

    protected static array $roles = ['admin', 'sales'];

    protected static ?string $model = Lead::class;

    protected static ?string $navigationIcon = 'heroicon-o-bolt';

    protected static ?string $navigationGroup = 'Sales';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'name';

    /** Sales executives only ever see leads assigned to them. */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->visibleTo(auth()->user());
    }

    public static function getNavigationBadge(): ?string
    {
        $count = static::getEloquentQuery()->where('status', 'new')->count();

        return $count ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'company', 'phone', 'email'];
    }

    public static function statusColor(?string $status): string
    {
        return match ($status) {
            'new' => 'warning',
            'contacted' => 'info',
            'qualified' => 'primary',
            'proposal' => 'primary',
            'won' => 'success',
            'lost' => 'gray',
            default => 'gray',
        };
    }

    public static function scoreColor(int $score): string
    {
        return $score >= 60 ? 'danger' : ($score >= 35 ? 'warning' : 'gray');
    }

    public static function form(Form $form): Form
    {
        $isSales = fn () => auth()->user()?->isSales();

        return $form->schema([
            Forms\Components\Group::make([
                Forms\Components\Section::make('Contact')->columns(2)->schema([
                    Forms\Components\TextInput::make('name')->required()->maxLength(120),
                    Forms\Components\TextInput::make('company')->maxLength(190),
                    Forms\Components\TextInput::make('phone')->tel()->required()->maxLength(20),
                    Forms\Components\TextInput::make('email')->email()->maxLength(190),
                    Forms\Components\TextInput::make('city')->maxLength(80),
                    Forms\Components\TextInput::make('website')->maxLength(255),
                ]),
                Forms\Components\Section::make('Requirement')->columns(2)->schema([
                    Forms\Components\Select::make('business_size')->options(config('advertally.business_sizes')),
                    Forms\Components\Select::make('industry')->options(config('advertally.industries'))->searchable(),
                    Forms\Components\Select::make('budget')->options(config('advertally.budgets')),
                    Forms\Components\Select::make('form_type')->options(Lead::FORM_TYPES)->default('contact')->required(),
                    Forms\Components\CheckboxList::make('services')->options(Lead::SERVICE_OPTIONS)->columns(3)->columnSpanFull(),
                    Forms\Components\Textarea::make('message')->rows(4)->columnSpanFull(),
                ]),
            ])->columnSpan(['lg' => 2]),

            Forms\Components\Group::make([
                Forms\Components\Section::make('Pipeline')->schema([
                    Forms\Components\Select::make('status')->options(config('advertally.lead_statuses'))->default('new')->required()->live(),
                    Forms\Components\TextInput::make('lost_reason')->visible(fn (Forms\Get $get) => $get('status') === 'lost'),
                    Forms\Components\Select::make('assigned_to')->label('Assigned to')
                        ->options(fn () => User::where('is_active', true)->whereIn('role', ['admin', 'sales'])->pluck('name', 'id'))
                        ->searchable()->hidden($isSales),
                    Forms\Components\DateTimePicker::make('next_follow_up_at')->label('Next follow-up')->seconds(false)->native(false),
                    Forms\Components\TextInput::make('deal_value')->label('Deal value (₹)')->numeric()->prefix('₹'),
                    Forms\Components\TextInput::make('score')->numeric()->minValue(0)->maxValue(100)->default(30)
                        ->helperText('Auto-calculated for website leads. 60+ = hot.'),
                ]),
                Forms\Components\Section::make('Attribution')->collapsed()->schema([
                    Forms\Components\TextInput::make('utm_source')->label('Source'),
                    Forms\Components\TextInput::make('utm_medium')->label('Medium'),
                    Forms\Components\TextInput::make('utm_campaign')->label('Campaign'),
                    Forms\Components\TextInput::make('source_page')->label('Form page')->disabled(),
                    Forms\Components\TextInput::make('landing_page')->disabled(),
                    Forms\Components\TextInput::make('referrer')->disabled(),
                ]),
            ])->columnSpan(['lg' => 1]),
        ])->columns(3);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Infolists\Components\Group::make([
                Infolists\Components\Section::make('Contact')->columns(3)->schema([
                    Infolists\Components\TextEntry::make('name')->weight('bold'),
                    Infolists\Components\TextEntry::make('company')->placeholder('—'),
                    Infolists\Components\TextEntry::make('phone')->copyable()->url(fn (Lead $r) => 'tel:'.$r->phone),
                    Infolists\Components\TextEntry::make('email')->copyable()->placeholder('—'),
                    Infolists\Components\TextEntry::make('city')->placeholder('—'),
                    Infolists\Components\TextEntry::make('website')->url(fn (Lead $r) => $r->website && str_starts_with($r->website, 'http') ? $r->website : null, true)->placeholder('—'),
                ]),
                Infolists\Components\Section::make('Requirement')->columns(3)->schema([
                    Infolists\Components\TextEntry::make('business_size')->formatStateUsing(fn ($state) => config('advertally.business_sizes')[$state] ?? $state)->placeholder('—'),
                    Infolists\Components\TextEntry::make('industry')->formatStateUsing(fn ($state) => config('advertally.industries')[$state] ?? $state)->placeholder('—'),
                    Infolists\Components\TextEntry::make('budget')->formatStateUsing(fn ($state) => config('advertally.budgets')[$state] ?? $state)->placeholder('—'),
                    Infolists\Components\TextEntry::make('services')->badge()->formatStateUsing(fn ($state) => Lead::SERVICE_OPTIONS[$state] ?? $state)->columnSpanFull()->placeholder('—'),
                    Infolists\Components\TextEntry::make('message')->columnSpanFull()->placeholder('—')->extraAttributes(['class' => 'whitespace-pre-line']),
                ]),
            ])->columnSpan(['lg' => 2]),
            Infolists\Components\Group::make([
                Infolists\Components\Section::make('Pipeline')->schema([
                    Infolists\Components\TextEntry::make('status')->badge()->formatStateUsing(fn ($state) => config('advertally.lead_statuses')[$state] ?? $state)->color(fn ($state) => static::statusColor($state)),
                    Infolists\Components\TextEntry::make('score')->badge()->color(fn ($state) => static::scoreColor((int) $state))->suffix('/100'),
                    Infolists\Components\TextEntry::make('assignee.name')->label('Assigned to')->placeholder('Unassigned'),
                    Infolists\Components\TextEntry::make('next_follow_up_at')->label('Next follow-up')->dateTime('d M Y, h:i A')->placeholder('—'),
                    Infolists\Components\TextEntry::make('deal_value')->money('INR')->placeholder('—'),
                    Infolists\Components\TextEntry::make('created_at')->label('Received')->since(),
                ]),
                Infolists\Components\Section::make('Attribution')->collapsible()->schema([
                    Infolists\Components\TextEntry::make('form_type')->formatStateUsing(fn ($state) => Lead::FORM_TYPES[$state] ?? $state),
                    Infolists\Components\TextEntry::make('utm_source')->label('Source / medium')->formatStateUsing(fn (Lead $r) => trim(($r->utm_source ?: 'direct').' / '.($r->utm_medium ?: '—')))->default('direct'),
                    Infolists\Components\TextEntry::make('utm_campaign')->label('Campaign')->placeholder('—'),
                    Infolists\Components\TextEntry::make('source_page')->label('Form page')->placeholder('—'),
                    Infolists\Components\TextEntry::make('landing_page')->placeholder('—'),
                    Infolists\Components\TextEntry::make('device')->placeholder('—'),
                    Infolists\Components\TextEntry::make('pages_viewed'),
                ]),
            ])->columnSpan(['lg' => 1]),
        ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        $admin = fn () => ! auth()->user()?->isSales();

        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->sortable()->weight('bold')
                    ->description(fn (Lead $r) => $r->company),
                Tables\Columns\TextColumn::make('phone')->searchable()->copyable()->icon('heroicon-m-phone'),
                Tables\Columns\TextColumn::make('services')->badge()
                    ->formatStateUsing(fn ($state) => Lead::SERVICE_OPTIONS[$state] ?? $state)->limitList(2)->toggleable(),
                Tables\Columns\TextColumn::make('score')->badge()->sortable()->color(fn ($state) => static::scoreColor((int) $state)),
                Tables\Columns\TextColumn::make('status')->badge()->sortable()
                    ->formatStateUsing(fn ($state) => config('advertally.lead_statuses')[$state] ?? $state)
                    ->color(fn ($state) => static::statusColor($state)),
                Tables\Columns\TextColumn::make('business_size')->label('Size')->badge()->color('gray')->toggleable(),
                Tables\Columns\TextColumn::make('form_type')->label('Form')->formatStateUsing(fn ($state) => Lead::FORM_TYPES[$state] ?? $state)->toggleable(),
                Tables\Columns\TextColumn::make('utm_source')->label('Source')->default('direct')->toggleable(),
                Tables\Columns\TextColumn::make('assignee.name')->label('Owner')->placeholder('Unassigned')->toggleable()->visible($admin),
                Tables\Columns\TextColumn::make('next_follow_up_at')->label('Follow-up')->dateTime('d M, h:i A')->sortable()->toggleable()
                    ->color(fn (Lead $r) => $r->next_follow_up_at?->isPast() ? 'danger' : null),
                Tables\Columns\TextColumn::make('created_at')->label('Received')->since()->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')->options(config('advertally.lead_statuses'))->multiple(),
                Tables\Filters\SelectFilter::make('form_type')->label('Form')->options(Lead::FORM_TYPES),
                Tables\Filters\SelectFilter::make('business_size')->label('Business size')->options(config('advertally.business_sizes')),
                Tables\Filters\SelectFilter::make('assigned_to')->label('Owner')->relationship('assignee', 'name')->visible($admin),
                Tables\Filters\Filter::make('hot')->label('Hot leads (60+)')->query(fn (Builder $query) => $query->where('score', '>=', 60))->toggle(),
                Tables\Filters\Filter::make('follow_up_due')->label('Follow-up due')->query(fn (Builder $query) => $query->where('next_follow_up_at', '<=', now()))->toggle(),
                Tables\Filters\Filter::make('created_at')->form([
                    Forms\Components\DatePicker::make('from'),
                    Forms\Components\DatePicker::make('until'),
                ])->query(fn (Builder $query, array $data) => $query
                    ->when($data['from'] ?? null, fn ($q, $d) => $q->whereDate('created_at', '>=', $d))
                    ->when($data['until'] ?? null, fn ($q, $d) => $q->whereDate('created_at', '<=', $d))),
            ])
            ->actions([
                Tables\Actions\Action::make('whatsapp')->label('')->tooltip('WhatsApp')->icon('heroicon-o-chat-bubble-left-right')->color('success')
                    ->url(fn (Lead $r) => $r->whatsapp_url, true),
                Tables\Actions\Action::make('call')->label('')->tooltip('Call')->icon('heroicon-o-phone')->color('gray')
                    ->url(fn (Lead $r) => 'tel:'.$r->phone),
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    static::logActivityAction(),
                    Tables\Actions\Action::make('status')->label('Change status')->icon('heroicon-o-arrows-right-left')
                        ->form([Forms\Components\Select::make('status')->options(config('advertally.lead_statuses'))->required()])
                        ->fillForm(fn (Lead $r) => ['status' => $r->status])
                        ->action(fn (Lead $r, array $data) => $r->update(['status' => $data['status']])),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('assign')->label('Assign to…')->icon('heroicon-o-user-plus')->visible($admin)
                        ->form([Forms\Components\Select::make('assigned_to')->label('Sales user')
                            ->options(fn () => User::where('is_active', true)->whereIn('role', ['admin', 'sales'])->pluck('name', 'id'))->required()])
                        ->action(fn (Collection $records, array $data) => $records->each->update(['assigned_to' => $data['assigned_to']]))
                        ->deselectRecordsAfterCompletion(),
                    Tables\Actions\BulkAction::make('bulk_status')->label('Change status')->icon('heroicon-o-arrows-right-left')
                        ->form([Forms\Components\Select::make('status')->options(config('advertally.lead_statuses'))->required()])
                        ->action(fn (Collection $records, array $data) => $records->each->update(['status' => $data['status']]))
                        ->deselectRecordsAfterCompletion(),
                    Tables\Actions\BulkAction::make('export')->label('Export CSV')->icon('heroicon-o-arrow-down-tray')
                        ->action(fn (Collection $records) => static::exportCsv($records)),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('No leads yet')
            ->emptyStateDescription('Leads from every website form, the audit tool and the pricing calculator appear here automatically.');
    }

    public static function logActivityAction(): Tables\Actions\Action
    {
        return Tables\Actions\Action::make('log')->label('Log activity')->icon('heroicon-o-pencil-square')
            ->form([
                Forms\Components\Select::make('type')->options(collect(\App\Models\LeadNote::TYPES)->except('status'))->default('call')->required(),
                Forms\Components\Textarea::make('body')->label('Notes')->required()->rows(3),
                Forms\Components\DateTimePicker::make('next_follow_up_at')->label('Next follow-up')->seconds(false)->native(false),
            ])
            ->action(function (Lead $record, array $data) {
                $record->notes()->create(['user_id' => auth()->id(), 'type' => $data['type'], 'body' => $data['body']]);
                $updates = ['next_follow_up_at' => $data['next_follow_up_at'] ?? null];
                if ($record->status === 'new') {
                    $updates['status'] = 'contacted';
                }
                $record->update($updates);
            });
    }

    public static function exportCsv(Collection $records)
    {
        $filename = 'leads-'.Carbon::now()->format('Y-m-d-His').'.csv';
        $cols = ['id', 'created_at', 'name', 'company', 'phone', 'email', 'city', 'website', 'business_size', 'industry', 'budget',
            'services', 'status', 'score', 'form_type', 'utm_source', 'utm_medium', 'utm_campaign', 'source_page', 'message'];

        return response()->streamDownload(function () use ($records, $cols) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF"); // UTF-8 BOM so Excel shows ₹ and Hindi names correctly
            fputcsv($out, $cols);
            foreach ($records as $r) {
                fputcsv($out, array_map(function ($c) use ($r) {
                    $v = $r->{$c};

                    return is_array($v) ? implode(', ', $v) : (string) $v;
                }, $cols));
            }
            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    public static function getRelations(): array
    {
        return [NotesRelationManager::class];
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
