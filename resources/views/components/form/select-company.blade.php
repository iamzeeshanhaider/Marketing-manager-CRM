@props([
    'label' => '',
    'name' => 'company_id',
    'id' => 'select_company',
    'selected' => null,
    'required' => false,
    'readonly' => false,
    'disabled' => false,
    'multiple' => false,
    'meta' => null,
    'isWire' => false,
])
<div class="form-group">
    <label for="{{ strtolower($label) }}">{{ ucwords($label) }}</label>
    <small class="text-muted"><i>{{ $meta ?? '' }}</i></small>
    <select class="form-control @error($name) is-invalid @enderror" {{ $readonly ? 'readonly' : '' }}
        {{ $disabled ? 'disabled' : '' }} {{ $multiple ? 'multiple' : '' }} {{ $required ? 'required' : '' }}
        data-search="on" id="{{ $id }}" style="width: 100%"
        @if ($isWire) wire:model="{{ $name }}" @else name="{{ $name }}" @endif>
        @if ($selected !== null)
            @foreach ($selected as $company)
                <option value="{{ $company->id ?? '' }}">{{ $company->name ?? '' }}</option>
            @endforeach
        @endif
    </select>
</div>

@push('scripts')
    <script>
        $('#{{ $id }}').select2({
            placeholder: 'Select and begin typing',
            ajax: {
                url: '{{ route('company.list') }}',
                delay: 250,
                cache: true,
                data: function(params) {
                    return {
                        search: params.term,
                    }
                },
                processResults: function(result) {
                    return {
                        results: result.map(function(company) {
                            return {
                                id: company.id,
                                text: company.name,
                            }
                        })
                    }
                },
            }
        });
    </script>
@endpush
