@props([
    'label' => '',
    'name' => 'permissions',
    'id' => 'permissions',
    'selected' => null,
    'required' => false,
    'user_id' => 3,
    'readonly' => false,
    'disabled' => false,
    'multiple' => false,
    'meta' => null,
])

<div class="form-group">
    <label for="{{ strtolower($label) }}">{{ ucwords($label) }}</label>
    <small class="text-muted"><i>{{ $meta ?? '' }}</i></small>
    <select class="form-control" {{ $disabled ? 'disabled' : '' }} multiple {{ $required ? 'required' : '' }}
        name="{{ $name }}" data-search="on" id="{{ $id }}" style="width: 100%"
        placeholder="Select Permission">
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
                placeholder: 'Select Permission',
                ajax: {
                    url: '{{ route('permissions.list') }}',
                    delay: 250,
                    cache: true,
                    data: function(params) {
                        return {
                            search: params.term,
                        }
                    },
                    processResults: function(result) {
                        return {
                            results: result.map(function(perm) {
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

        selectedItems();
        $('#users').change(function() {
            $('#{{ $id }}').val(null).trigger('change');
            selectedItems();
        })

        function selectedItems() {
            let id = "{{ $user_id }}";
            $.ajax({
                url: '{{ route('users.permission') }}',
                data: {
                    user: id
                },
                method: 'GET',
                success: function(userPermissions) {
                    $('#{{ $id }}').empty();
                    userPermissions.forEach(function(data) {
                        var option = new Option(data.name, data.id, true, true);

                        $('#{{ $id }}').append(option).trigger('change');
                    });
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching data:', error);
                }
            });
        }
    </script>
@endpush
