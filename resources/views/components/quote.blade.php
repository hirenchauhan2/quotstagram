<div id="quote-{{$quote->id}}" class="card quote-card {{ $quote->id % 2 == 0 ? 'p-3' : ''}}">
	<div class="card-block quote-header panel-heading">
		<a class="author-name" href="#/user/id">{{ $quote->user->name }}</a>
		<small class="text-muted"><i>{{ $quote->created_at->diffForHumans()}}</i></small>
	</div>

	<div class="quote-body card-block">
		<p class="quote-text card-text font-{{ $quote->font_family }}">
			{{ $quote->text }}
		</p>
	</div>
	<div class="quote-footer card-footer card-block">
		<span class="likes">
			@if(Auth::user()->likesQuote($quote))
				<a onclick="unlike(event, {{$quote->id}})" href="#">
					<i id="like-icon-{{$quote->id}}" class="fa fa-lg fa-heart"></i>
				</a>
			@else
				<a onclick="like(event, {{$quote->id}})" href="#">
					<i id="like-icon-{{$quote->id}}" class="fa fa-lg fa-heart-o"></i>
				</a>
			@endif

			@if(count($quote->likes) === 0)
				<span id="likeCount-{{$quote->id}}"></span>
			@elseif(count($quote->likes) === 1)
				<span id="likeCount-{{$quote->id}}">1 Like</span>
			@else
				<span id="likeCount-{{$quote->id}}">{{ count($quote->likes) }} Likes</span>
			@endif
		</span>
		&nbsp;&nbsp;
	</div>
	{{-- @if (Auth::id() === $quote->user->id)
		<a href="#collapse" onclick="_.debounce(deleteQuote({{$quote->id}}), 300)">Remove Quote</a>
	@endif --}}
</div>
