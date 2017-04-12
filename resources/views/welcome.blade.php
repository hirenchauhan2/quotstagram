@extends('layouts.app')

@section('styles')
    <style>
        .full-height {
            height: 80vh;
        }

        .flex-center {
            align-items: center;
            display: flex;
            justify-content: center;
        }

        .content {
            text-align: center;
        }

        .m-b-md {
            margin-bottom: 20px;
        }
        .title-main {
          font-size: 10rem;
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
            <div class="actions">
                @if (Auth::guest())
                    <a href="{{ url('login') }}" class="btn-lg btn-primary">Login</a>
                @else
                    <a href="{{ url('explore') }}" class="btn-lg btn-primary">Explore</a>
                @endif                    
            </div>
        </div>
    </div>
@endsection