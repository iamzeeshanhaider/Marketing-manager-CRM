@props([
    'label' => 'Campaign Type',
    'name' => 'source',
    'required' => false,
    'multiple' => false,
    'selected' => null,
    'isWire' => false,
    'types' => [\App\Enums\CampaignTypes::Email, \App\Enums\CampaignTypes::SMS],
])

<div class="form-group">
    <label for="{{ strtolower($label) }}">{{ ucwords($label) }}</label>
    <select @if ($isWire) wire:model="{{ $name }}" @else name="{{ $name }}" @endif
        id="{{ strtolower($label) }}" class="form-control @error($name) is-invalid @enderror"
        {{ $required ? 'required' : '' }} {{ $multiple ? 'multiple' : '' }}>
        @foreach ($types as $type)
            <option value="{{ $type }}" {{ $selected == $type ? 'selected' : '' }}>
                {{ ucfirst($type) }}
            </option>
        @endforeach
    </select>
</div>
