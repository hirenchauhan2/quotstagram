@extends('layouts.app')

@section('styles')
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
@endsection

@section('content')  
    <div class="flex-center position-ref full-height">
       <div class="content">
            <div class="title title-main m-b-md">
                {{ config('app.name') }}
            </div>
            <div class="sub">
              Its like Instagram but for Quotes!
            </div>
            <br><br>
            <div class="actions">
                <a href="{{ url('login') }}" class="btn-lg btn-primary">Login</a>
            </div>
        </div>
    </div>
@endsection