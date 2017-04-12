<template>
	<form @submit.prevent="submit()" action="/quotes" method="POST" role="form">
	    <h1>Create an Awesome Quote</h1>

		<div v-if="requestFinished" class="alert"
			:class="{ 'alert-danger': requestFailed, 'alert-success': requestSucced }">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
			<strong>{{ requestSucced ? 'Success': 'Oops' }}</strong> {{ message }}
		</div>

    	<div class="form-group" :class="{ 'has-danger': (errors && errors.text) }">
    		<label for="text">Quote</label>
				<textarea
					name="text"
					id="text"
					v-model="text"
					class="form-control form-control-lg"
					:class="{'form-control-danger': (errors && errors.text) }"
					rows="3" required
					></textarea>
				<span v-if="errors && errors.text" class="help-block">
					{{ errors.text }}
				</span>
    	</div>
    	<div class="form-group">
    		<label for="font_family">Font</label>
    		<select name="font_family" id="inputFont" class="form-control" v-model="font_family" required>
    			<option value="raleway" selected>Raleway</option>
    			<option value="arapey">Arapey</option>
    			<option value="spirax">Spirax</option>
    			<option value="philosopher">Philosopher</option>
    			<option value="poiret_one">Poiret One</option>
    			<option value="bad_script">Bad Script</option>
    			<option value="handlee">Handlee</option>
    		</select>
    	</div>
    	<div class="form-group">
 		   	<button type="submit" class="btn btn-primary">Submit</button>
    	</div>
			<div class="form-group">
				<strong>Preview</strong> <br>
				<p class="quote-preview" :class="'font-'+font_family">{{ text }}</p>
			</div>
    </form>
</template>
<style scoped lang="scss">
	textarea.form-control {
		font-size: 1.5rem;
	}
	.quote-preview {
		padding-left: 5px;
		font-size: 2rem;
	}
</style>
<script>
	export default {
		name: 'QuoteComposer',
		data() {
			return {
				font_family: 'raleway',
				text: '',
				message: '',
				errors: {},
				requestStarted: false,
				requestFinished: false,
				requestFailed: false,
				requestSucced: false
			}
		},
		methods: {
			clear() {
				this.text = ''
				this.font_family = 'raleway'
				this.errors = {}
			},
			submit() {
				// some checks
				if(!this.text.trim()) {
					this.$set(this.errors, 'text', 'Enter Quote Text')
					return
				}
				// ready to submit
				axios.post('/quotes', {
					text: this.text,
					font_family: this.font_family
				}).then((response) => {
					this.requestStarted = false
					this.requestFinished = true
					if (response.data.success) {
						this.requestSucced = true
						this.message = 'Quote posted successfully!'
						this.clear()
					} else {
						console.log('In sucess block but failed',response.data)
						this.requestSucced = false
						this.message = 'There was some error. Please try again.'
					}
				}).catch((error) => {
					console.log('Error in creating Quote', error.message)
					this.requestStarted = false
					this.requestFinished = true
					this.requestFailed = true
					this.message = error.response.data || error.message
				})
			}
		}
	}
</script>
