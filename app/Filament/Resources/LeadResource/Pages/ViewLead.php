<?php

namespace App\Filament\Resources\LeadResource\Pages;

use App\Filament\Resources\ClientResource;
use App\Filament\Resources\LeadResource;
use App\Models\Client;
use App\Models\Lead;
use Filament\Actions;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewLead extends ViewRecord
{
    protected static string $resource = LeadResource::class;

    public function getSubheading(): ?string
    {
        /** @var Lead $lead */
        $lead = $this->getRecord();

        return ($lead->company ? $lead->company.' · ' : '').'Received '.$lead->created_at->diffForHumans();
    }

    protected function getHeaderActions(): array
    {
        /** @var Lead $lead */
        $lead = $this->getRecord();
        $pillars = collect($lead->services ?? [])->map(fn ($s) => Lead::SERVICE_PILLAR[$s] ?? null)->filter()->unique()->values()->all();

        return [
            Actions\Action::make('whatsapp')->label('WhatsApp')->icon('heroicon-o-chat-bubble-left-right')->color('success')
                ->url($lead->whatsapp_url, true),
            Actions\Action::make('call')->label('Call')->icon('heroicon-o-phone')->color('gray')->url('tel:'.$lead->phone),
            Actions\Action::make('log')->label('Log activity')->icon('heroicon-o-pencil-square')->color('gray')
                ->form([
                    Forms\Components\Select::make('type')->options(collect(\App\Models\LeadNote::TYPES)->except('status'))->default('call')->required(),
                    Forms\Components\Textarea::make('body')->label('Notes')->required()->rows(3),
                    Forms\Components\DateTimePicker::make('next_follow_up_at')->label('Next follow-up')->seconds(false)->native(false),
                ])
                ->action(function (array $data) use ($lead) {
                    $lead->notes()->create(['user_id' => auth()->id(), 'type' => $data['type'], 'body' => $data['body']]);
                    $lead->update(array_filter([
                        'next_follow_up_at' => $data['next_follow_up_at'] ?? null,
                        'status' => $lead->status === 'new' ? 'contacted' : null,
                    ]));
                    $this->refreshFormData(['status', 'next_follow_up_at']);
                    Notification::make()->title('Activity logged')->success()->send();
                }),
            Actions\Action::make('convert')->label('Convert to client')->icon('heroicon-o-trophy')->color('primary')
                ->visible(fn () => ! $lead->client()->exists())
                ->form([
                    Forms\Components\CheckboxList::make('active_pillars')->label('Services they bought')
                        ->options(collect(config('advertally.ladder'))->mapWithKeys(fn ($l, $k) => [$k => $l['title']]))
                        ->default($pillars ?: ['grow'])->required(),
                    Forms\Components\TextInput::make('monthly_value')->label('Monthly value (₹)')->numeric()->prefix('₹')->default($lead->deal_value),
                ])
                ->action(function (array $data) use ($lead) {
                    $client = Client::create([
                        'lead_id' => $lead->id,
                        'account_manager_id' => $lead->assigned_to ?? auth()->id(),
                        'company' => $lead->company ?: $lead->name,
                        'contact_name' => $lead->name,
                        'phone' => $lead->phone,
                        'email' => $lead->email,
                        'city' => $lead->city,
                        'industry' => $lead->industry,
                        'business_size' => $lead->business_size,
                        'active_pillars' => $data['active_pillars'],
                        'monthly_value' => (int) ($data['monthly_value'] ?? 0),
                        'since' => now(),
                    ]);
                    $lead->update(['status' => 'won']);
                    Notification::make()->title('Client created 🎉')->success()->send();
                    $this->redirect(ClientResource::getUrl('edit', ['record' => $client]));
                }),
            Actions\EditAction::make(),
        ];
    }
}
