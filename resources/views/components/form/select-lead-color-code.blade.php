@props([
    'label' => 'Color Code',
    'name' => 'color_code',
    'id' => 'lead_status_color_code',
    'selected' => null,
    'required' => false,
    'readonly' => false,
    'disabled' => false,
    'multiple' => false,
    'meta' => null,
])

<div class="form-group">
    <label for="{{ strtolower($label) }}">{{ ucwords($label) }}</label>
    <select name="{{ $name }}" id="{{ $name }}" class="form-control @error($name) is-invalid @enderror"
        {{ $required ? 'required' : '' }} {{ $multiple ? 'multiple' : '' }} style="background-color: {{ $selected }};">
        <option value="" selected disabled>-- Select Status Color --</option>
        @foreach (\App\Models\LeadStatus::defaultColorCodes() as $color)
            <option value="{{ $color }}" {{ $selected == $color ? 'selected' : '' }}>
                {{ $color }}
            </option>
        @endforeach
    </select>
</div>

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#{{ $name }}').on('change', function() {
                var selectedColor = $(this).val();
                console.log(selectedColor);
                $(this).prop('style', 'background-color: ' + selectedColor + ' !important');
            });
        });
    </script>
@endpush
