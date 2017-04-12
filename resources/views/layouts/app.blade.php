<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }} | Instagram for Quotes</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @yield('styles')
    <!-- Scripts -->
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
    </script>
</head>
<body>
    <div id="app">
        <div class="container">
            <nav class="navbar navbar-toggleable-sm navbar-light">
              <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
              </button>
              <a class="navbar-brand" href="{{ url('/') }}">{{ config('app.name', 'Quotestagram') }}</a>
              <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav ml-auto">
                    @if (Auth::guest())
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">Register</a>
                        </li>
                    @else
                        <li class="nav-item active">
                            <a class="nav-link" href="/quotes/create">Create Quote
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('explore') }}">Explore</a>
                        </li>
                        <li class="nav-item">
                            <a id="navbarDropdownMenuLink" href="#" class="nav-link">
                                {{ Auth::user()->name }}
                            </a>
                        </li>
                        <li class="nav-item">
                               <a href="{{ route('logout') }}"
                                    class="nav-link"
                                    onclick="event.preventDefault();
                                        document.getElementById('logout-form').submit();">
                                        Logout
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                        </li>
                    @endif            
                </ul>
              </div>
            </nav>
        </div>

        {{-- The Main Content Will be inserted here --}}
        @yield('content')
    </div>
    <footer class="footer">
        <div class="container">
            <nav class="navbar navbar-inverse fixed-bottom">
                <p class="text-center">
                    {{  config('app.name') }} &middot; 2017 &copy; Hiren Chuahan.
                </p>
            </nav>
        </div>
    </footer>
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    {{-- Extra script required by particular page will be inserted --}}
    @yield('scripts')
</body>
</html>
