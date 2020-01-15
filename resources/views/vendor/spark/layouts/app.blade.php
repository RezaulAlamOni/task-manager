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
    <script src="https://kit.fontawesome.com/1a9af935ca.js" crossorigin="anonymous"></script>
{{--    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">--}}
    <!-- CSS -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('css/bootstrap.css')}}">
    <link href="{{asset('css/themify-icons.css')}}" rel="stylesheet">
    <link href="{{asset('css/light_custom.css')}}" rel="stylesheet">
    <link href="{{asset('css/tree_view.css')}}" rel="stylesheet">
    <link href="{{asset('css/rules.css')}}" rel="stylesheet">
    <link href="{{asset('css/_OverView.css')}}" rel="stylesheet">
    <link href="{{asset('css/board_view.css')}}" rel="stylesheet">
    <link href="{{asset('css/icon.css')}}" rel="stylesheet">
    <link href="{{asset('css/loder.css')}}" rel="stylesheet">
    <link href="{{asset('css/profile.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('css/all.css')}}">
    <link rel="stylesheet" href="{{asset('css/formelements.css')}}">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    {{-- <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script> --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css" rel="stylesheet">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.2.0/socket.io.slim.js"></script>

    <script src="https://www.gstatic.com/firebasejs/7.6.1/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/7.6.1/firebase-analytics.js"></script>
    <script src="https://www.gstatic.com/firebasejs/7.6.1/firebase-messaging.js"></script>




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
                <aside class="left-side sidebar-offcanvas left-hide">
                    <!-- sidebar: style can be found in sidebar-->
                @include('spark::layouts.leftmenu')
                <!-- /.sidebar -->
                </aside>
                <aside class="right-side right-align">
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
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js"></script>

{{--    <script src="{{asset('js/init-firebase.js')}}"></script>--}}
    <!-- Scripts -->
    @yield('custom_scripts', '')
    <script>
        var team_id = null;
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
            showProjectDashboard();
        })

        function showProjectDashboard() {
            {{--/project-dashboard/{{$project->id}} --}}
            $.ajax({
                type:'GET',
                url:'{{route('all-project')}}',
                data:
                    {
                        render : 'project',
                        _token: "{{ csrf_token() }}"
                    },
                success: function(data) {
                    $('#collapseProjects').html(data);
                }
            });
        }
        function SetTeam_id(trigger) {
            var trigger = $(trigger);
            var team = JSON.parse(trigger.attr('data-type'));
            window.team_id = team.id;
            $.ajax({
                type:'POST',
                url:'{{route('update-current-team')}}',
                data:
                    {
                        "_token": "{{ csrf_token() }}",
                        team_id : team.id,
                    },
                success: function(data) {
                    if (data.data === 1){
                        showProjectDashboard();
                        setTimeout(function () {
                            window.location.href = '/projects';
                        },100)
                    }
                }
            });
        }

        function getComment()
        {
            alert('dsfsdafjdsklf');
        }

    </script>

    <script>

        $(function () {
            {{--var firebaseConfig = {--}}
            {{--    apiKey: "AIzaSyAptOmWCzJ3Xrh0H--BKWYynTIEIJQbCvM",--}}
            {{--    authDomain: "compltit-2019.firebaseapp.com",--}}
            {{--    databaseURL: "https://compltit-2019.firebaseio.com",--}}
            {{--    projectId: "compltit-2019",--}}
            {{--    storageBucket: "compltit-2019.appspot.com",--}}
            {{--    messagingSenderId: "299665927099",--}}
            {{--    appId: "1:299665927099:web:5a8140fc36c771545bf29e",--}}
            {{--    measurementId: "G-24XSYB6Z38"--}}
            {{--};--}}
            {{--// Initialize Firebase--}}
            {{--firebase.initializeApp(firebaseConfig);--}}
            {{--navigator.serviceWorker.register("{{url('/firebase-messaging-sw.js')}}").then(function(){--}}
            {{--    const messaging = firebase.messaging();--}}
            {{--    messaging.requestPermission().then(function () {--}}
            {{--        return messaging.getToken();--}}
            {{--    }).then(function (token) {--}}
            {{--        InsertOrUpdateFcmToken(token);--}}
            {{--        // console.log(token)--}}
            {{--    }).catch(function (err) {--}}
            {{--        console.log(err);--}}
            {{--    });--}}
            {{--    messaging.onMessage(function(payload) {--}}
            {{--        console.log("Message received. ", payload);--}}
            {{--        //https://developer.mozilla.org/en-US/docs/Web/API/notification/Notification--}}
            {{--        navigator.serviceWorker.ready.then(function (registration) {--}}
            {{--            registration.showNotification(payload.notification.title, {--}}
            {{--                tag: payload.notification.tag,--}}
            {{--                body: payload.notification.body,--}}
            {{--                icon: payload.notification.icon,--}}
            {{--                image: payload.notification.image,--}}
            {{--                vibrate: [500, 110, 500, 110, 450, 110, 200, 110, 170, 40, 450, 110, 200, 110, 170, 40, 500],--}}
            {{--                sound: 'https://notificationsounds.com/soundfiles/dd458505749b2941217ddd59394240e8/file-sounds-1111-to-the-point.ogg'--}}
            {{--            });--}}
            {{--        });--}}
            {{--        console.log(payload);--}}
            {{--    });--}}
            {{--});--}}
        })
        function InsertOrUpdateFcmToken(token) {
            $.ajax({
                type:'POST',
                url:'{{route('FcmTokenInsertOrUpdate')}}',
                data:
                    {
                        "_token": "{{ csrf_token() }}",
                        user_id : {{auth()->id()}},
                        fcm_token : token,
                    },
                success: function(data) {

                }
            });
        }

    </script>
</body>
</html>
