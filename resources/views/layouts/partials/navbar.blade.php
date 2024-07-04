<nav class="header-navbar navbar-expand-md navbar navbar-with-menu fixed-top navbar-semi-dark navbar-shadow">
    <div class="navbar-wrapper">
        <div class="navbar-header">
            <ul class="nav navbar-nav">
                <li class="nav-item">
                    <a class="navbar-brand" href="{{ url('/') }}">
                        <img src="{{ asset('assets/img/logo.png') }}" width="60"
                            alt="{{ config('app.name', 'Laravel') }}">
                    </a>
                </li>
            </ul>
        </div>
        <div class="navbar-container content">
            <div class="collapse navbar-collapse" id="navbar-mobile">
                <ul class="nav navbar-nav mr-auto float-left">
                    <li class="nav-item d-none d-md-block">
                        <a class="toggle-container nav-link nav-menu-main menu-toggle" href="#">
                            <i class="la la-arrow-left" id="switch-icon"></i>
                        </a>
                    </li>
                </ul>
                <ul class="nav navbar-nav float-right">
                    @if (config('services.calender') === \App\Enums\MeetingType::Google)
                        <li class="dropdown dropdown-notification nav-item"><a class="nav-link nav-link-label"
                                href="{{ route('google.calendar.events') }}"><i class="ficon ft-calendar"></i></a>
                        </li>
                    @endif
                    @if (config('services.calender') === \App\Enums\MeetingType::Zoom)
                        <li class="dropdown dropdown-notification nav-item"><a class="nav-link nav-link-label"
                                href="{{ route('zoom.calendar.events') }}"><i class="ficon ft-calendar"></i></a>
                        </li>
                    @endif
                    {{-- TODO: implement notification with livewire --}}
                    <li class="dropdown dropdown-notification nav-item"><a class="nav-link nav-link-label"
                            href="#" data-toggle="dropdown"><i class="ficon ft-bell"></i><span
                                class="badge badge-pill badge-default badge-danger badge-default badge-up">5</span></a>
                        <ul class="dropdown-menu dropdown-menu-media dropdown-menu-right">
                            <li class="dropdown-menu-header">
                                <h6 class="dropdown-header m-0"><span class="grey darken-2">Notifications</span>
                                </h6>
                                <span class="notification-tag badge badge-default badge-danger float-right m-0">5
                                    New</span>
                            </li>
                            <li class="scrollable-container media-list w-100"><a href="javascript:void(0)">
                                    <div class="media">
                                        <div class="media-left align-self-center"><i
                                                class="ft-plus-square icon-bg-circle bg-cyan"></i></div>
                                        <div class="media-body">
                                            <h6 class="media-heading">You have new order!</h6>
                                            <p class="notification-text font-small-3 text-muted">Lorem ipsum dolor
                                                sit
                                                amet, consectetuer elit.</p><small>
                                                <time class="media-meta text-muted"
                                                    datetime="2015-06-11T18:29:20+08:00">30 minutes
                                                    ago</time></small>
                                        </div>
                                    </div>
                                </a><a href="javascript:void(0)">
                                    <div class="media">
                                        <div class="media-left align-self-center"><i
                                                class="ft-download-cloud icon-bg-circle bg-red bg-darken-1"></i>
                                        </div>
                                        <div class="media-body">
                                            <h6 class="media-heading red darken-1">99% Server load</h6>
                                            <p class="notification-text font-small-3 text-muted">Aliquam tincidunt
                                                mauris eu risus.</p><small>
                                                <time class="media-meta text-muted"
                                                    datetime="2015-06-11T18:29:20+08:00">Five hour
                                                    ago</time></small>
                                        </div>
                                    </div>
                                </a><a href="javascript:void(0)">
                                    <div class="media">
                                        <div class="media-left align-self-center"><i
                                                class="ft-alert-triangle icon-bg-circle bg-yellow bg-darken-3"></i>
                                        </div>
                                        <div class="media-body">
                                            <h6 class="media-heading yellow darken-3">Warning notifixation</h6>
                                            <p class="notification-text font-small-3 text-muted">Vestibulum auctor
                                                dapibus neque.</p><small>
                                                <time class="media-meta text-muted"
                                                    datetime="2015-06-11T18:29:20+08:00">Today</time></small>
                                        </div>
                                    </div>
                                </a><a href="javascript:void(0)">
                                    <div class="media">
                                        <div class="media-left align-self-center"><i
                                                class="ft-check-circle icon-bg-circle bg-cyan"></i></div>
                                        <div class="media-body">
                                            <h6 class="media-heading">Complete the task</h6><small>
                                                <time class="media-meta text-muted"
                                                    datetime="2015-06-11T18:29:20+08:00">Last week</time></small>
                                        </div>
                                    </div>
                                </a><a href="javascript:void(0)">
                                    <div class="media">
                                        <div class="media-left align-self-center"><i
                                                class="ft-file icon-bg-circle bg-teal"></i></div>
                                        <div class="media-body">
                                            <h6 class="media-heading">Generate monthly report</h6><small>
                                                <time class="media-meta text-muted"
                                                    datetime="2015-06-11T18:29:20+08:00">Last month</time></small>
                                        </div>
                                    </div>
                                </a></li>
                            <li class="dropdown-menu-footer"><a class="dropdown-item text-muted text-center"
                                    href="javascript:void(0)">Read all notifications</a></li>
                        </ul>
                    </li>
                    @if (auth()->user()->hasPermissionTo('all_permissions') || auth()->user()->hasPermissionTo('select_company'))
                        <li class="dropdown dropdown-notification nav-item"><a class="nav-link nav-link-label"
                                href="#" data-toggle="dropdown"><i class="fa fa-building"
                                    aria-hidden="true"></i></a>
                            <ul class="dropdown-menu dropdown-menu-media dropdown-menu-right">
                                <li class="dropdown-menu-header">
                                    <h6 class="dropdown-header m-0"><span class="grey darken-2">Companies</span>
                                    </h6>

                                </li>
                                <li class="scrollable-container media-list w-100">
                                    @forelse (\App\Models\Company::active()->get() as $company)
                                        <a href="" class="update_select" id="{{ $company->id }}">
                                            <div class="media">
                                                <div class="media-body">
                                                    <h6 class="media-heading">
                                                        {{ $company->name }}</h6>
                                                    @if (\App\Models\Company::first()->id == $company->id)
                                                        <span
                                                            class="notification-tag badge badge-default badge-danger float-right m-0">Selected
                                                        </span>
                                                    @endif

                                                </div>
                                            </div>
                                        </a>
                                    @empty
                                    @endforelse


                                </li>

                            </ul>
                        </li>
                    @endif
                    {{-- TODO: implement notification with livewire --}}

                    <li class="dropdown dropdown-user nav-item">
                        <a class="dropdown-toggle nav-link dropdown-user-link" href="#" data-toggle="dropdown">
                            <span class="avatar avatar-online">
                                <img src="{{ Auth::user()->getAvatar() }}" alt="{{ Auth::user()->name }}">
                                <i></i>
                            </span>
                            <span class="user-name">{{ ucwords(Auth::user()->name) }}</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            {{-- TODO: create edit profile page --}}
                            <a class="dropdown-item" href="{{ route('user.profile') }}"><i class="ft-user"></i>
                                Edit
                                Profile</a>
                            <div class="dropdown-divider"> </div>
                            <a class="dropdown-item" href="{{ route('logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="ft-power"></i> {{ __('Logout') }}
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>
