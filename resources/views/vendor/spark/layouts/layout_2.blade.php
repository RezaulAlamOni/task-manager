<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Meta Information -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', config('app.name'))</title>

    <!-- Fonts -->
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:300,400,600' rel='stylesheet' type='text/css'>
    <script src="https://kit.fontawesome.com/1a9af935ca.js" crossorigin="anonymous"></script>
    <!-- CSS -->
    <link href="{{ mix(Spark::usesRightToLeftTheme() ? 'css/app-rtl.css' : 'css/app.css') }}" rel="stylesheet">
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="/css/bootstrap.css">
    <link href="/css/themify-icons.css" rel="stylesheet">
    <link href="/css/light_custom.css" rel="stylesheet">
    <link href="/css/login.css" rel="stylesheet">

{{--yield styles--}}
@yield('styles','')
<!-- Scripts -->
@yield('scripts', '')

<!-- Global Spark Object -->
    <script>
        window.Spark = <?php echo json_encode(array_merge(
            Spark::scriptVariables(), []
        )); ?>;
    </script>
</head>
<body class="skin-default login-form" style="-webkit-print-color-adjust:exact;">
<div id="spark-app" v-cloak>
    <!-- Navigation -->

{{--@if (Auth::check())--}}
    {{--@include('spark::nav.user')--}}
{{--@else--}}
    {{--@include('spark::nav.guest')--}}
{{--@endif--}}



<!-- Main Content -->
    <!-- Main Content -->
    <div class="wrapper row-offcanvas row-offcanvas-left">
        @if (Auth::check())
            <aside class="left-side sidebar-offcanvas">
                <!-- sidebar: style can be found in sidebar-->
            @include('spark::layouts.leftmenu')
            <!-- /.sidebar -->
            </aside>
            <aside class="right-side">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    @yield('breadcrumb')
                </section>
                <!-- Main content -->
                <!-- Main Content -->
                <main class="py-4">
                    @yield('content')
                </main>
                <!-- /.content -->
            </aside>
        @else
            <section class="content m-t-10rem">
                @yield('content')
            </section>
        @endif
    </div>


    <!-- Application Level Modals -->
    @if (Auth::check())
        @include('spark::modals.notifications')
        @include('spark::modals.support')
        @include('spark::modals.session-expired')
    @endif
</div>

<!-- JavaScript -->
<script src="{{ mix('js/app.js') }}"></script>
<script src="/js/sweetalert.min.js"></script>

<!-- Scripts -->
@yield('custom_scripts', '')

</body>
</html>
