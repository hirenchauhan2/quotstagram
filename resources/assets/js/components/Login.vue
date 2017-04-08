<template>
<div class="col-md-8 col-md-offset-2">
	<div v-if="reqFailed" class="alert alert-danger">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
		<strong>Oops!</strong> {{ message }}
	</div>
    <div class="panel panel-default">
        <div class="panel-heading">Login</div>
        <div class="panel-body">
            <form @submit.prevent="login()" class="form-horizontal" role="form" method="POST" action="/login" ref="login">

                <div class="form-group"
                :class="{ 'has-error': errors && errors.username }">
                    <label for="username" class="col-md-4 control-label">Username</label>

                    <div class="col-md-6">
                        <input id="username" type="text" class="form-control" name="username" v-model="username" required autofocus>
                        <span v-if="errors && errors.username" class="help-block">
                            <strong>{{ errors.username }}</strong>
                        </span>
                    </div>
                </div>

                <div class="form-group"
                	:class="{ 'has-error': errors && errors.password }">
                    <label for="password" class="col-md-4 control-label">Password</label>

                    <div class="col-md-6">
                        <input id="password" type="password" class="form-control" name="password" v-model="password" required>
                        <span v-if="errors && errors.password" class="help-block">
                            <strong>{{ errors.password }}</strong>
                        </span>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-6 col-md-offset-4">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="remember" v-model="remember"> Remember Me
                            </label>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-8 col-md-offset-4">
                        <button type="submit" class="btn btn-primary">
                            Login
                        </button>

                        <a class="btn btn-link" href="/password/forgot">
                            Forgot Your Password?
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
</template>

<script>
export default {

  name: 'Login',

  data () {
    return {
		username: '',
		password: '',
		remember: false,
		reqStarted: false,
		reqFinished: false,
		reqFailed: false,
		message: '',
		errors: {}
    }
  },
  methods: {
  	reset() {
  		this.username = '',
  		this.password = '',
  		this.remember = false
  	},
  	login() {
		if (!this.username ) {
			this.$set(this.errors, 'username', 'Enter username')
			return
		}

		if (!this.password) {
			this.$set(this.errors, 'password', 'Enter password')
			return
		}

		console.log('SUBMIT')
		// start the ajax request
		this.reqStarted = true
		let credentials = {
			username: this.username,
			password: this.password
		}
		// If user want to get remembered
		if ( this.remember === true ) {
			credentials = _.assign({}, credentials, { remember: true })
		}
		axios.post('/login', credentials).then((response) => {
			// Request finished successfully
			this.reqFinished = true
			this.reqStarted = false
			const data = response.data
			if (data.success) {
				console.log('Login Success', data)
				setTimeout(() => window.location = data.redirect, 1000)
			} else {
				this.reqFailed = true
				this.message = data.message
			}
			// reset the form
			this.reset()
		}).catch((err) => {
			this.reqFinished =  true
			this.reqFailed = true
			this.reqStarted = false
			this.message = err.message
			console.log('Error', err)
		})
  	}
  }
}
</script>
