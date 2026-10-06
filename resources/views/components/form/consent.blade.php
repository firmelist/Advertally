@props(['text' => 'I agree to Advertally contacting me about this request. We never share your details.'])
<div>
    <label class="flex items-start gap-3 text-sm text-muted">
        <input type="checkbox" name="consent" value="1" class="mt-0.5 size-4 rounded border-line text-brand-600 focus:ring-brand-500" @checked(old('consent')) required>
        <span>{{ $text }} See our <a href="{{ url('privacy-policy') }}" class="font-semibold text-brand-700 hover:underline">privacy policy</a>.</span>
    </label>
    @error('consent')<p class="field-error">{{ $message }}</p>@enderror
</div>
