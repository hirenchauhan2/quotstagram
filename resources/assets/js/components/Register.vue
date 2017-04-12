<template>
  <div class="col col-md-6 registration">
    <div v-if="requestFinished" :class="{ 'alert-danger': requestFailed, 'alert-success': success }"  class="alert alert-dismissible fade show" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      <strong>{{ ( requestFailed ) ? 'Oops' : 'Success' }}</strong> {{ message }}
      <ul v-if="formErrors && formErrors.length">
        <li v-for="error in formErrors">{{ error }}</li>
      </ul>
    </div>
    <div class="card">
        <div class="panel-body">
          <h1 class="card-title text-center">Register</h1>
          <form @submit.prevent="register()" ref="form" role="form" method="POST" action="/register" autocomplete="false">
            <div class="form-group" :class="{ 'has-danger': (errors && errors.name) }">
              <label for="name" class="col form-control-label">Name</label>
              <div class="col">
                  <input 
                    id="name" type="text" class="form-control"
                    :class="{ 'form-control-danger': (errors && errors.name) }"
                    name="name"
                    v-model="name" 
                    @blur="checkValue('name', name)" 
                    required autofocus>
                  <span v-show="errors && errors.name" class="form-control-feedback">
                    <strong>{{ errors.name }}</strong>
                  </span>
              </div>
            </div>
            <div class="form-group" :class="{ 'has-danger': (errors && errors.email) }">
              <label for="email" class="col form-control-label">E-Mail Address</label>
              <div class="col">
                <input id="email" type="email" class="form-control" name="email" v-model="email" @blur="checkValue('email', email)"
                :class="{ 'form-control-danger': (errors && errors.email) }"
                required>
                <span v-show="errors && errors.email" class="form-control-feedback">
                  <strong>{{ errors.email }}</strong>
                </span>
              </div>
            </div>
            <div class="form-group" :class="{ 'has-danger': (errors && errors.username) }">
              <label for="username" class="col form-control-label">Username</label>
              <div class="col ">
                <input id="username" type="text" class="form-control" name="username" v-model="username" @blur="checkValue('username', username)"
                :class="{ 'form-control-danger': (errors && errors.username) }"
                 required>
                <span v-show="errors && errors.username" class="form-control-feedback">
                  <strong>{{ errors.username }}</strong>
                  </span>
              </div>
            </div>
            <div class="form-group" :class="{ 'has-danger': (errors && errors.password) }">
              <label for="password" class="col form-control-label">Password</label>

              <div class="col ">
                  <input
                    id="password"
                    type="password"
                    class="form-control"
                    name="password"
                    v-model="password"
                    @blur="checkValue('password', password)"
                    :class="{ 'form-control-danger': (errors && errors.password) }"
                    required>
                  <span v-show="errors && errors.password" class="form-control-feedback">
                      <strong>{{ errors.password }}</strong>
                  </span>
              </div>
          </div>
            <div class="form-group" :class="{ 'has-danger': (errors && errors.password_confirmation) }">
                <label for="password-confirm" class="col form-control-label">Confirm Password</label>

                <div class="col ">
                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" v-model="password_confirmation"
                    @blur="checkValue('password_confirmation', password_confirmation)"
                    :class="{ 'form-control-danger': (errors && errors.password_confirmation) }"
                    required>
                    <span v-show="errors && errors.password_confirmation" class="form-control-feedback">
                        <strong>{{ errors.password_confirmation }}</strong>
                    </span>
                </div>
            </div>
            <div class="form-group">
                <div class="col">
                    <button :disabled="!valid" type="submit" class="btn btn-primary">
                        Register
                    </button>
                </div>
            </div>
        </form>
      </div>
    </div>
  </div>
</template>
<style lang="scss" scoped>
  .registration {
    margin-bottom: 100px!important;
    z-index: 100;
  }
</style>
<script>
import isEmail from '../utils/isEmail'

export default {
  data() {
    return {
      name: '',
      email: '',
      username: '',
      password: '',
      password_confirmation: '',
      message: '',
      success: false,
      requestStarted: false,
      requestFinished: false,
      requestFailed: false,
      errors: {},
      formErrors: []
    }
  },
  computed: {
    valid() {
      const errorsKeys = _.keys(this.errors)
                          .filter(e => this.errors[e] !== null)
      return errorsKeys.length === 0
    },
    passwordMatches() {
      // only check if both fields have some value
      if (  this.password.trim().length
            && this.password_confirmation.trim().length ) {
        return this.password === this.password_confirmation
      }
      return false;
    }
  },
  methods: {
    cleanData() {    
      this.name= ''
      this.email= ''
      this.username= ''
      this.password= ''
      this.password_confirmation= ''
    },
    // validate perticular input
    checkValue(name, value) {
      value = value.trim()
      if (!value) {
          this.$set(this.errors, name, `Enter ${_.startCase(name)}`)
      } else {
        if (name === 'email' && !isEmail(value)) {
          this.$set(this.errors, name, 'Enter valid email')
        } else if (name === 'password_confirmation') {
          if (!this.passwordMatches) {
            this.$set(this.errors, name, 'Password does not match')
          } else {
            this.$set(this.errors, name, null)
          }
        } else {
          this.$set(this.errors, name, null)
        }
      }
    },

    register() {
      if (!this.valid) {
        console.log('NO SUBMIT')
        return;
      }
      // finally
      console.log('SUBMITING The Form...')
      this.requestStarted = true
      axios.post('/register', {
        name: this.name,
        email: this.email,
        username: this.username,
        password: this.password,
        password_confirmation: this.password_confirmation,
      }).then((response) => {
        this.requestStarted = false
        this.requestFinished = true
        const data = response.data
        if (data.success) {
          this.cleanData()
          this.success = true
          this.message = `Registration Success. Redirecting to: ${data.redirect}`
          // Redirect to Home after successful registration
          // User gets logged in also.
          setTimeout(() => window.location = data.redirect, 1000)
        }
      }).catch((err) => {
        this.requestStarted = false
        this.requestFinished = true
        this.requestFailed = true
        if (err.response) {
          const errData = err.response.data
          const errArray = _.keys(errData).map(key => _.first(errData[key]))
          this.formErrors = errArray
          this.message = 'Please check errors below:'
        } else {
          this.message = 'There was some error on server. Please try again.'
        }
        this.cleanData()
      })
    }
  }
}
</script>
