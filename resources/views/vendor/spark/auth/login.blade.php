<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="keywords" content="">

    <title>TheSaaS — Login</title>

    <!-- Styles -->
    <link href="../css/page.min.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">

    <!-- Favicons -->
    <link rel="apple-touch-icon" href="../img/apple-touch-icon.png">
    <link rel="icon" href="../img/favicon.ico">
</head>

<body class="layout-centered bg-img">


<!-- Main Content -->
<main class="main-content">

    <div class="bg-white rounded shadow-7 w-400 mw-100 p-6">
        <h5 class="mb-7">Login to Compltit</h5>
        @include('spark::shared.errors')
        <form role="form" method="POST" action="/login">
            {{ csrf_field() }}
            <div class="form-group">
                <input type="email" placeholder="Email" class="form-control" name="email" value="{{ old('email') }}" autofocus>
            </div>

            <div class="form-group">
                <input type="password" placeholder="Password" class="form-control" name="password">
            </div>

            <div class="form-group flexbox py-3">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" name="remember" checked>
                    <label class="custom-control-label">Remember me</label>
                </div>
                <a class="text-muted small-2" href="{{ url('/password/reset') }}">{{__('Forgot Your Password?')}}</a>
            </div>

            <div class="form-group">
                <button class="btn btn-block btn-primary" type="submit">Login</button>
            </div>
        </form>

        <!--<div class="divider">Or Login With</div>
        <div class="text-center">
            <a class="btn btn-circle btn-sm btn-facebook mr-2" href="#"><i class="fa fa-facebook"></i></a>
            <a class="btn btn-circle btn-sm btn-google mr-2" href="#"><i class="fa fa-google"></i></a>
            <a class="btn btn-circle btn-sm btn-twitter" href="#"><i class="fa fa-twitter"></i></a>
        </div>-->

        <hr class="w-30">

        <p class="text-center text-muted small-2">
            Don't have an account?
            <a href="{{ url('/register') }}">Register here</a>
        </p>
    </div>

</main><!-- /.main-content -->


<!-- Scripts -->
<script src="../js/page.min.js"></script>
<script src="../js/script.js"></script>

</body>
</html>
