<template>
  <div>
    <d-card class="ml-auto mr-auto mt-5" style="max-width: 300px">
      <d-card-header class="border-bottom">
        Login into your account
      </d-card-header>
      <d-card-body>
        <d-form @submit.prevent="doLogin">
          <label for="Username">Email</label>
          <d-input
            id="email"
            v-model="form.email"
            name="email"
            class="mb-2"
            placeholder="Email"
          />

          <label for="Password">Password</label>
          <d-input
            id="Password"
            v-model="form.password"
            name="password"
            class="mb-2"
            type="password"
            placeholder="Password"
          />

          <d-button type="submit" theme="primary">Sign In</d-button>
          <div class="mt-4">
            Don't have account?
            <nuxt-link to="/auth/register">Register here</nuxt-link>
          </div>
        </d-form>
      </d-card-body>
    </d-card>
  </div>
</template>

<script>
export default {
  layout: 'auth',
  auth: false,
  data() {
    return {
      form: {
        email: '',
        password: ''
      }
    }
  },
  methods: {
    doLogin(e) {
      this.$auth
        .loginWith('local', {
          data: this.form
        })
        .then(() => {
          this.$router.replace({ path: '/projects' })
        })
    }
  }
}
</script>
