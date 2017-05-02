@extends ('layouts.app')

@section('content')
<div class="container explore">
	<div class="row">
		<div class="card-columns">
			@each('components.quote', $quotes, 'quote', 'components.empty')
		</div>
	</div>
</div>
@endsection

@section('scripts')
<script>
	function like(e, id) {
		e.preventDefault()
		const likeIcon = document.getElementById('like-icon-'+id)
		const likeCount = document.getElementById('likeCount-'+id)
		const url = '/quotes/'+ id +'/likes'
		axios
			.post(url, {})
			.then(res => {
				if(res.data.success) {
					const likesInner = likeCount.innerText.split(' ')[0]
					console.log('Likes Counter', likesInner)
					let likes = parseInt(likesInner, 10) || 0
					likes++
					likeCount.innerText = likes === 1 ? '1 Like' : likes + ' Likes'
					$(likeIcon).removeAttr('class')
					$(likeIcon).attr('class', 'fa fa-lg fa-heart')
				}
				if(res.data.deleted) {
					console.log('like deleted')
					const likesInner = likeCount.innerText.split(' ')[0]
					let likes = parseInt(likesInner, 10) || 0
					likes--
					likeCount.innerText = likes === 0 ? '' : likes === 1 ? '1 Like' : likes + ' Likes'
					$(likeIcon).removeAttr('class')
					$(likeIcon).attr('class','fa fa-lg fa-heart-o')
				}
			}).catch(err => {
				console.log(err)
			})
	}

	function unlike(e, id) {
		e.preventDefault()
		const likeIcon = document.getElementById('like-icon-'+id)
		const likeCount = document.getElementById('likeCount-'+id)
		const url = '/quotes/'+ id +'/likes'
		axios
			.delete(url, {})
			.then(res => {
				if(res.data.success) {
					const likesInner = likeCount.innerText.split(' ')[0]
					let likes = parseInt(likesInner, 10) || 0
					likes--
					likeCount.innerText = likes === 0 ? '' : likes === 1 ? '1 Like' : likes + ' Likes'
					likeIcon.classList.remove('fa-heart')
					likeIcon.classList.add('fa-heart-0')
				}
			})
	}

	function deleteQuote(id) {
		const url = '/quotes/'+ id
		console.log(url)
		axios
			.delete(url, {})
			.then(res => {
				if(res.data.success) {
					const quoteEle = document.getElementById('quote-'+id)
					quoteEle.parentNode.removeChild(quoteEle)
				}
			}).catch(err => console.log(err))
	}
</script>
@endsection

@section('styles')
<style>
	.container.explore {
		margin-bottom: 50px;
		margin-top: 80px;
	}
	.right {
		float: right;
	}
	.btn-quote-options {
		background-color: rgba(0, 0, 0, 0.2);
		color: #333;
		padding: 0!important;
		width: 10px!important;
		height: 30px!important;
	}
	.btn-rounded {
		border-radius: 50%;
	}
</style>
@endsection
