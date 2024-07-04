@props([
    'label' => 'name',
    'name' => 'name',
    'required' => false,
    'multiple' => false,
    'selected' => null,
    'options' => [
        '' => 'Select',
    ]
])

<div class="form-group">
    <label for="{{ strtolower($label) }}">{{ ucwords($label) }}</label>
    <select name="{{$name}}" id="{{ strtolower($label) }}" class="form-control @error($name) is-invalid @enderror"
        {{ $required ? 'required' : '' }} {{ $multiple ? 'multiple' : '' }}>
        @foreach ($options as $item => $value)
            <option value="{{ $item }}" {{ $selected == $item ? 'selected' : '' }}>
                {{ $value }}
            </option>
        @endforeach
    </select>
</div>
