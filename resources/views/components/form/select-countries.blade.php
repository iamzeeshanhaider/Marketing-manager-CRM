@props([
    'label' => '',
    'name' => 'country',
    'selected' => null,
    'required' => false,
    'readonly' => false,
    'disabled' => false,
    'multiple' => false,
    'meta' => null,
])

<div class="form-group">
    <label for="{{ strtolower($label) }}">{{ ucwords($label) }}</label>
    <small class="text-muted"><i>{{ $meta ?? '' }}</i></small>
    <select class="form-control @error($name) is-invalid @enderror" {{ $readonly ? 'readonly' : '' }}
        {{ $disabled ? 'disabled' : '' }} {{ $multiple ? 'multiple' : '' }} {{ $required ? 'required' : '' }}
        name="{{ $name }}" data-search="on" id="{{ $name }}" style="width: 100%"
        placeholder="Select Country">
        @if ($selected !== null)
            <option value="{{ $selected }}">{{ $selected }}</option>
        @endif
    </select>
</div>

@push('scripts')
    <script>
        $(document).ready(function() {

            $('#{{ $name }}').select2({
                placeholder: 'Select Country',
                ajax: {
                    url: '{{ route('country.list') }}',
                    delay: 250,
                    cache: true,
                    data: function(params) {
                        return {
                            search: params.term,
                        }
                    },
                    processResults: function(result) {
                        let manualRow = {
                            id: 999,
                            name: 'All'
                        };
                        result.unshift(manualRow);

                        return {
                            results: result.map(function(country) {
                                return {
                                    id: country.id,
                                    text: country.name,
                                }
                            })
                        }
                    },
                }
            });
        });
    </script>
@endpush
