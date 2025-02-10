<!DOCTYPE html>
<html class="no-js" lang="en">
<!--<![endif]-->

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{url(asset('public/assets/images/logo.png'))}}">
    <title>vodacom</title>
    <!-- Bootstrap Core CSS -->
    <link href="/assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <link href="/assets/plugins/toast-master/css/jquery.toast.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="/css/style.css" rel="stylesheet">
    <!-- You can change the theme colors from here -->
    <link href="/css/colors/blue.css" id="theme" rel="stylesheet">
    <link href="/css/customization.css" id="theme" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="{{url('html5shiv.3.7.0.js')}}"></script>
    <script src="{{url('public/js/respond.1.4.2.min.js')}}"></script>

    <![endif]-->



</head>
<body class="card-no-border">
<!-- ============================================================== -->
<!-- Preloader - style you can find in spinners.css -->
<!-- ============================================================== -->
<div class="preloader">
    <svg class="circular" viewBox="25 25 50 50">
        <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" /> </svg>
</div>
<!-- ============================================================== -->
<!-- Main wrapper - style you can find in pages.scss -->
<!-- ============================================================== -->
<div id="main-wrapper">


<!--  sidebar -->

    <div class="page-wrapper" style="background-color: white;">


        @yield('content')


    </div>


</div>
<!-- All Jquery -->
<!-- ============================================================== -->
<script src="/assets/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap tether Core JavaScript -->
<script src="/assets/plugins/bootstrap/js/tether.min.js"></script>
<script src="/assets/plugins/bootstrap/js/bootstrap.min.js"></script>
<!-- slimscrollbar scrollbar JavaScript -->
<script src="/js/jquery.slimscroll.js"></script>
<!--Wave Effects -->
<script src="/js/waves.js"></script>

<script src="/js/custom.min.js"></script>


<script src="/assets/plugins/toast-master/js/jquery.toast.js"></script>

<script src="/js/toastr.js"></script>

<script src="/js/customization.js"></script>

<script>
    // $.toast({
    //     heading: 'Welcome to Monster admin',
    //     text: 'Use the predefined ones, or specify a custom position object.',
    //     position: 'top-right',
    //     loaderBg:'#ff6849',
    //     icon: 'info',
    //     hideAfter: 3000,
    //     stack: 6
    // });

    $('#table').DataTable({

        'lengthChange':false,
        "paging": false
    });
</script>
<!-- ============================================================== -->
<!-- Style switcher -->
<!-- ============================================================== -->
<script src="{{url('public/assets/plugins/styleswitcher/jQuery.style.switcher.js')}}"></script>

@yield('scripts')
</body>
</html>
