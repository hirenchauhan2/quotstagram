<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name') }} | Instagram for Quotes</title>

        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
        <!-- Styles -->
        <style>
            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            a, a:link, a:visited, a:active {
                text-decoration: none;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 14px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
            .title-main {
              font-size: 13rem;
              margin: 2rem 0;
            }

            .sub {
              text-align: center;
              font-size: 3rem;
              font-family: billabong, 'billabongregular';
            }

            .actions {
                margin-top: 5rem;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links">
                    @if (Auth::check())
                        <a href="{{ url('/home') }}">Home</a>
                    @else
                        <a href="{{ url('/login') }}">Login</a>
                        <a href="{{ url('/register') }}">Register</a>
                    @endif
                </div>
            @endif

            <div class="content">
                <div class="title title-main m-b-md">
                    {{ config('app.name') }}
                </div>
                <div class="sub">
                  Its like Instagram but for Quotes!
                </div>
                <div class="actions">
                    <a href="#" class="btn-lg btn-primary">Get Started</a>
                </div>
            </div>
        </div>
    </body>
</html>
