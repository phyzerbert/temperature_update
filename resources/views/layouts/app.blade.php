<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->

    <!-- Fonts -->
    <link href="{{asset('fonts/Nunito-Regular.ttf')}}">

    <!-- Styles -->
    {{-- <link href="{{ asset('css/app.css') }}" rel="stylesheet"> --}}
    <link href="{{ asset('css/toggle-bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/toggle-bootstrap-dark.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    @yield('styles')
    <script>
        window.auth_user = {!! auth()->user() !!};
    </script>
</head>
<body class="bootstrap">
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-themed shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                        @else
                            @if (Auth::user()->role != 'user')
                                <li class="nav-item">
                                    <a href="{{route('setting.index')}}" class="nav-link">Setting</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{route('user.index')}}" class="nav-link">User Management</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{route('log.index')}}" class="nav-link">Log</a>
                                </li>
                            @endif
                            <li class="nav-item">
                                <a href="{{route('home')}}" class="nav-link">Chart</a>
                            </li>
                            @if (Auth::user()->role != 'user')
                                <li class="nav-item">
                                    <a href="{{route('notification.index')}}" class="nav-link">
                                        <img src="{{asset('/images/notification.png')}}" width="25" alt="">
                                    </a>
                                </li>
                                {{-- <li class="nav-item dropdown">
                                    <a id="notificationDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                        <img src="{{asset('/images/notification.png')}}" width="25" alt="">
                                        <span class="badge badge-warning badge-pill" id="count_notification" style="display: none">0</span>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown" id="dropdown_notification">
                                        <a class="dropdown-item" href="{{route('notification.index')}}">View All Notifications</a>
                                    </div>
                                </li> --}}
                            @endif
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            <div class="change-theme">
                <button class="btn btn-sm btn-primary" id="btn_change_theme">Dark</button>
            </div>
            @yield('content')
        </main>
    </div>
    <script src="{{asset('plugins/moment/moment.min.js')}}"></script>
    <script src="{{asset('js/jquery.min.js')}}"></script>
        
    <script src="{{ asset('js/app.js') }}"></script>
    @yield('scripts')
    <script>
        $(document).ready(function () {
            setTheme();
            $("#btn_change_theme").click(function () {
               let theme = localStorage.getItem("theme"); 
               if(theme == 'light') {
                    localStorage.setItem("theme", "dark");
               } else {
                    localStorage.setItem("theme", "light");
               }
               setTheme();
            });

            function setTheme() {
                let theme = localStorage.getItem("theme"); 
                if(theme == 'dark') {
                    $("body").removeClass("bootstrap").addClass('bootstrap-dark');
                    $("#btn_change_theme").text('Light');
                } else {
                    $("body").removeClass("bootstrap-dark").addClass('bootstrap');
                    $("#btn_change_theme").text('Dark');
                }
            }
        })
    </script>
</body>
</html>
