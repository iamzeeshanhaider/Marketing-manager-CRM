@props([
    'label' => '',
    'name' => '',
    'value' => '',
    'required' => false,
    'readonly' => false,
    'checked' => false,
    'disabled' => false,
    'type' => 'text', // text, tel, password, email
    'meta' => null,
    'class' => 'form-control',
    'isWire' => false,
])

<div class="form-group">
    @isset($label)
        <label for="{{ strtolower($name) }}">{{ ucwords($label) }}</label>
    @endisset
    @isset($meta)
        <small class="text-muted"><i>{{ $meta ?? '' }}</i></small>
    @endisset
    <div>
    <input class="{{ $class }} @error($name) is-invalid @enderror" type="{{ $type }}"
        id="{{ strtolower($name) }}" value="{{ $value }}"
        @if ($isWire) wire:model="{{ $name }}" @else name="{{ $name }}" @endif
        @if ($type === 'checkbox') checked="{{ $checked }}" @endif {{ $required ? 'required' : '' }}
        {{ $readonly ? 'readonly' : '' }} {{ $disabled ? 'disabled' : '' }}>
    </div>
</div>
