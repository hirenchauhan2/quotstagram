<template>
<div class="col col-md-6">
	<div v-if="reqFailed" class="alert alert-danger alert-dismissible fade show" role="alert">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
    </button>
		<h3 class="alert-heading">Oops!</h3>
    <p>{{ message }}</p>
	</div>
    <div class="card">
        <div class="card-block">
            <h1 class="card-title text-center">Login</h1>
            <form @submit.prevent="login()" role="form" method="POST" action="/login" ref="login">

                <div class="form-group"
                :class="{ 'has-danger': errors && errors.username }">
                    <label for="username" class="col control-label">Username</label>

                    <div class="col">
                        <input id="username" type="text" class="form-control" name="username" v-model="username" required autofocus>
                        <span v-if="errors && errors.username" class="help-block">
                            <strong>{{ errors.username }}</strong>
                        </span>
                    </div>
                </div>

                <div class="form-group"
                	:class="{ 'has-danger': errors && errors.password }">
                    <label for="password" class="col control-label">Password</label>

                    <div class="col">
                        <input id="password" type="password" class="form-control" name="password" v-model="password" required>
                        <span v-if="errors && errors.password" class="help-block">
                            <strong>{{ errors.password }}</strong>
                        </span>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col">
                        <div class="form-check">
                            <label class="form-check-label">
                                <input class="form-check-input" type="checkbox" name="remember" v-model="remember"> Remember Me
                            </label>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col">
                        <button type="submit" class="btn btn-primary">
                            Login
                        </button>

                        <!-- <a class="btn btn-link" href="/passwords/reset">
                            Forgot Your Password?
                        </a> -->
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
        resErrors: [],
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
            console.log(response)
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
            if (err.response && err.response.data) {
                const data = err.response.data
                this.message = Object.keys(data).map(k => {
                    return data[k]
                }).join('<br>')
    			console.log('Error', err)
            }
		})
  	}
  }
}
</script>
