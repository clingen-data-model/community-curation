<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>


    <!-- favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest" rel="preload">
    <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="theme-color" content="#ffffff">

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link
        rel="preload stylesheet"
        href="https://fonts.googleapis.com/css?family=Nunito&display=swap"
        as="style"
        onload="this.rel = 'stylesheet'"
    >
    <link
        rel="preload stylesheet"
        href="https://fonts.googleapis.com/icon?family=Material+Icons&display=swap"
        as="style"
        onload="this.rel = 'stylesheet'"
    >


    <!-- Styles -->
    <link
        rel="preload stylesheet"
        href="{{ mix('css/app.css') }}"
        as="style"
        onload="this.rel = 'stylesheet'"
    >

    <noscript>
        {{-- <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito&display=swap"> --}}
        <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons&display=swap">
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    </noscript>

    @stack('styles')
</head>
<body>
    <div id="app" :class="{loading: loading}">
        @if(config('app.env') == 'demo')
        <demo-warning></demo-warning>
        @endif

        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
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
                        @if (!Auth::guest() && Auth::user()->hasAnyRole(['admin', 'super-admin', 'programmer']))
                            <li><a href="/volunteers" class="nav-link">Volunteers</a></li>
                            <li><a href="/trainings" class="nav-link">Trainings</a></li>
                            <li><a href="/curation-activities" class="nav-link">Activities</a></li>
                            <li><a href="/curation-groups" class="nav-link">Groups</a></li>
                            <li><a href="/reports" class="nav-link">Reports</a></li>
                            @if(Auth::user()->hasRole('volunteer'))
                                <li style="padding-left: .5rem; border-left: solid 1px #efefef; margin-left: .5rem;">
                                    <a href="/volunteers/{{Auth::user()->id}}" class="nav-link">Your Profile</a>
                                </li>
                            @endif
                        @endif
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a href="/faq" class="dropdown-item" target="faq">FAQ</a>


                                    @if(\Auth::user()->hasPermissionTo('administer'))
                                        <a class="dropdown-item" href="/admin" target="admin">
                                            Admin
                                        </a>
                                    @endif

                                    @if(\Auth::user()->hasPermissionTo('view logs'))
                                        <a class="dropdown-item" href="/admin/logs" target="logs">Logs</a>
                                    @endif

                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="
                                            event.preventDefault();
                                            document.getElementById('logout-form').submit();
                                            window.clearSessionStorage();
                                        "
                                    >
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                        <li class="nav-item" style="margin-right: -46px">
                            <help-button></help-button>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        @include('partials.no-mail')
        <global-progress></global-progress>

        @include('partials.messages')
        <main class="py-4 container">
            @yield('content')
        </main>

        <alerts></alerts>

        @include('partials.impersonate')

        <footer class="bg-white pt-4 mt-3 pb-2 border-top w-100">
            <div class="container">
                <div class="d-flex justify-content-between">
                    <div>
                        © 2020 <a href="https://clinicalgenome.org" taret="clin-gen">ClinGen</a>
                    </div>
                    <ul class="list-inline">
                        <li class="list-inline-item"><a href="/faq" target="faq">FAQ</a></li>
                        <li class="list-inline-item"><a href="https://clinicalgenome.org/working-groups/clingen-community-curation-c3/" target="clinicalgenome">About Community Curation</a></li>
                    </ul>
                </div>
                @if(\Auth::user() && (\Auth::user()->hasRole('programmer') || \Auth::user()->isImpersonated() && \Auth::user()->impersonatedBy->hasRole('programmer')))
                <div class="mt-3 border-top pt-3 d-flex flex-row-reverse">
                    <button class="btn btn-xs btn-light border" v-on:click="refreshUser">
                        <b-icon icon="arrow-clockwise"></b-icon>
                        Refresh session user
                    </button>
                </div>
                @endif
            </div>
        </footer>
    </div>

    <!-- Scripts -->
    <script src="{{ mix('/js/app.js') }}" defer></script>

</body>
</html>
