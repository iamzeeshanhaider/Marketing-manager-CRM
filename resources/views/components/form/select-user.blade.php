@props([
    'label' => '',
    'name' => 'agent_id',
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
    $id = strtolower($name);
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
            if (isCompanySelected()) {
                var selectedCompany = $('#lead_company_select').select2('data')[0];
                if (selectedCompany) {
                    fetchUsers(selectedCompany);
                }
            }

            $('#lead_company_select').on('select2:select', function(e) {
                var company = e.params.data;
                if (company) {
                    fetchUsers(company);
                }
            });

            function isCompanySelected() {
                return $('#lead_company_select').val() !== null;
            }

            function fetchUsers(company) {
                var url = '{{ route('users.list') }}';
                var role = '{{ $role }}';
                url += '?company=' + encodeURIComponent(company.id);
                url += '?role=' + encodeURIComponent(role);

                $('#{{ $id }}').select2({
                    placeholder: 'Select and begin typing',
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
                                results: result.map(function(user) {
                                    return {
                                        id: user.id,
                                        text: user.name,
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
