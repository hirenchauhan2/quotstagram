<template>
    <div class="card">
		<div class="card-block quote-header panel-heading">
			<a class="author-name" href="#/user/id">{{ author.name }}</a>
			<small><i>{{ quote.time_ago }}</i></small>
		</div>

		<div class="quote-body card-block">
			<p class="quote-text" :class="'font-'+quote.font_family">
				{{ quote.text }}
			</p>
		</div>
		<div class="quote-footer card-block">
			<div class="likes">
					<a v-if="likes" @click.prevent="unLike()" href="#">
						<i class="fa fa-lg fa-heart"></i>
					</a>
					<a v-else="!likes" @click.prevent="like()" href="#">
						<i class="fa fa-lg fa-heart-o"></i>
					</a>
					
					<span v-if="initialLikesCount === 0"></span>
					<span v-else-if="initialLikesCount === 1">1 Like</span> 
					<span v-else>{{ initialLikesCount }} Likes</span> 
			</div>
			&nbsp;&nbsp;
			<div class="comments">
				<i class="fa fa-lg fa-comment-o"></i>
			</div>
		</div>
	</div>
</template>

<script>
	export default {
		name: 'Quote',
		props: {
			quote: Object,
			likesCount: Number,
			userLikes: Boolean,
			author: Object
		},
		data() {
			return {
				initialLikesCount: this.likesCount,
				likes: !!this.userLikes,
			}
		},
		methods: {
			like() {
				const url = '/quotes/'+ this.quote.id +'/likes'
				axios
					.post(url, {})
					.then(res => {
						if(res.data.success) {
							this.initialLikesCount++;
							this.likes = true;
						}
					})
			},
			unLike() {

			}
		}
	}
</script>