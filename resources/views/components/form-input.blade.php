@props([
    'label' => null,
    'name' => '',
    'type' => 'text',
    'placeholder' => '',
    'value' => null,
    'error' => null,
    'icon' => null,
    'required' => false,
    'disabled' => false,
    'helper' => null,
    'id' => null,
])

@php
    $id = $id ?? $name;
    $hasError = $error || $errors->has($name);
@endphp

<div class="form-group">
    @if ($label)
        <label for="{{ $id }}" class="@if ($hasError) error @endif">
            {{ $label }}
            @if ($required)
                <span class="required" aria-label="required">*</span>
            @endif
            @if ($helper)
                <span class="helper">{{ $helper }}</span>
            @endif
        </label>
    @endif

    <div class="input-wrapper @if ($hasError) error @endif" id="{{ $id }}-wrapper">
        <input
            type="{{ $type }}"
            id="{{ $id }}"
            name="{{ $name }}"
            value="{{ old($name, $value) }}"
            placeholder="{{ $placeholder }}"
            @if ($required) required @endif
            @if ($disabled) disabled @endif
            @if ($hasError) aria-invalid="true" aria-describedby="{{ $id }}-error" @endif
            class="@if ($hasError) has-error @endif"
            {{ $attributes->merge(['class' => '']) }}
        />
        
        @if ($icon)
            <span class="input-icon">
                <i class="fa-solid fa-{{ $icon }}"></i>
            </span>
        @endif
    </div>

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
