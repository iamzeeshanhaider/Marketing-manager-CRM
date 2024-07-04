@props(
    [
        'label' => '',
        'name' => 'tel_status',
        'id' => 'select_tel_status',
        'selected' => null,
        'required' => false,
        'readonly' => false,
        'disabled' => false,
        'multiple' => false,
        'meta' => null,
        'isWire' => false,
    ]
)

<div class="form-group">
    <label for="{{ strtolower($label) }}">{{ ucwords($label) }}</label>
    <select name="{{ $name }}" id="{{ strtolower($label) }}" class="form-control @error($name) is-invalid @enderror"
        {{ $required ? 'required' : '' }} {{ $multiple ? 'multiple' : '' }}>
        @foreach (\App\Enums\CallStatus::getInstances() as $status)
            <option value="{{ $status }}" {{ $selected == $status ? 'selected' : '' }}>
                {{ $status }}
            </option>
        @endforeach
    </select>
</div>
