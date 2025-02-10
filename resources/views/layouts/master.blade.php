<!DOCTYPE html>
<html class="no-js" lang="en">
<!--<![endif]-->

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Disbursement Portal">
    <meta name="csrf-token" content="{{ csrf_token() }}"/>

    <meta name="author" content="Mwanga Hakika Bank">
    <!-- Favicon icon -->
    <title>MHB Disbursement Portal</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" href="{{ url('public/img/mhb-icon.png') }}">
    <!-- Bootstrap Core CSS -->
    <link href="{{url(asset('public/assets/plugins/bootstrap/css/bootstrap.min.css'))}}" rel="stylesheet">
    <!-- chartist CSS -->

    <link href="{{url('public/assets/plugins/chartist-js/dist/chartist.min.css')}}" rel="stylesheet">


    <link href="{{url('public/assets/plugins/chartist-js/dist/chartist-init.css')}}" rel="stylesheet">
    <link href="{{url('public/assets/plugins/chartist-plugin-tooltip-master/dist/chartist-plugin-tooltip.css')}}"
          rel="stylesheet">
    <link href="{{url('public/assets/plugins/css-chart/css-chart.css')}}" rel="stylesheet">
    <!-- toast CSS -->
    <link href="{{url('public/assets/plugins/toast-master/css/jquery.toast.css')}}" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="{{url('public/css/style.css')}}" rel="stylesheet">
    <link href="{{url('public/css/custom-styles.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{url('public/css/select2.min.css')}}"/>

    <!-- You can change the theme colors from here -->
    <link href="{{url('public/css/colors/blue.css')}}" id="theme" rel="stylesheet">
    <link href="{{url('public/css/customization.css')}}" id="theme" rel="stylesheet">

    <link href="{{url('public/css/fileinput.css')}}" media="all" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="{{url('public/css/datepicker.css')}}"/>

    {{--    <script src="{{url('html5shiv.3.7.0.js')}}"></script>--}}
    {{--    <script src="{{url('public/js/respond.1.4.2.min.js')}}"></script>--}}
</head>
<body class="fix-header fix-sidebar card-no-border">
<!-- ============================================================== -->
<!-- Preloader - style you can find in spinners.css -->
<!-- ============================================================== -->
<div class="preloader">
    <svg class="circular" viewBox="25 25 50 50">
        <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"/>
    </svg>
</div>
<!-- ============================================================== -->
<!-- Main wrapper - style you can find in pages.scss -->
<!-- ============================================================== -->
<div id="main-wrapper">


    @include('partials.header')

    <!--  sidebar -->

    @include('partials.sidebar')


    <div class="page-wrapper pagewrapper">


        <div class="col-md-12">
            @if(auth()->user()->last_login)
                <p class="text-right last-login">
                    Last Login: {{ \Carbon\Carbon::parse(auth()->user()->last_login)->format('d-m-Y h:i A') }}
                </p>
            @endif
        </div>
        @yield('content')


        <div id="idle-timeout-dialog" data-backdrop="static" class="modal fade header-form" tabindex="-1" role="dialog"
             aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title mod-head">Your session is expiring soon</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <div class="modal-body">
                        <p>
                            <i class="fa fa-warning font-red" aria-hidden="true"></i> Your session will be locked in
                            <span id="idle-timeout-counter"></span> seconds.</p>
                        <p> Do you want to continue your session? </p>
                    </div>
                    <div class="modal-footer text-center">
                        <button id="idle-timeout-dialog-keepalive" type="button" class="btn btn-success"
                                data-dismiss="modal">Yes, Keep Working
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>


    <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true"
         id="mi-modal">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header d-block">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"
                                                                                                      class="header-navs">&times;</span>
                    </button>
                    <h4 class="modal-title header-navs" id="myModalLabel"></h4>
                </div>
                <div class="modal-body"></div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" id="modal-btn-yes">Yes</button>
                    <button type="button" class="btn btn-default" id="modal-btn-no">No</button>
                </div>
            </div>
        </div>
    </div>

