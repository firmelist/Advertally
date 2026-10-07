<div class="card overflow-hidden" x-data x-init="$wire.$watch('step', () => $el.scrollIntoView({ behavior: 'smooth', block: 'start' }))">
    {{-- progress --}}
    <div class="border-b border-line px-6 py-4 sm:px-8">
        <div class="flex items-center justify-between text-xs font-semibold">
            <span class="text-ink">
                @if ($step < $total) Step {{ $step + 1 }} of {{ $total + 1 }} · {{ $steps[$step]['name'] }} @else Final step · Your report @endif
            </span>
            <span class="text-muted tabular-nums">{{ (int) round($step / ($total + 1) * 100) }}%</span>
        </div>
        <div class="mt-3 flex gap-1" aria-hidden="true">
            @for ($i = 0; $i <= $total; $i++)
                <span class="h-1.5 flex-1 rounded-full transition-colors {{ $i < $step ? 'bg-brand-600' : ($i === $step ? 'bg-brand-300' : 'bg-navy-50') }}"></span>
            @endfor
        </div>
    </div>

    <form wire:submit="{{ $step < $total ? 'next' : 'submit' }}" class="p-6 sm:p-8">
        @if ($step < $total)
            @php $current = $steps[$step]; @endphp
            <div wire:key="step-{{ $step }}">
                <p class="font-mono text-xs font-bold text-brand-600">{{ str_pad($step + 1, 2, '0', STR_PAD_LEFT) }} — {{ strtoupper($current['name']) }}</p>
                @if ($current['description'])<p class="mt-1 text-sm text-muted">{{ $current['description'] }}</p>@endif

                <div class="mt-6 space-y-8">
                    @foreach ($current['questions'] as $question)
                        <fieldset wire:key="q-{{ $question['id'] }}">
                            <legend class="text-base font-semibold text-ink">{{ $question['question'] }}</legend>
                            @if ($question['help'])<p class="mt-1 text-sm text-muted">{{ $question['help'] }}</p>@endif
                            <div class="mt-3 grid gap-2">
                                @foreach ($question['options'] as $index => $label)
                                    <label class="group flex cursor-pointer items-start gap-3 rounded-xl border px-4 py-3 text-sm transition has-[:checked]:border-brand-600 has-[:checked]:bg-brand-50 has-[:focus-visible]:ring-2 has-[:focus-visible]:ring-brand-500 border-line hover:border-brand-200">
                                        <input type="radio" class="mt-0.5 size-4 border-line text-brand-600 focus:ring-brand-500" wire:model="answers.{{ $question['id'] }}" value="{{ $index }}" name="q{{ $question['id'] }}">
                                        <span class="text-ink">{{ $label }}</span>
                                    </label>
                                @endforeach
                            </div>
                            @error('answers.'.$question['id'])<p class="field-error">{{ $message }}</p>@enderror
                        </fieldset>
                    @endforeach
                </div>
            </div>
        @else
            <div wire:key="step-contact">
                <p class="font-mono text-xs font-bold text-growth-700">ALMOST DONE</p>
                <h2 class="mt-2 text-2xl font-bold text-ink">Where should we send your Growth Score?</h2>
                <p class="mt-2 text-sm text-muted">Your report opens instantly. A strategist may follow up with one or two observations — never a hard sell.</p>

                <div class="relative mt-6 grid gap-5 sm:grid-cols-2">
                    <div class="absolute -left-[10000px]" aria-hidden="true"><input type="text" wire:model="hp_trap" tabindex="-1" autocomplete="off" data-lpignore="true" data-1p-ignore aria-label="Leave this field empty"></div>
                    @foreach ([['name', 'Your name', 'text', 'name'], ['email', 'Business email', 'email', 'email'], ['company', 'Company', 'text', 'organization'], ['website', 'Website', 'text', 'url']] as [$field, $label, $type, $auto])
                        <div>
                            <label for="gs-{{ $field }}" class="field-label">{{ $label }} @if ($field !== 'website')<span class="text-red-700" aria-hidden="true">*</span>@endif</label>
                            <input id="gs-{{ $field }}" type="{{ $type }}" wire:model="{{ $field }}" autocomplete="{{ $auto }}" class="field" @if ($field !== 'website') required @endif @error($field) aria-invalid="true" @enderror>
                            @error($field)<p class="field-error">{{ $message }}</p>@enderror
                        </div>
                    @endforeach
                    <div class="sm:col-span-2">
                        <label for="gs-industry" class="field-label">Industry <span class="text-red-700" aria-hidden="true">*</span></label>
                        <select id="gs-industry" wire:model="industry" class="field" required>
                            <option value="">Select…</option>
                            @foreach ($industries as $key => $label)<option value="{{ $key }}">{{ $label }}</option>@endforeach
                        </select>
                        @error('industry')<p class="field-error">{{ $message }}</p>@enderror
                    </div>
                </div>
                <label class="mt-5 flex items-start gap-3 text-sm text-muted">
                    <input type="checkbox" wire:model="consent" class="mt-0.5 size-4 rounded border-line text-brand-600 focus:ring-brand-500">
                    <span>I agree to Advertally storing my answers to prepare this report and contacting me about it. <a href="{{ url('privacy-policy') }}" class="font-semibold text-brand-700 hover:underline">Privacy policy</a>.</span>
                </label>
                @error('consent')<p class="field-error">{{ $message }}</p>@enderror
            </div>
        @endif

        <div class="mt-8 flex items-center justify-between gap-3 border-t border-line pt-6">
            @if ($step > 0)
                <button type="button" wire:click="back" class="btn-secondary">Back</button>
            @else
                <span></span>
            @endif
            <button type="submit" class="btn-primary" wire:loading.attr="disabled">
                <span wire:loading.remove wire:target="next,submit">{{ $step < $total ? 'Continue' : 'See my Growth Score' }}</span>
                <span wire:loading wire:target="next,submit">{{ $step < $total ? 'Saving…' : 'Calculating your score…' }}</span>
                <x-glyph name="arrow-right" class="size-4" />
            </button>
        </div>
    </form>
</div>
