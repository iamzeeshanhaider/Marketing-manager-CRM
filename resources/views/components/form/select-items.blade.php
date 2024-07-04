@props([
    'label' => '',
    'name' => 'permissions',
    'id' => 'permissions',
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
    <select class="form-control"
        {{ $disabled ? 'disabled' : '' }} multiple {{ $required ? 'required' : '' }}
        name="{{ $name }}" data-search="on" id="{{ $id }}" style="width: 100%"
        placeholder="Select Permission">

    </select>
</div>

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#{{ $id }}').select2({
                    placeholder: 'Select Items',
                    ajax: {
                        url: '{{ route("items.select") }}',
                        delay: 250,
                        cache: true,
                        data: function(params) {
                            return {
                                search: params.term,
                            }
                        },
                        processResults: function(result) {
                            return {
                                results: result.map(function(perm)  {
                                    return {
                                        id: perm.id,
                                        text: perm.name,
                                    }
                                }),

                            }

                        },

                    }

                });

        });




    </script>
@endpush
