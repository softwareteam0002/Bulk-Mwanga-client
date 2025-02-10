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
                    <a href="{{url('/dashboard')}}" aria-expanded="false"><i class="mdi mdi-view-dashboard"></i><span
                                class="hide-menu">Dashboard </span></a>
                </li>

                @if(\Illuminate\Support\Facades\Auth::user()->user_type==2)
                    @if(\App\Models\Permission::createInitiator())
                        <li>
                            <a class="has-arrow" href="#" aria-expanded="false"><i
                                        class="mdi mdi-settings-box"></i><span class="hide-menu">Management </span></a>
                            <ul aria-expanded="false" class="collapse sidebar-background">
                                {{--                                <li><a href="{{url('/setup')}}">Setup</a></li>--}}
                                <li>
                                    <a href="{{route('organization-initiator',['q'=>'adin','orid'=>encrypt(\Illuminate\Support\Facades\Auth::user()->organization_id)])}}">Manage
                                        Initiator</a></li>
                            </ul>
                        </li>
                    @endif
                    {{--@endif--}}
                @endif

                <li>
                    <a class="has-arrow" href="#" aria-expanded="false"><i class="mdi mdi-cash"></i><span
                                class="hide-menu">Payment To Wallet</span></a>
                    <ul aria-expanded="false" class="collapse sidebar-background">
                        <li><a href="{{url('/disbursement/create')}}">Initiate Payment</a></li>
                        <li><a href="{{url('/disbursement/verification')}}">Wallet Verification Status</a></li>
                        <li><a href="{{url('/disbursement/payments')}}">Wallet Payment Status</a></li>


                    </ul>
                </li>

                <li>
                    <a class="has-arrow" href="#" aria-expanded="false"><i class="mdi mdi-cash"></i><span
                                class="hide-menu">Payment To Bank </span></a>
                    <ul aria-expanded="false" class="collapse sidebar-background">
                        <li><a href="{{url('/bank-disbursement/create')}}">Initiate Payment</a></li>

                        <li><a href="{{url('/bank-disbursement/verifications')}}">Bank Verification Status</a></li>
                        <li><a href="{{url('/bank-disbursement/payments')}}">Bank Payment Status</a></li>


                    </ul>
                </li>

                <li>
                    <a class="has-arrow" href="#" aria-expanded="false"><i class="mdi mdi-archive"></i><span
                                class="hide-menu">Reports </span></a>
                    @if(\Illuminate\Support\Facades\Auth::user()->user_type==2)

                        <ul class="collapse">
                            <li>
                                <a class="has-arrow" href="#" aria-expanded="false"><span class="hide-menu">Mobile Wallet Reports</span></a>
                                <ul aria-expanded="false" class="collapse sidebar-background">
                                    <li><a href="{{url('reports/disbursement-by-date')}}">By Date</a></li>
                                    <li><a href="{{url('reports/disbursement-per-batch')}}">By Batch
                                            Number</a></li>
                                </ul>
                            </li>

                            <li>
                                <a class="has-arrow" href="#" aria-expanded="false"><span
                                            class="hide-menu">Bank Reports</span></a>
                                <ul aria-expanded="false" class="collapse sidebar-background">
                                    <li><a href="{{url('reports/bank/disbursement-by-date')}}">By Date</a>
                                    </li>
                                    <li><a href="{{url('reports/bank/disbursement-per-batch')}}">By Batch
                                            Number</a></li>
                                </ul>
                            </li>
                            <li>
                                <a class="has-arrow" href="#" aria-expanded="false"><span class="hide-menu">General Reports</span></a>
                                <ul aria-expanded="false" class="collapse">
                                    <li><a href="{{url('reports/general/disbursement-by-date')}}">By
                                            Date</a></li>
                                </ul>
                            </li>


                        </ul>

                    @endif
                </li>

            </ul>
        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->

</aside>
