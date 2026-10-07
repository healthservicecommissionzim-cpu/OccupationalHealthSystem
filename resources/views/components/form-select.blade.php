@props([
    'label' => null,
    'name' => '',
    'options' => [],
    'value' => null,
    'error' => null,
    'icon' => null,
    'required' => false,
    'disabled' => false,
    'helper' => null,
    'id' => null,
    'placeholder' => 'Select an option',
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
        <select
            id="{{ $id }}"
            name="{{ $name }}"
            @if ($required) required @endif
            @if ($disabled) disabled @endif
            @if ($hasError) aria-invalid="true" aria-describedby="{{ $id }}-error" @endif
            class="@if ($hasError) has-error @endif"
            {{ $attributes->merge(['class' => '']) }}
        >
            @if ($placeholder)
                <option value="">{{ $placeholder }}</option>
            @endif
            
            @foreach ($options as $optionValue => $optionLabel)
                <option 
                    value="{{ $optionValue }}"
                    @if (old($name, $value) == $optionValue) selected @endif
                >
                    {{ $optionLabel }}
                </option>
            @endforeach
        </select>
        
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
