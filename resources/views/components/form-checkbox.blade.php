@props([
    'label' => null,
    'name' => '',
    'value' => '1',
    'checked' => false,
    'error' => null,
    'required' => false,
    'disabled' => false,
    'helper' => null,
    'id' => null,
])

@php
    $id = $id ?? $name;
    $hasError = $error || $errors->has($name);
    $isChecked = $checked || old($name) == $value;
@endphp

<div class="checkbox-group @if ($hasError) error @endif" id="{{ $id }}-wrapper">
    <input
        type="checkbox"
        id="{{ $id }}"
        name="{{ $name }}"
        value="{{ $value }}"
        @if ($isChecked) checked @endif
        @if ($required) required @endif
        @if ($disabled) disabled @endif
        @if ($hasError) aria-invalid="true" aria-describedby="{{ $id }}-error" @endif
        {{ $attributes->merge(['class' => '']) }}
    />
    
    @if ($label)
        <label for="{{ $id }}">
            {{ $label }}
            @if ($required)
                <span class="required" aria-label="required">*</span>
            @endif
            @if ($helper)
                <span class="helper">{{ $helper }}</span>
            @endif
        </label>
    @endif

    @if ($hasError)
        <div class="error-text" id="{{ $id }}-error" role="alert">
            <i class="fa-solid fa-circle-exclamation"></i>
            @php
                $errorMessage = $error ?? $errors->first($name);
            @endphp
            {{ $errorMessage }}
        </div>
    @endif
</div>
