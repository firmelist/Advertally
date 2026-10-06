@props(['name', 'label', 'type' => 'text', 'required' => false, 'options' => null, 'placeholder' => null, 'rows' => 4, 'hint' => null, 'value' => null])
@php $id = 'f-'.$name; $error = $errors->first($name); @endphp
<div {{ $attributes->only('class') }}>
    <label for="{{ $id }}" class="field-label">{{ $label }} @if ($required)<span class="text-red-700" aria-hidden="true">*</span>@endif</label>
    @if ($options !== null)
        <select id="{{ $id }}" name="{{ $name }}" class="field" @required($required) @if ($error) aria-invalid="true" aria-describedby="{{ $id }}-error" @endif {{ $attributes->except('class') }}>
            <option value="">{{ $placeholder ?? 'Select…' }}</option>
            @foreach ($options as $key => $optionLabel)
                <option value="{{ $key }}" @selected(old($name, $value) === (string) $key)>{{ $optionLabel }}</option>
            @endforeach
        </select>
    @elseif ($type === 'textarea')
        <textarea id="{{ $id }}" name="{{ $name }}" rows="{{ $rows }}" class="field" placeholder="{{ $placeholder }}" @required($required) @if ($error) aria-invalid="true" aria-describedby="{{ $id }}-error" @endif {{ $attributes->except('class') }}>{{ old($name, $value) }}</textarea>
    @else
        <input id="{{ $id }}" name="{{ $name }}" type="{{ $type }}" value="{{ old($name, $value) }}" class="field" placeholder="{{ $placeholder }}" @required($required) @if ($error) aria-invalid="true" aria-describedby="{{ $id }}-error" @endif {{ $attributes->except('class') }}>
    @endif
    @if ($hint && ! $error)
        <p class="mt-1.5 text-xs text-muted">{{ $hint }}</p>
    @endif
    @if ($error)
        <p id="{{ $id }}-error" class="field-error">{{ $error }}</p>
    @endif
</div>
