@extends('layouts.app')

@section ('content')
<div id="quote_create" class="container">
    <div class="row justify-content-center">
        <div class="col col-md-6 col-sm-10 col-md-offset-3">
            @if(session('message'))
	            <div class="alert alert-info">
	            	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
	            	{{ session('message') }}
	            </div>
            @endif
			      <quote-composer></quote-composer>
        </div>
    </div>
</div>
@endsection
