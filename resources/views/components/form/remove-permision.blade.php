@props([
    'label' => '',
    'name' => 'agent_id',
    'id' => 'permission_id',
    'selected' => null,
    'required' => false,
    'readonly' => false,
    'disabled' => false,
    'multiple' => false,
    'meta' => null,
    'role' => 'agent',
    'isWire' => false,
])

@php
    $id = strtolower($id);
@endphp

<div class="form-group">
    <label for="{{ $id }}">{{ ucwords($label) }}</label>
    @if ($meta)
        <small class="text-muted"><i>{{ $meta }}</i></small>
    @endif
    <select class="form-control @error($name) is-invalid @enderror" {{ $readonly ? 'readonly' : '' }}
        {{ $disabled ? 'disabled' : '' }} {{ $multiple ? 'multiple' : '' }} {{ $required ? 'required' : '' }}
        @if ($isWire) wire:model="{{ $name }}" @else name="{{ $name }}" @endif
        data-search="on" id="{{ $id }}" style="width: 100%" placeholder="Select Company First">
        @if (is_array($selected) && !empty($selected))
        @foreach ($selected as $agent)
            @isset($agent)
                <option value="{{ $agent->id ?? '' }}">{{ $agent->name ?? '' }}</option>
            @endisset
        @endforeach
    @endif
    </select>
</div>

@push('scripts')
    <script>
        $(document).ready(function() {
            if (isuserSelected()) {
                var selecteduser = $('#users').val();
                if (selecteduser) {
                    fetchpermissions(selecteduser);
                }
            }

            $('#users').on('change', function(e) {
                var user = $('#users').val();
                if (user) {
                    fetchpermissions(user);
                }
            });

            function isuserSelected() {
                return $('#users').val() !== null;
            }

            function fetchpermissions(user) {
                var url = '{{ route('users.permission') }}';
                url += '?user=' + encodeURIComponent(user);

                $('#{{ $id }}').select2({
                    placeholder: 'User Permissions',
                    ajax: {
                        url: url,
                        delay: 250,
                        cache: true,
                        data: function(params) {
                            return {
                                search: params.term,
                            }
                        },
                        processResults: function(result) {
                            return {
                                results: result.map(function(permission) {
                                    return {
                                        id: permission.id,
                                        text: permission.name,
                                    }
                                })
                            }
                        },
                    }
                });
            }
        });
    </script>
@endpush
