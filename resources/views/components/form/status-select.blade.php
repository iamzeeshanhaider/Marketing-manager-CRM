@props([
    'label' => 'name',
    'name' => 'name',
    'required' => false,
    'multiple' => false,
    'selected' => null,
])

<div class="form-group">
    <label for="{{ strtolower($label) }}">{{ ucwords($label) }}</label>
    <select name="status" id="{{ strtolower($label) }}" class="form-control @error($name) is-invalid @enderror"
        {{ $required ? 'required' : '' }} {{ $multiple ? 'multiple' : '' }}>
        @foreach (\App\Enums\GeneralStatus::getInstances() as $status)
            <option value="{{ $status }}" {{ $selected == $status ? 'selected' : '' }}>
                {{ $status }}
            </option>
        @endforeach
    </select>
</div>
