<template>
  <div class="col-md-8 col-md-offset-2">
    <div class="panel panel-default">
      <div class="panel-heading">Register</div>
        <div class="panel-body">
          <form @submit.prevent="register()" class="form-horizontal" ref="form" role="form" method="POST" action="/register">
            <div class="form-group" v-bind:class="{ 'has-error': (errors && errors.name) }">
              <label for="name" class="col-md-4 control-label">Name</label>
              <div class="col-md-6">
                  <input id="name" type="text" class="form-control" name="name" v-model="name" @blur="checkValue('name', name)" required >
                  <span v-show="errors && errors.name" class="help-block">
                    <strong>{{ errors.name }}</strong>
                  </span>
              </div>
            </div>
            <div class="form-group" v-bind:class="{ 'has-error': (errors && errors.email) }">
              <label for="email" class="col-md-4 control-label">E-Mail Address</label>
              <div class="col-md-6">
                <input id="email" type="email" class="form-control" name="email" v-model="email" @blur="checkValue('email', email)" required>
                <span v-show="errors && errors.email" class="help-block">
                  <strong>{{ errors.email }}</strong>
                </span>
              </div>
            </div>
            <div class="form-group" v-bind:class="{ 'has-error': (errors && errors.username) }">
              <label for="username" class="col-md-4 control-label">Username</label>
              <div class="col-md-6">
                <input id="username" type="text" class="form-control" name="username" v-model="username" @blur="checkValue('username', username)" required>
                <span v-show="errors && errors.username" class="help-block">
                  <strong>{{ errors.username }}</strong>
                  </span>
              </div>
            </div>
            <div class="form-group" v-bind:class="{ 'has-error': (errors && errors.password) }">
              <label for="password" class="col-md-4 control-label">Password</label>

              <div class="col-md-6">
                  <input
                    id="password"
                    type="password"
                    class="form-control"
                    name="password"
                    v-model="password"
                    @blur="checkValue('password', password)"
                    required>
                  <span v-show="errors && errors.password" class="help-block">
                      <strong>{{ errors.password }}</strong>
                  </span>
              </div>
          </div>
            <div class="form-group" v-bind:class="{ 'has-error': (errors && errors.password_confirmation) }">
                <label for="password-confirm" class="col-md-4 control-label">Confirm Password</label>

                <div class="col-md-6">
                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" v-model="password_confirmation"
                    @blur="checkValue('password_confirmation', password_confirmation)" required>
                    <span v-show="errors && errors.password_confirmation" class="help-block">
                        <strong>{{ errors.password_confirmation }}</strong>
                    </span>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-6 col-md-offset-4">
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
      errors: {}
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
      // validations
      if (!this.name.trim()) {
        this.$set(this.errors, 'name', 'Enter your name')
      }
      if (!this.email.trim()) {
        this.$set(this.errors, 'email', 'Enter your email')
      } else if (!isEmail(this.email.trim())) {
        this.$set(this.errors, 'email', 'Enter valid email')
      }

      if (!this.username.trim()) {
        this.$set(this.errors, 'username', 'Enter your username')
      }

      if (!this.password.trim()) {
        this.$set(this.errors, 'password', 'Enter your password')
      }

      if (!this.password_confirmation.trim()) {
        this.$set(this.errors, 'password_confirmation', 'Enter password confirmation')
      }
      else if (!this.passwordMatches) {
        this.$set(this.errors, 'password_confirmation', 'Passwords does not match')
      }

      console.log(`Is the form is valid: ${this.valid}`)
      if (!this.valid) {
        console.log('NO SUBMIT')
        return;
      }
      // finally
      console.log('SUBMIT')

    }
  }
}
</script>
