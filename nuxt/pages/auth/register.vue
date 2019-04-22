<template>
  <div class="kt-grid kt-grid--ver kt-grid--root h-100">
    <div
      id="kt_login"
      class="kt-grid kt-grid--hor kt-grid--root  kt-login kt-login--v1"
    >
      <div
        class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--desktop kt-grid--ver-desktop kt-grid--hor-tablet-and-mobile"
      >
        <div
          class="kt-grid__item kt-grid__item--order-tablet-and-mobile-2 kt-grid kt-grid--hor kt-login__aside"
          style="background-image: url(/media//bg/bg-4.jpg);"
        >
          <div class="kt-grid__item">
            <a href="#" class="kt-login__logo">
              <img src="/media/logos/logo-4.png" />
            </a>
          </div>
          <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--ver">
            <div class="kt-grid__item kt-grid__item--middle">
              <h3 class="kt-login__title">Welcome to Generetor!</h3>
              <h4 class="kt-login__subtitle">
                The ultimate Bootstrap theme framework for next generation web
                apps.
              </h4>
            </div>
          </div>
          <div class="kt-grid__item">
            <div class="kt-login__info">
              <div class="kt-login__copyright">
                2018 Metronic
              </div>
              <div class="kt-login__menu">
                <a href="#" class="kt-link">Privacy</a>
                <a href="#" class="kt-link">Contact</a>
              </div>
            </div>
          </div>
        </div>
        <div
          class="kt-grid__item kt-grid__item--fluid  kt-grid__item--order-tablet-and-mobile-1  kt-login__wrapper"
        >
          <div class="kt-login__head">
            <span class="kt-login__signup-label">Already have account?</span
            >&nbsp;&nbsp;
            <nuxt-link
              to="/auth/login"
              href="#"
              class="kt-link kt-login__signup-link"
              >Sign In!</nuxt-link
            >
          </div>
          <div class="kt-login__body">
            <div class="kt-login__form">
              <div class="kt-login__title">
                <h3>Sign Up!</h3>
              </div>
              <form class="kt-form" @submit.prevent="doRegister">
                <div class="form-group">
                  <input
                    id="name"
                    v-model="form.name"
                    name="name"
                    class="form-control"
                    placeholder="name"
                  />
                </div>
                <div class="form-group">
                  <input
                    id="username"
                    v-model="form.username"
                    name="username"
                    class="form-control"
                    placeholder="Username"
                  />
                </div>
                <div class="form-group">
                  <input
                    id="email"
                    v-model="form.email"
                    class="form-control"
                    type="text"
                    placeholder="Email"
                    name="email"
                  />
                </div>
                <div class="form-group">
                  <input
                    id="password"
                    v-model="form.password"
                    class="form-control"
                    type="password"
                    placeholder="Password"
                    name="password"
                  />
                </div>
                <div class="kt-login__actions">
                  <a href="#" class="kt-link kt-login__link-forgot">
                    Forgot Password ?
                  </a>
                  <button
                    id="kt_login_signin_submit"
                    type="submit"
                    class="btn btn-primary btn-elevate kt-login__btn-primary"
                  >
                    Sign Up
                  </button>
                </div>
              </form>
              <div class="kt-login__divider">
                <div class="kt-divider">
                  <span></span>
                  <span>OR</span>
                  <span></span>
                </div>
              </div>
              <div class="kt-login__options">
                <a href="#" class="btn btn-primary kt-btn">
                  <i class="fab fa-facebook-f"></i>
                  Facebook
                </a>
                <a href="#" class="btn btn-info kt-btn">
                  <i class="fab fa-twitter"></i>
                  Twitter
                </a>
                <a href="#" class="btn btn-danger kt-btn">
                  <i class="fab fa-google"></i>
                  Google
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
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
