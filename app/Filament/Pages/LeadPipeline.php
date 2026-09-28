<?php

namespace App\Filament\Pages;

use App\Filament\Resources\LeadResource;
use App\Models\Lead;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

/**
 * Kanban-style pipeline board. Drag a card to another column to change its status.
 */
class LeadPipeline extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-view-columns';

    protected static ?string $navigationGroup = 'Sales';

    protected static ?string $navigationLabel = 'Pipeline board';

    protected static ?int $navigationSort = 2;

    protected static ?string $title = 'Lead pipeline';

    protected static string $view = 'filament.pages.lead-pipeline';

    public bool $onlyMine = false;

    public static function canAccess(): bool
    {
        return in_array(auth()->user()?->role, ['admin', 'sales'], true);
    }

    public function getColumns(): array
    {
        $leads = LeadResource::getEloquentQuery()
            ->with('assignee:id,name')
            ->when($this->onlyMine, fn ($q) => $q->where('assigned_to', auth()->id()))
            ->where(fn ($q) => $q->whereNotIn('status', ['won', 'lost'])->orWhere('updated_at', '>=', now()->subDays(30)))
            ->orderByDesc('score')
            ->limit(400)
            ->get()
            ->groupBy('status');

        return collect(config('advertally.lead_statuses'))->map(fn ($label, $key) => [
            'key' => $key,
            'label' => $label,
            'color' => LeadResource::statusColor($key),
            'leads' => $leads->get($key, collect()),
            'value' => $leads->get($key, collect())->sum('deal_value'),
        ])->values()->all();
    }

    public function moveLead(int $id, string $status): void
    {
        abort_unless(array_key_exists($status, config('advertally.lead_statuses')), 422);

        $lead = LeadResource::getEloquentQuery()->findOrFail($id); // respects "sales see only their leads"

        if ($lead->status !== $status) {
            $lead->update(['status' => $status]);
            Notification::make()->title("{$lead->name} → ".config('advertally.lead_statuses')[$status])->success()->send();
        }
    }

    public function leadUrl(Lead $lead): string
    {
        return LeadResource::getUrl('view', ['record' => $lead]);
    }
}
