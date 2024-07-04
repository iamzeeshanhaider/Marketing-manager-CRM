<div class="card">
    <div class="card-body">

        <div class="col-6">
            <h5 class="mt-3 card-title">Old Value:</h5>
            <code>
                {{ $log->old_value }}
            </code>
        </div>
        <div class="col-6">
            <h5 class="mt-3 card-title">New Value:</h5>
            <code>
                {{ $log->new_value }}
            </code>
        </div>

        <div class="weather-category twt-category">
            <hr>
            <ul>
                <li class="active">
                    <a href="#" title="Model">
                        <span class="h6">{{ $log->module_name }}</span>
                        <br>
                        Model
                    </a>
                </li>
                <li>
                    <a href="#" title="User">
                        <span class="h5">{{ $log->user->name }}</span>
                        <br>
                        User
                    </a>
                </li>
                <li>
                    <a href="#" title="User">
                        <span class="h5">{{ $log->user->getRoleNames()->first() }}</span>
                        <br>
                        User Role
                    </a>
                </li>
            </ul>
            <hr>
            <ul>
                <li>
                    <a href="#" title="Action">
                        <span class="h5">{{ $log->action }}</span>
                        <br>
                        Action
                    </a>
                </li>
                <li class="active">
                    <a href="#" title="Date">
                        <span class="h6">{{ $log->created_at }}</span>
                        <br>
                        Date
                    </a>
                </li>
                <li>
                    <a href="#" title="User">
                        <span class="h5">{{ $log->ip_address }}</span>
                        <br>
                        IP Address
                    </a>
                </li>
                {{-- <li>
                    <a href="#" title="Action">
                        <span class="h5">{{ $log->guard_name }}</span>
                        <br>
                        Guard
                    </a>
                </li> --}}
            </ul>
        </div>
    </div>
</div>
