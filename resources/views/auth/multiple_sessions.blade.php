<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MHB Disbursement Portal: Login</title>
    <link rel="icon" type="image/png" href="{{ url('public/img/mhb-icon.png') }}">
    <link href="{{ url('public/assets/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ url('public/css/customization.css') }}" id="theme" rel="stylesheet">
    <link href="{{ url('public/css/landing-styles.css') }}" rel="stylesheet">
    <link href="{{ url('public/css/style.css') }}" rel="stylesheet">
</head>
<body>
<div class="login-box">
    <div class="logo-circle">
        <img src="{{ url('public/img/mhb-logo.png') }}" alt="MHB Logo">
    </div>
    <div class="mt-3">
        @if ($errors->any())
            <div class="alert alert-danger alertbox-div"><a href="#" class="close"
                                                            data-dismiss="alert">×</a>
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif
        @foreach (['danger', 'warning', 'success', 'info'] as $msg)
            @if (Session::has('alert-' . $msg))
                <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }}
                    <a href="#" class="close" data-dismiss="alert"
                       aria-label="close"></a>
                </p>
            @endif
        @endforeach
    </div>
    <h4 class="">Multiple Sessions</h4>
    <div class="text-center"><i class="fa fa-laptop laptop-icon" aria-hidden="true"></i></div>
    <h3><span class="badge badge-secondary">{{ \Illuminate\Support\Facades\Auth::user()->username }}</span></h3>
    <div class="form-group">
        <div class="alert alert-danger" role="alert">
            You have an active login session on another device. You can only have one
            active login session.
        </div>
    </div>
    <form method="post" action="{{ url('logout-other-devices') }}">
        {{ csrf_field() }}
        <button
            class="btn login-btn"
            type="submit">Continue on this Device
        </button>

        <div class="text-danger text-left">
            <span class="small">**This will log you out on the other device</span>
        </div>
        <div class="col-xs-12 mt-2">
            <a class="btn btn-block login-btn"
               href="#" onclick="$('#logout-form').submit();">Logout on this Device</a>
        </div>
    </form>
    <form id="logout-form" action="{{ url('logout-current-session') }}" method="POST"
          class="header-form">
        {{ csrf_field() }}
    </form>
</div>

<script src="{{ url('public/assets/plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap tether Core JavaScript -->
<script src="{{ url('public/assets/plugins/bootstrap/js/bootstrap.min.js') }}"></script>
<script src="{{ url('public/assets/plugins/bootstrap/js/tether.min.js') }}"></script>
<!-- slimscrollbar scrollbar JavaScript -->
<script src="{{ url('public/js/slims.min.js') }}"></script>
<script src="{{ url('public/js/custom2.min.js') }}"></script>
</body>
</html>
