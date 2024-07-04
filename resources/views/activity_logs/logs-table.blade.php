
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">


@foreach ($logs as $index => $log)
    <tr>
        <td>{{ $index + 1 }}</td>
        <td>
            <div>
                <p> {{ $log->model_type }}</p>
            </div>
        </td>
        <td>
            <p> {{ $log->guard_name }}</p>
        </small>
    </td>
    <td>
        <p> {{ $log->module_name }}</p>
    </td>

    <td>
        <p>{{ $log->action }}</p>
    </td>

    <td>
        <p>{{ $log->old_value }}</p>
    </td>
    <td>
        <p>{{ $log->new_value }}</p>
    </td>

    <td>
        <div class="d-flex align-items-center">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#logModal">
                View
            </button>

        </div>
    </td>
</tr>
@endforeach

<div class="container mt-5">
    <!-- Modal -->
    <div class="modal fade" id="logModal" tabindex="-1" aria-labelledby="activityLogs" aria-hidden="hidden">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="activityLogs">Activity Logs</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <div class="card">
                        <div class="card-body">

                            <div class="col-12">
                                <h5 class="mt-3 card-title">Old Value:</h5>
                                <code>
                                    {{ isset($log->old_value) ? $log->old_value : ''  }}
                                </code>
                            </div>
                            <div class="col-12">
                                <h5 class="mt-3 card-title">New Value:</h5>
                                <code>
                                    {{ isset($log->new_value) ? $log->new_value : ''}}
                                </code>
                            </div>

                            <div class="weather-category twt-category">
                                <hr>
                                <ul>
                                    <li class="active">
                                        <a href="#" title="Model">
                                            <span class="h6">{{ isset($log->module_name) ? $log->module_name : '' }}</span>
                                            <br>
                                            Model
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" title="User">
                                            <span class="h5">{{ isset($log->id) ? $log->user->getRoleNames()->first(): '' }}</span>
                                            <br>
                                            User Role
                                        </a>
                                    </li>
                                </ul>
                                <hr>
                                <ul>
                                    <li>
                                        <a href="#" title="Action">
                                            <span class="h5">{{isset($log->action) ? $log->action : ''}}</span>
                                            <br>
                                            Action
                                        </a>
                                    </li>
                                    <li class="active">
                                        <a href="#" title="Date">
                                            <span class="h6">{{ isset($log->created_at) ? $log->created_at : ''}}</span>
                                            <br>
                                            Date
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" title="User">
                                            <span class="h5">{{ isset($log->ip_address) ? $log->ip_address : '' }}</span>
                                            <br>
                                            IP Address
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>


