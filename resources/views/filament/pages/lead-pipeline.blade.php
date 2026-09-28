<x-filament-panels::page>
    {{-- Scoped styles: Filament's pre-built CSS doesn't include arbitrary Tailwind utilities used in custom pages. --}}
    <style>
        .adv-board { display: flex; gap: 1rem; overflow-x: auto; padding-bottom: 1rem; align-items: flex-start; }
        .adv-col { flex: 0 0 18rem; width: 18rem; min-width: 18rem; max-width: 18rem; display: flex; flex-direction: column; border-radius: .75rem; padding: .75rem; background: rgb(243 244 246); transition: box-shadow .15s; }
        .dark .adv-col { background: rgb(255 255 255 / .05); }
        .adv-col.is-over { box-shadow: 0 0 0 2px #2952CC; }
        .adv-col-head { display: flex; align-items: center; justify-content: space-between; margin-bottom: .75rem; padding: 0 .25rem; }
        .adv-list { display: flex; flex-direction: column; gap: .5rem; min-height: 6rem; }
        .adv-card { display: block; cursor: grab; border-radius: .5rem; border: 1px solid rgb(229 231 235); background: #fff; padding: .75rem; box-shadow: 0 1px 2px rgb(0 0 0 / .05); }
        .adv-card:hover { border-color: #5F84F5; }
        .dark .adv-card { background: rgb(17 24 39); border-color: rgb(255 255 255 / .1); }
        .adv-row { display: flex; align-items: flex-start; justify-content: space-between; gap: .5rem; }
        .adv-name { font-size: .875rem; font-weight: 600; }
        .adv-meta { font-size: .75rem; color: rgb(107 114 128); }
        .adv-foot { display: flex; justify-content: space-between; margin-top: .5rem; font-size: 11px; color: rgb(156 163 175); }
        .adv-due { color: rgb(220 38 38); font-weight: 600; }
        .adv-empty { border: 1px dashed rgb(209 213 219); border-radius: .5rem; padding: 1rem; text-align: center; font-size: .75rem; color: rgb(156 163 175); }
        .adv-ellipsis { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; margin-top: .5rem; }
    </style>

    <div style="display:flex; align-items:center; justify-content:space-between; gap:1rem;">
        <p class="text-sm text-gray-500 dark:text-gray-400">Drag a card to another column to update its status. Won/Lost show the last 30 days.</p>
        @unless (auth()->user()->isSales())
            <label style="display:flex; align-items:center; gap:.5rem;" class="text-sm">
                <x-filament::input.checkbox wire:model.live="onlyMine" /> Only my leads
            </label>
        @endunless
    </div>

    <div class="adv-board" x-data="{ dragging: null, over: null }">
        @foreach ($this->getColumns() as $col)
            <div class="adv-col" :class="over === '{{ $col['key'] }}' && 'is-over'"
                 x-on:dragover.prevent="over = '{{ $col['key'] }}'"
                 x-on:dragleave="over = null"
                 x-on:drop.prevent="if (dragging) { $wire.moveLead(dragging, '{{ $col['key'] }}'); } dragging = null; over = null">
                <div class="adv-col-head">
                    <x-filament::badge :color="$col['color']">{{ $col['label'] }}</x-filament::badge>
                    <span class="adv-meta">{{ $col['leads']->count() }}@if ($col['value']) · ₹{{ number_format($col['value']) }}@endif</span>
                </div>
                <div class="adv-list">
                    @forelse ($col['leads'] as $lead)
                        <a href="{{ $this->leadUrl($lead) }}" draggable="true" wire:key="lead-{{ $lead->id }}"
                           x-on:dragstart="dragging = {{ $lead->id }}" class="adv-card">
                            <div class="adv-row">
                                <span class="adv-name text-gray-950 dark:text-white">{{ $lead->name }}</span>
                                <span style="flex-shrink:0"><x-filament::badge size="sm" :color="\App\Filament\Resources\LeadResource::scoreColor($lead->score)">{{ $lead->score }}</x-filament::badge></span>
                            </div>
                            @if ($lead->company)<div class="adv-meta">{{ $lead->company }}</div>@endif
                            <div class="adv-meta adv-ellipsis">{{ $lead->services_label ?: (\App\Models\Lead::FORM_TYPES[$lead->form_type] ?? '') }}</div>
                            <div class="adv-foot">
                                <span>{{ $lead->assignee?->name ?? 'Unassigned' }}</span>
                                <span @class(['adv-due' => $lead->next_follow_up_at?->isPast()])>
                                    {{ $lead->next_follow_up_at ? 'Follow-up '.$lead->next_follow_up_at->diffForHumans() : $lead->created_at->diffForHumans() }}
                                </span>
                            </div>
                        </a>
                    @empty
                        <div class="adv-empty">Drop leads here</div>
                    @endforelse
                </div>
            </div>
        @endforeach
    </div>
</x-filament-panels::page>
