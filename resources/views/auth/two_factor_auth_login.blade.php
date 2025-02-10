<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MHB Disbursement Portal: Verify Code</title>
    <link rel="icon" type="image/png" href="{{ url('public/img/mhb-icon.png') }}">
    <link href="{{ url('public/assets/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
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
    <h4 class="">Verify Code</h4>
    <form class="form-horizontal form-material mt-3" id="loginform" method="post" action="{{ url('login-verify') }}">
        {{ csrf_field() }}
        <div class="otp-container">
            @for ($i = 1; $i <= 6; $i++)
                <input type="text" name="token[]" class="form-control otp-box" maxlength="1" autocomplete="off"
                       oninput="moveToNext(this, {{ $i }})" onkeydown="moveToPrev(event, {{ $i }})"
                       inputmode="numeric" pattern="[0-9]" required>
            @endfor
        </div>

        <button type="submit" class="btn login-btn">Verify</button>
    </form>

    <!-- Resend Token Form -->
    <form id="resend-token" action="{{ url('resend-token') }}" method="POST" class="header-form mt-3">
        {{ csrf_field() }}
        <button type="submit" class="btn login-btn">Resend Token</button>
    </form>

    <!-- Logout to Home -->
    <form id="logout-code" action="{{ url('weblogout') }}" method="POST" class="mt-3" style="display: none;">
        {{ csrf_field() }}
        <button type="submit" class="btn logout-btn">Logout</button>
    </form>

    <a href="#" class="forgot-password text-center mt-3" onclick="document.getElementById('logout-code').submit();">Go
        to Login</a>

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

    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll(".otp-box").forEach(input => {
            input.addEventListener("keypress", function (e) {
                if (!/[0-9]/.test(e.key)) {
                    e.preventDefault(); // Block non-numeric input
                }
            });

            input.addEventListener("paste", function (e) {
                e.preventDefault(); // Prevent pasting
            });
        });
    });
    // Move to next OTP box after filling one
    function moveToNext(currentBox, index) {
        if (currentBox.value.length == currentBox.maxLength) {
            var nextBox = document.querySelector(`input[name="token[]"]:nth-of-type(${index + 1})`);
            if (nextBox) nextBox.focus();
        }
    }

    // Move to previous OTP box when backspace is pressed
    function moveToPrev(event, index) {
        if (event.key === 'Backspace') {
            // Check if the box is empty, then move to the previous box
            if (document.querySelector(`input[name="token[]"]:nth-of-type(${index})`).value === "") {
                var prevBox = document.querySelector(`input[name="token[]"]:nth-of-type(${index - 1})`);
                if (prevBox) prevBox.focus();
            }
        }
    }
</script>
<!-- slimscrollbar scrollbar JavaScript -->
<script src="{{ url('public/js/slims.min.js') }}"></script>
<script src="{{ url('public/js/custom2.min.js') }}"></script>
</body>
</html>
