
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="">
    <META NAME='ROBOTS' content='NOINDEX, NOFOLLOW'>
    <!-- CSRF Token -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <link rel="icon" type="image/png" sizes="16x16" href="{{url(asset('public/assets/images/logo.png'))}}">

    <title> 404 Error </title>
    <style>
        body{
            background-color: #680202;
            height: 100%;
            width: 100%;
        }
        #bg {
            /*position: fixed;*/
            height: 100%;

        }
        #bg img {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            margin: auto;

            height: 450px;
        }
    </style>
</head>

<body>
<div id="bg">
    <h1 style="text-align: center; color: red;">OOPS! Something went wrong</h1>
    <img src="{{url(asset('public/img/not_found.png'))}}">
</div>
</body>
</html>
