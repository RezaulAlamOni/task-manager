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

    <!-- Scripts -->
    @yield('scripts', '')

    <!-- Global Spark Object -->
    <script>
        window.Spark = @json(array_merge(Spark::scriptVariables(), []));
    </script>
</head>
<body class="with-navbar">
    <div>
        <!-- Navigation -->
        @if (Auth::check())
            @include('spark::nav.blade.user')
        @else
            @include('spark::nav.guest')
        @endif

        <!-- Main Content -->
        <main class="py-4">
            @yield('content')
        </main>

        <!-- JavaScript -->
        <script src="{{ mix('js/app.js') }}"></script>
    </div>


</body>
</html>
