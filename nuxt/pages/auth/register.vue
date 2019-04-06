<template>
  <div>
    <d-card class="ml-auto mr-auto" style="max-width: 400px">
      <d-card-header class="border-bottom">
        Join us for free
      </d-card-header>
      <d-card-body>
        <d-form @submit.prevent="doRegister">
          <label for="name">Name</label>
          <d-input
            id="name"
            v-model="form.name"
            name="name"
            class="mb-2"
            placeholder="name"
          />

          <label for="Username">Username</label>
          <d-input
            id="username"
            v-model="form.username"
            name="username"
            class="mb-2"
            placeholder="Username"
          />

          <label for="name">Email</label>
          <d-input
            id="email"
            v-model="form.email"
            name="email"
            class="mb-2"
            placeholder="email"
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

          <d-button type="submit" theme="primary">Register</d-button>
          <div class="mt-4">
            Already have account?
            <nuxt-link to="/auth/login">Login here</nuxt-link>
          </div>
        </d-form>
      </d-card-body>
    </d-card>
  </div>
</template>

<script>
export default {
  layout: 'auth',
  data() {
    return {
      form: {
        name: '',
        username: '',
        email: '',
        password: ''
      }
    }
  },
  methods: {
    doRegister() {
      //
    }
  }
}
</script>
