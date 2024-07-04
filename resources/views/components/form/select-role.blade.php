@props([
    'label' => '',
    'name' => 'role_id',
    'id' => 'select_role',
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
        name="{{ $name }}" data-search="on" id="{{ $id }}" style="width: 100%">
        @if ($selected !== null)
            @foreach ($selected as $role)
                <option value="{{ $role->id ?? '' }}">{{ $role->name ?? '' }}</option>
            @endforeach
        @endif
    </select>
</div>

@push('scripts')
    <script>
        $(document).ready(function() {

            $('#{{ $id }}').select2({
                placeholder: 'Select and begin typing',
                ajax: {
                    url: '{{ route('roles.list') }}',
                    delay: 250,
                    cache: true,
                    data: function(params) {
                        return {
                            search: params.term,
                        }
                    },
                    processResults: function(result) {
                        return {
                            results: result.map(function(role) {
                                return {
                                    id: role.id,
                                    text: role.name,
                                }
                            })
                        }
                    },
                }
            });
        });
    </script>
@endpush
