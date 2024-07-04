@props([
    'headings' => ['id', 'name', 'action'],
    'title' => 'Company', // always pass title as singluar
    'subtitle' => null, // this parameter is optional
    'route' => '', // this parameter is required
    'addon' => '', // this parameter is required
])

<style>
    table.dataTable tbody tr.selected a {
        color: #212529 !important
    }
</style>

<div class="row">
    <div class="col-12">
        <div class="card border-0">
            <div class="card-header border-0 bg-white">
                <div>
                    <h4 class="card-title border-0 bg-white">All {{ ucwords(\Str::plural($title)) }}</h4>
                    @if ($subtitle)
                        <p class="small">{!! $subtitle ?? '' !!}</p>
                    @endif
                </div>
                <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                <div class="heading-elements">
                    <ul class="list-inline mb-0">
                        @isset($addons)
                            {{ $addons }}
                        @endisset
                        <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                    </ul>
                </div>
            </div>
            <div class="card-content">
                <div class="card-body card-dashboard table-responsive">
                    <table class="table table-striped table-bordered zero-configuration" style="width: 100%"
                        id="{{ strtolower($title) }}">
                        <thead>
                            <tr>
                                @foreach ($headings as $heading)
                                    <th>{{ strtoupper(str_replace('_', ' ', $heading)) }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            {{ $slot }}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
    <script>
        $(document).ready(function() {
            var isAgent = @json(auth()->user()->hasRole('Agent'));

            if (!isAgent) {
                loadDatatable();
            } else {
                loadDatatable(@json(auth()->user()->id));
            }
            $('#select-all-button').on('click', function() {
                var anyChecked = $(':checkbox:checked').length > 0;
                if (!anyChecked) {
                    selectAllCheckboxes();

                } else {
                    unselectAllCheckboxes();
                }
            });

            // Leads according to the agent




            // Select row
            $(table_id + ' tbody').on('click', 'tr', function() {
                if ($(this).hasClass('selected')) {
                    $(this).removeClass('selected');
                } else {
                    $(table_id).DataTable().$('tr.selected').removeClass('selected');
                    $(this).addClass('selected');
                }
            });
        });

        function selectAllCheckboxes() {
            $(':checkbox').prop('checked', true);
            $('#select-all-button').val('UnSelect');
        }

        function unselectAllCheckboxes() {
            $(':checkbox').prop('checked', false);
            $('#select-all-button').val('Check All');
        }

        function loadDatatable(agent = null) {
            var selectedCompany = "{{ \App\Models\Company::first()->id }}";
            loadComapnyAgents(selectedCompany);
            table_id = '#' + @json($title).toLowerCase();
            if ($.fn.DataTable.isDataTable(table_id)) {
                $(table_id).DataTable().destroy();
            }

            var urlParams = new URLSearchParams(window.location.search);
            var statusParameter = urlParams.get('status');
            var ajaxUrl = "{{ route('leads.index') }}?company=" + selectedCompany;
            if (statusParameter) {
                ajaxUrl += '&status=' + statusParameter;
            }
            if (agent) {
                ajaxUrl += '&agent_id=' + agent;
            }
            $(table_id).DataTable({
                processing: true,
                serverSide: true,
                ajax: ajaxUrl,
                columns: [
                    //{ data: 'checkbox', name: 'checkbox', orderable: false, searchable: false, width: '5%' }, // Checkbox column
                    @foreach ($headings as $heading)
                        @if ($heading === 'address')
                            {
                                data: '{{ $heading }}',
                                name: '{{ $heading }}',
                                orderable: false,
                                searchable: false,
                                width: '5%'
                            },
                        @else
                            {
                                data: '{{ $heading }}',
                                name: '{{ $heading }}',
                                orderable: true,
                                searchable: true,
                                width: 'auto'
                            },
                        @endif
                    @endforeach
                ],
                "lengthMenu": [
                    [10, 25, 50, -1],
                    [10, 25, 50, "All"]
                ],
                select: {
                    style: 'os',
                    selector: 'td:first-child',
                },
            });
            history.pushState({}, '', ajaxUrl);
        }


        function loadComapnyAgents(company_id) {
            if (company_id) {
                $.ajax({
                    url: "{{ route('company.agents') }}",
                    method: "POST",
                    data: {
                        company_id: company_id,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(data) {
                        if (data.success) {
                            var selectBox = document.getElementById('lead_agent_select');
                            selectBox.innerHTML = '';
                            console.log(data.users);
                            for (var userId in data.users) {
                                if (data.users.hasOwnProperty(userId)) {
                                    var option = document.createElement('option');
                                    option.value = userId;
                                    option.textContent = data.users[userId];
                                    selectBox.appendChild(option);
                                }
                            }
                        } else {
                            toastr.error(data.message);
                        }
                    }
                });
            }
        }
    </script>
@endpush
