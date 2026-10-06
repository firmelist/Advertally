<x-filament-panels::page>
    <x-filament::section heading="Integrations" description="IDs are read from .env — they are never hard-coded. Tags load on the public site only in production and after cookie consent.">
        <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ($integrations as $name => $value)
                <div class="flex items-center justify-between rounded-lg border border-gray-200 px-4 py-3 dark:border-white/10">
                    <span class="text-sm font-medium">{{ $name }}</span>
                    @if ($value)
                        <x-filament::badge color="success">{{ \Illuminate\Support\Str::limit($value, 14) }}</x-filament::badge>
                    @else
                        <x-filament::badge color="gray">Not set</x-filament::badge>
                    @endif
                </div>
            @endforeach
        </div>
        <p class="mt-4 text-xs text-gray-500">Website traffic is reported in GA4 / Search Console. This page reports on leads and their attribution.</p>
    </x-filament::section>

    <div class="grid gap-6 lg:grid-cols-3">
        @foreach (['UTM campaigns' => $campaigns, 'First-touch sources' => $firstTouch, 'Landing pages' => $landingPages] as $title => $rows)
            <x-filament::section :heading="$title.' (90 days)'">
                @if ($rows->isEmpty())
                    <p class="text-sm text-gray-500">No data yet.</p>
                @else
                    <table class="w-full text-sm">
                        <thead><tr class="text-left text-xs text-gray-500"><th class="pb-2">Label</th><th class="pb-2 text-right">Leads</th><th class="pb-2 text-right">Qualified</th></tr></thead>
                        <tbody>
                            @foreach ($rows as $row)
                                <tr class="border-t border-gray-100 dark:border-white/5">
                                    <td class="max-w-[14rem] truncate py-2" title="{{ $row->label }}">{{ \Illuminate\Support\Str::limit(preg_replace('#^https?://[^/]+#', '', (string) $row->label) ?: '/', 40) }}</td>
                                    <td class="py-2 text-right">{{ $row->leads }}</td>
                                    <td class="py-2 text-right">{{ $row->qualified }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </x-filament::section>
        @endforeach
    </div>
</x-filament-panels::page>
