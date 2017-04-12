@extends('layouts.app')

@section('content')
	<div class="container">
		<div class="row">
			<div class="col-sm-12 text-center">
				<h1>Explore Public Content</h1>
			</div>
		</div>
		<div class="row">
			@each('components.quote', $quotes, 'quote')
		</div>
	</div>
@endsection