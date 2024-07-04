@props([
    'label' => '',
    'name' => '',
    'class' => '',
    'value' => '',
    'required' => false,
    'readonly' => false,
    'placeholder' => '',
    'disabled' => false,
    'isWire' => false,
])

<div class="form-group">
    @isset($label)
        <label for="{{ strtolower($name) }}">{{ ucwords($label) }}</label>
    @endisset
    <textarea id="{{ strtolower($label) }}" rows="5" class="form-control {{ $class }}"
        @if ($isWire) wire:model="{{ $name }}" @else name="{{ $name }}" @endif
        placeholder="{{ $placeholder }}" {{ $required ? 'required' : '' }} {{ $readonly ? 'readonly' : '' }}
        {{ $disabled ? 'disabled' : '' }}>{{ $value }}</textarea>
</div>
