@props([
    'label' => 'Email Status',
    'name' => 'email_status',
    'required' => false,
    'multiple' => false,
    'selected' => null,
])

<div class="form-group">
    <label for="{{ strtolower($label) }}">{{ ucwords($label) }}</label>
    <select name="{{ $name }}" id="{{ strtolower($label) }}" class="form-control @error($name) is-invalid @enderror"
        {{ $required ? 'required' : '' }} {{ $multiple ? 'multiple' : '' }}>
        @foreach (\App\Enums\EmailStatus::getInstances() as $status)
            <option value="{{ $status }}" {{ $selected == $status ? 'selected' : '' }}>
                {{ $status }}
            </option>
        @endforeach
    </select>
</div>
