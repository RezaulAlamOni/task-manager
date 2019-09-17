<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Meta Information -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name'))</title>

    <!-- Fonts -->
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:300,400,600' rel='stylesheet' type='text/css'>
    <link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css' rel='stylesheet' type='text/css'>

{{--    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">--}}
    <!-- CSS -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('css/bootstrap.css')}}">
    <link href="{{asset('css/themify-icons.css')}}" rel="stylesheet">
    <link href="{{asset('css/light_custom.css')}}" rel="stylesheet">
    <link href="{{asset('css/tree_view.css')}}" rel="stylesheet">
    <link href="{{asset('css/board_view.css')}}" rel="stylesheet">
    <link href="{{asset('css/icon.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('css/all.css')}}">
    <link rel="stylesheet" href="{{asset('css/formelements.css')}}">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">



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
<body class="skin-default" style="-webkit-print-color-adjust:exact;">
    <div id="spark-app" v-cloak>
        <!-- Navigation -->

        @if (Auth::check())
            @include('spark::nav.user')
        @else
            @include('spark::nav.guest')
        @endif



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
{{--                    <section class="content-header">--}}
                    <section>
                        @yield('breadcrumb')
                    </section>
                    <!-- Main content -->
                    <!-- Main Content -->
                    <main class="">
                        @yield('content')
                    </main>
                    <!-- /.content -->
                </aside>
            @else
                <section class="content m-t-75">
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
    <script src="{{asset('js/sweetalert.min.js')}}"></script>
    <script src="{{asset('js/icheck.js')}}"></script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
{{--    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>--}}

    <!-- Scripts -->
    @yield('custom_scripts', '')
    <script>
        $(document).ready(function () {
            $(".navbar .fa-bars").on("click",function () {

                $(".left-side.sidebar-offcanvas").toggleClass("left-hide");
                $(".right-side").toggleClass("right-align")

            });

            $("#menu .navigation a").click(function()
            {

                $("#menu .navigation li.active").removeClass("active");
                $(this).parent().addClass("active");
                $(this).parent().parent(".collapse.show").parent().addClass("active");
            });
        })


    </script>
</body>
</html>
