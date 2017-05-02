<template>
	<form @submit.prevent="postComment()" method="post" class="form form-inline">
		<input type="text" name="text" v-model="text">
		<div v-if="requestFailed" class="help-block">
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
      text: '',
      requestStarted: false,
      requestFinished: false,
      requestFailed: false,
    };
  },
  methods: {
    postComment() {
      if (!this.text.trim()) {
        return;
      }
      const url = `/quotes/${this.quote}/comments`;
      axios
        .post(url, {
          text: this.text,
        })
        .then(resp => {
          if (resp.data.success) {
            this.text = '';
          }
          console.log(resp.data);
        })
        .catch(err => {
          this.message = err.response.data || err.message;
        });
    },
  },
};
</script>
