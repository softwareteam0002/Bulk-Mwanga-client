<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MHB Disbursement Portal: Change Password</title>
    <link rel="icon" type="image/png" href="{{ url('public/img/mhb-icon.png') }}">
    <link href="{{ url('public/assets/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ url('public/css/customization.css') }}" id="theme" rel="stylesheet">
    <link href="{{ url('public/css/landing-styles.css') }}" rel="stylesheet">
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
    <h5 class="">Change Credentials</h5>
    <div class="password-hints mt-3">
        <ul class="list-unstyled text-muted text-danger text-left">
            <li>✓ Must be at least 10 characters</li>
            <li>✓ At least one uppercase letter</li>
            <li>✓ At least one lowercase letter</li>
            <li>✓ At least one number</li>
            <li>✓ At least one special character</li>
            <li>✗ Cannot be a dictionary word</li>
            <li>✗ Cannot have more than 2 consecutive identical characters</li>
            <li>✗ Cannot be the same as last 12 passwords</li>
            <li>✗ Cannot be the same as your username</li>
        </ul>
    </div>

    <form method="post" action="{{ url('change-password') }}" class="mt-3">
        {{ csrf_field() }}
        <div class="form-group">
            <input type="password" name="oldPassword" class="form-control" placeholder="Old Password" autocomplete="off"
                   required value="{{ old('oldPassword') }}">
        </div>
        <div class="form-group">
            <input type="password" name="password" class="form-control" placeholder="New Password" autocomplete="off"
                   required>
        </div>
        <div class="form-group">
            <input type="password" name="password_repeated" class="form-control" placeholder="Repeat Password"
                   autocomplete="off" required>
        </div>
        <input type="hidden" name="username" value="{{$username}}">
        <button type="submit" class="btn login-btn">Change Password</button>
    </form>
    <!-- Logout to Home -->
    <form id="logout-code" action="{{ url('weblogout') }}" method="POST" class="mt-3" style="display: none;">
        {{ csrf_field() }}
        <button type="submit" class="btn logout-btn">Logout</button>
    </form>

    <a href="{{url('/dashboard')}}" class="forgot-password text-center mt-3">Go Back Home</a>

</div>

<script src="{{ url('public/assets/plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap tether Core JavaScript -->
<script src="{{ url('public/assets/plugins/bootstrap/js/bootstrap.min.js') }}"></script>
<script src="{{ url('public/assets/plugins/bootstrap/js/tether.min.js') }}"></script>
<script type="text/javascript">
    window.setTimeout(function () {
        $(".alert").fadeTo(1000, 0).slideUp(1000, function () {
            $(this).remove();
        });
    }, 6000);
</script>
<!-- slimscrollbar scrollbar JavaScript -->
<script src="{{ url('public/js/slims.min.js') }}"></script>
<script src="{{ url('public/js/custom2.min.js') }}"></script>
</body>
</html>
