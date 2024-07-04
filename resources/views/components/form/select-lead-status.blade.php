@props([
    'label' => '',
    'name' => 'status',
    'id' => 'select_lead_status',
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
        @if ($isWire) wire:model="{{ $name }}" @else name="{{ $name }}" @endif
        data-search="on" id="{{ $id }}" style="width: 100%">
        @if ($selected)
            <option value="{{ $selected->id ?? '' }}">{{ $selected->name ?? '' }}</option>
            @else
            <option value="1">Open</option>
        @endif
    </select>
</div>

@push('scripts')
    <script>
        $(document).ready(function() {
            if (isCompanySelected()) {
                var selectedCompany = $('#lead_company_select').select2('data')[0];
                if (selectedCompany) {
                    fetchLeadStatus(selectedCompany);
                }
            }

            $('#lead_company_select').on('select2:select', function(e) {
                var company = e.params.data;
                if (company) {
                    fetchLeadStatus(company);
                }
            });

            function isCompanySelected() {
                return $('#lead_company_select').val() !== null;
            }

            function fetchLeadStatus(company) {
                var url = '{{ route('lead_status.list') }}';
                url += '?company=' + encodeURIComponent(company.id);

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
                                results: result.map(function(lead) {
                                    return {
                                        id: lead.id,
                                        text: lead.name,
                                    }
                                })
                            }
                        },
                        initSelection: function (element, callback) {
            // You can set the default selected value here
            var defaultSelection = { id: '1', text: 'open' };
            callback(defaultSelection);
        }
                    }
                });
            }

        });
    </script>
@endpush