</div>
<!-- All Jquery -->
<!-- ============================================================== -->
<script src="{{url('public/assets/plugins/jquery/jquery.min.js')}}"></script>
<!-- Bootstrap tether Core JavaScript -->
<script src="{{url('public/assets/plugins/bootstrap/js/tether.min.js')}}"></script>
<script src="{{url('public/assets/plugins/bootstrap/js/bootstrap.min.js')}}"></script>
<!-- slimscrollbar scrollbar JavaScript -->
<script src="{{url('public/js/jquery.slimscroll.js')}}"></script>
<!--Wave Effects -->
<script src="{{url('public/js/waves.min.js')}}"></script>
<!--Menu sidebar -->
<script src="{{url('public/js/sidebarmenu.js')}}"></script>
<!--stickey kit -->
<script src="{{url('public/assets/plugins/sticky-kit-master/dist/sticky-kit.min.j')}}s"></script>
<!--Custom JavaScript -->
<script src="{{url('public/js/custom.min.js')}}"></script>

<!-- This is data table -->
<script src="{{url('public/assets/plugins/datatables/jquery.dataTables.min.js')}}"></script>
<!-- ============================================================== -->
<!-- This page plugins -->
<!-- ============================================================== -->
<!-- chartist chart -->
<script src="{{url('public/assets/plugins/chartist-js/dist/chartist.min.js')}}"></script>
<script
    src="{{url('public/assets/plugins/chartist-plugin-tooltip-master/dist/chartist-plugin-tooltip.min.js')}}"></script>
<!-- Chart JS -->
<script src="{{url('public/assets/plugins/toast-master/js/jquery.toast.js')}}"></script>
<script src="{{url('public/js/datepicker.min.js')}}" type="text/javascript"></script>
<!-- Chart JS -->

<script src="{{url('public/js/toastr.js')}}"></script>

<script src="{{url('public/js/select2.min.js')}}"></script>


{{--<script src="/https://raw.githubusercontent.com/Talv/x-editable/develop/dist/bootstrap4-editable/js/bootstrap-editable.min.js"></script>--}}

<script src="{{url('public/js/validation.js')}}"></script>
<script src="{{url('public/js/customization.js')}}"></script>

<script src="{{ url('public/js/jquery.idletimeout.js') }}"></script>
<script src="{{ url('public/js/jquery.idletimer.js') }}"></script>

<script>
    // $('.table-search').DataTable({
    //
    //     'lengthChange':false
    // });
    // $('#table').DataTable({
    //
    //     'lengthChange':false
    // });


    var UIIdleTimeout = function () {
        return {
            init: function () {
                $("body").append(""), $.idleTimeout("#idle-timeout-dialog", "#idle-timeout-dialog .modal-content button:last", {
                    idleAfter: 600,
                    timeout: 3e4,
                    onTimeout: function () {
                        window.location = '{{ url('lock') }}';
                    },
                    onIdle: function () {
                        $("#idle-timeout-dialog").modal("show");
                        $("#idle-timeout-dialog-keepalive").on("click", function () {
                            $("#idle-timeout-dialog").modal("hide")
                        })
                    },
                    onCountdown: function (e) {
                        $("#idle-timeout-counter").html(e)
                    }
                })
            }
        }
    }();
    jQuery(document).ready(function () {
        UIIdleTimeout.init()
    });
</script>
<!-- ============================================================== -->
<!-- Style switcher -->
<!-- ============================================================== -->
<script src="{{url('public/assets/plugins/styleswitcher/jQuery.style.switcher.js')}}"></script>
<script src="{{url('public/js/fileinput.min.js')}}"></script>
<script src="{{url('public/assets/plugins/datatables/media/js/dataTables.bootstrap.min.js')}}"></script>
<script src="{{url('public/js/app.js')}}"></script>
@yield('scripts')
</body>
</html>
