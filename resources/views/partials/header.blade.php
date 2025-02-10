<header class="topbar header-topbar">
    <nav class="navbar top-navbar navbar-toggleable-sm navbar-light">
        <!-- ============================================================== -->
        <!-- Logo -->
        <!-- ============================================================== -->
        <div class="navbar-header">
            <a class="navbar-brand" href="{{url('dashboard')}}">
                <!-- Logo icon -->
                <b>
                    <!-- Dark Logo icon -->
                    <img src="{{url(asset('public/img/mhblogo.png'))}}" width="140" alt="homepage" class="dark-logo"/>
                </b>
                <!-- End Logo icon -->
                <!-- Logo text -->
                <span>
                    <!-- Light Logo text -->
                </span>
            </a>
        </div>
        <!-- ============================================================== -->
        <!-- End Logo -->
        <!-- ============================================================== -->
        <div class="navbar-collapse">
            <!-- ============================================================== -->
            <!-- Toggle and nav items -->
            <!-- ============================================================== -->
            <ul class="navbar-nav mr-auto mt-md-0">
                <!-- ============================================================== -->
                <!-- End Messages -->
                <!-- ============================================================== -->
            </ul>
            <!-- ============================================================== -->
            <!-- User profile and search -->
            <!-- ============================================================== -->
            <ul class="navbar-nav my-lg-0">
                <li class="nav-item dropdown header-nav-dropdown">
                    <a class="nav-link nav-toggler hidden-md-up text-muted waves-effect waves-dark"
                       href="javascript:void(0)">
                        <i class="ti-menu header-navs"></i>
                    </a>
                    {{--                    <a class="nav-link dropdown-toggle text-muted waves-effect waves-dark" id="dropdownMenuLink" href=""--}}
                    {{--                       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">--}}
                    {{--                        <span class="header-navs">--}}
                    {{--                            <i class="mdi mdi-bell icon-size"></i>--}}
                    {{--                        </span>--}}
                    {{--                    </a>--}}

                    {{--                    <div class="dropdown-menu dropdown-menu-right">--}}
                    {{--                        <ul class="dropdown-user">--}}
                    {{--                            <li>--}}
                    {{--                                <a href="#">No notification</a>--}}
                    {{--                            </li>--}}
                    {{--                            <li role="separator" class="divider"></li>--}}
                    {{--                        </ul>--}}
                    {{--                    </div>--}}
                </li>

                <li class="nav-item dropdown header-nav-dropdown">
                    <a class="nav-link dropdown-toggle text-muted waves-effect waves-dark" href="#"
                       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="header-navs">
                        <i class="mdi mdi-account-circle icon-size"></i>
                        </span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <ul class="list-unstyled">
                            <li>
                                <a href="#" class="dropdown-item">
                                    <i class="mdi mdi-account"></i> {{ Auth::user()->first_name . ' ' . Auth::user()->last_name }}
                                </a>
                            </li>
                            <li>
                                <a href="{{ url('change-password') }}" class="dropdown-item">
                                    <i class="mdi mdi-lock"></i> Change Password
                                </a>
                            </li>
                            <li role="separator" class="divider"></li>
                            <li>
                                <form id="logout-form" action="{{ url('weblogout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item btn-logout">
                                        <i class="mdi mdi-power"></i> Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
</header>
