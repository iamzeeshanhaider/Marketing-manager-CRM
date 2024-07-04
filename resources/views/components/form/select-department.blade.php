@props([
    'label' => '',
    'name' => 'department_id',
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
        placeholder="Select Company First">
        @if ($selected !== null)
            @foreach ($selected as $department)
                <option value="{{ $department->id ?? '' }}">{{ $department->name ?? '' }}</option>
            @endforeach
        @endif
    </select>
</div>

@push('scripts')
    <script>
        $(document).ready(function() {
            if (isCompanySelected()) {
                var selectedCompany = $('#employee_company_select').select2('data')[0];
                if (selectedCompany) {
                    fetchDepartments(selectedCompany);
                }
            }

            $('#employee_company_select').on('select2:select', function(e) {
                var company = e.params.data;
                if (company) {
                    fetchDepartments(company);
                }
            });

            function isCompanySelected() {
                return $('#employee_company_select').val() !== null;
            }

            function fetchDepartments(company) {
                $('#{{ $name }}').select2({
                    placeholder: 'Select and begin typing',
                    ajax: {
                        url: '{{ route('department.list', ['company' => ':companyId']) }}'
                            .replace(':companyId', company.id),
                        delay: 250,
                        cache: true,
                        data: function(params) {
                            return {
                                search: params.term,
                            }
                        },
                        processResults: function(result) {
                            return {
                                results: result.map(function(department) {
                                    return {
                                        id: department.id,
                                        text: department.name,
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
