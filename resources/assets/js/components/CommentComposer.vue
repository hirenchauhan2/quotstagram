<template>
	<form @submit.prevent="postComment()" :action="'/quotes/'+quote+'/comments'" method="post" class="form form-inline">
		<input type="text" name="comment" v-model="comment">
		<div v-if="err" class="help-block">
			{{ message }}
		</div>
	</form>
</template>
<script>
export default {
	props: ['quote'],
	data() {
		return {
			message: '',
			comment: '',
			requestStarted: false,
			requestFinished: false,
			requestFailed: false,
		}
	},
	methdos: {
		postComment() {
			if (!this.comment.trim()) {
				return;
			}
			const url= '/quotes/'+this.quote+'/comment'
			axios.post(url, {
				comment: this.comment
			}).then(resp => {
				if(resp.data.success) {
					this.comment = ''
				} 
			}).catch(err=> {
				this.message = err.response.data || err.message
			})
		}
	}
}
</script>