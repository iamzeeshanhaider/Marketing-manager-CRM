@props([
    'label' => 'name',
    'name' => 'name',
    'required' => false,
    'multiple' => false,
    'selected' => null,
    'items' => []
])

<div class="form-group">
    <label for="{{ strtolower($label) }}">{{ ucwords($label) }}</label>
    <select name="{{ $name }}" id="{{ strtolower($label) }}" class="form-control @error($name) is-invalid @enderror"
        {{ $required ? 'required' : '' }} {{ $multiple ? 'multiple' : '' }}>
        @foreach ($items as $item)
            <option value="{{ $item->id }}" {{ $selected == $item->name ? 'selected' : '' }}>
                {{ $item->name }}
            </option>
        @endforeach
    </select>
</div>
