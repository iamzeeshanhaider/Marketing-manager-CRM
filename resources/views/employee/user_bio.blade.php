<div class="main-body">
    <div class="row gutters-sm">
        <div class="col-md-4">
            <div class="mb-3 card border-0 shadow">
                <div class="card-body">
                    <div class="text-center d-flex flex-column align-items-center">
                        <img src="{{ $user->avatar }}" alt="Admin" class="rounded-circle" width="150" height="150">
                        <div class="mt-3">
                            <h4>{{ $user->name }}</h4>
                            <p class="text-muted font-size-sm">{{ $user->email }}</p>
                            <div class="badge badge-primary">{{ $user->getRoleNames()->first() }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-3 card border-0 shadow">
                <ul class="list-group list-group-flush">
                    <li class="flex-wrap list-group-item d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">Tickets</h6>
                        <span class="text-secondary">{{ count($user->tickets) }}</span>
                    </li>
                    <li class="flex-wrap list-group-item d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">Messages</h6>
                        <span class="text-secondary">{{ count($user->messages) }}</span>
                    </li>
                    <li class="flex-wrap list-group-item d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">Last Login</h6>
                        <span
                                class="text-secondary">{{ $user->last_login ? $user->last_login->diffForHumans() : 'Has Never Logged in' }}</span>
                    </li>
                    @if ($user->activities->last())
                        <li class="flex-wrap list-group-item d-flex justify-content-between align-items-center">
                            <h6 class="mb-0">Recent Activity</h6>
                            <span
                                    class="text-secondary">{{ ucfirst(strtolower($user->activities->last()->action)) . ' user_bio.blade.php' . $user->activities->last()->module_name }}</span>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
        <div class="col-md-8">
            <div class="mb-3 card border-0 shadow">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-3">
                            <h6 class="mb-0">Full Name:</h6>
                        </div>
                        <div class="col-sm-9 text-secondary">
                            {{ $user->name }}
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-3">
                            <h6 class="mb-0">Email:</h6>
                        </div>
                        <div class="col-sm-9 text-secondary">
                            {{ $user->email }}
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-3">
                            <h6 class="mb-0">Phone:</h6>
                        </div>
                        <div class="col-sm-9 text-secondary">
                            {{ $user->phone ?? '' }}
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-3">
                            <h6 class="mb-0">Gender:</h6>
                        </div>
                        <div class="col-sm-9 text-secondary">
                            {{ ucwords($user->gender) }}
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-3">
                            <h6 class="mb-0">Date of Birth:</h6>
                        </div>
                        <div class="col-sm-9 text-secondary">
                            {{ $user->dob ? $user->dob->format('d m, Y') : '' }}
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-3">
                            <h6 class="mb-0">Country:</h6>
                        </div>
                        <div class="col-sm-9 text-secondary">
                            {{ $user->country->name ?? '' }}
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-3">
                            <h6 class="mb-0">City:</h6>
                        </div>
                        <div class="col-sm-9 text-secondary">
                            {{ $user->city ?? '' }}
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-3">
                            <h6 class="mb-0">Address:</h6>
                        </div>
                        <div class="col-sm-9 text-secondary">
                            {{ $user->address ?? '' }}
                        </div>
                    </div>
                    <hr>
                    @if (!$user->hasRole('agent'))
                        <div class="row">
                            <div class="col-sm-3">
                                <h6 class="mb-0">Designation:</h6>
                            </div>
                            <div class="col-sm-9 text-secondary">
                                {{ $user->designation ?? '' }}
                            </div>
                        </div>
                        <hr>
                    @endif
                    <div class="row">
                        <div class="col-sm-3">
                            <h6 class="mb-0">Role:</h6>
                        </div>
                        <div class="col-sm-9 text-secondary">
                            {{ $user->getRoleNames()->first() }}
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-3">
                            <h6 class="mb-0">Permissions:</h6>
                        </div>
                        <div class="col-sm-9 text-secondary">
                            <div class="row">
                                @foreach ($user->getPermissionNames()->chunk(5) as $permissions)
                                    <div class="col-md-6 p-2">
                                        <ul>
                                            @foreach ($permissions as $permission)
                                                <ol>
                                                    &check; {{ ucwords(str_replace('_', ' ', $permission)) }}
                                                </ol>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-12">
                            <a onclick="handleGeneralModal(this)" class="text-white btn btn-primary"
                               data-link="{{ route('users.edit', ['group' => $group, 'user' => $user->slug]) }}"
                               title="Edit User">
                                Edit
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            @if ($user->hasRole('student'))
                <div class="row gutters-sm">
                    <div class="mb-3 col-sm-6">
                        @foreach ($user->courses as $course)
                            <h6 class="mb-3 d-flex align-items-center">{{ $course->title }}</h6>
                            <i class="mr-2 text-info">progress report</i>
                            @foreach ($course->lessons as $course)
                                <small>Web Design</small>
                                <div class="mb-3 progress" style="height: 5px">
                                    <div class="progress-bar bg-primary" role="progressbar" style="width: 80%"
                                         aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            @endforeach
                            <div class="mb-3 progress" style="height: 5px">
                                <div class="progress-bar bg-primary" role="progressbar" style="width: 66%"
                                     aria-valuenow="66" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

</div>
