<!-- Left Sidebar - style you can find in sidebar.scss  -->
<!-- ============================================================== -->
<aside class="left-sidebar">
    <!-- Sidebar scroll-->
    <div class="scroll-sidebar">
        <!-- User profile -->

        <!-- End User profile text-->
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav">
            <ul id="sidebarnav">


                <li>
                    <a  href="{{url('/dashboard')}}" aria-expanded="false"><i class="mdi mdi-view-dashboard"></i><span class="hide-menu">Dashboard </span></a>

                    {{--                        <li><a href="{{url('/')}}">Dashboard</a></li>--}}

                </li>
                <li>
                    <a class="has-arrow" href="#" aria-expanded="false"><i class="mdi mdi-settings-box"></i><span class="hide-menu">Management </span></a>

                    @if(\Illuminate\Support\Facades\Auth::user()->user_type==1)

                        <ul aria-expanded="false" class="collapse" >

                            <li><a href="{{url('/organization')}}">Organizations</a></li>

                            <li><a href="{{url('/roles')}}">Roles</a></li>

                            <li><a href="{{url('/users')}}">Users</a></li>
                            <li><a href="{{url('/users-delegate')}}">Delegate</a></li>


                        </ul>

                    @elseif(\Illuminate\Support\Facades\Auth::user()->user_type==1)

                        <ul aria-expanded="false" class="collapse" >

                            <li><a href="{{url('/organization/details-management')}}">Organizations Details</a></li>

                            <li><a href="{{url('/roles')}}">Roles</a></li>

                            <li><a href="{{url('organization/users-all')}}">Users</a></li>

                        </ul>

                    @endif
                </li>

                <li>
                    <a class="has-arrow" href="#" aria-expanded="false"><i class="mdi mdi-cash"></i><span class="hide-menu">Disbursement </span></a>

                    <ul aria-expanded="false" class="collapse" >

                        <li><a href="{{url('/disbursement/verification')}}">View verification Status</a></li>
                        <li><a href="{{url('/disbursement/payments')}}">View payment Status</a></li>

                    @if(\Illuminate\Support\Facades\Auth::user()->user_type==2)

                            <li><a href="{{url('/disbursement/create')}}">Payment</a></li>

                        @endif

                    </ul>
                </li>

                <li>
                    <a class="has-arrow" href="#" aria-expanded="false"><i class="mdi mdi-archive"></i><span class="hide-menu">Reports </span></a>

                    @if(\Illuminate\Support\Facades\Auth::user()->user_type==1)

                        <ul aria-expanded="false" class="collapse" >

                            <li><a href="{{url('reports/disbursement-per-organization')}}">Disbursement Per Organization</a></li>
                            <li><a href="{{url('reports/disbursement-by-date')}}">Disbursement By Date</a></li>
                            <li><a href="{{url('reports/disbursement-per-batch')}}">Disbursement By Batch Number</a></li>

                        </ul>

                    @elseif(\Illuminate\Support\Facades\Auth::user()->user_type==2)
                        <ul aria-expanded="false" class="collapse" >

                            <li><a href="{{url('reports/disbursement-by-date')}}">Disbursement By Date</a></li>
                            <li><a href="{{url('reports/disbursement-per-batch')}}">Disbursement By Batch Number</a></li>


                        </ul>

                    @endif

                </li>


            </ul>
        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->

</aside>
