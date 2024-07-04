@props([
    'label' => '',
    'name' => 'source',
    'required' => false,
    'multiple' => false,
    'selected' => null,
    'isWire' => false,
])

<div class="form-group">
    <label for="{{ strtolower($label) }}">{{ ucwords($label) }}</label>
    <select @if ($isWire) wire:model="{{ $name }}" @else name="{{ $name }}" @endif
        id="{{ strtolower($label) }}" class="form-control @error($name) is-invalid @enderror"
        {{ $required ? 'required' : '' }} {{ $multiple ? 'multiple' : '' }}>
        @foreach (\App\Enums\LeadSource::getInstances() as $source)
            <option value="{{ $source }}" {{ $selected == $source ? 'selected' : '' }}>
                {{ $source }}
            </option>
        @endforeach
    </select>
</div>
