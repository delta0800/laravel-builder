import pkg from './package'
require('dotenv').config()

export default {
  mode: 'universal',

  /*
   ** Headers of the page
   */
  head: {
    title: pkg.name,
    meta: [
      { charset: 'utf-8' },
      { name: 'viewport', content: 'width=device-width, initial-scale=1' },
      { hid: 'description', name: 'description', content: pkg.description }
    ],
    link: [
      { rel: 'icon', type: 'image/x-icon', href: '/favicon.ico' },
      { rel: 'stylesheet', href: 'https://use.fontawesome.com/releases/v5.0.6/css/all.css' },
      { rel: 'stylesheet', href: 'https://fonts.googleapis.com/icon?family=Material+Icons' }
    ]
  },

  /*
   ** Customize the progress-bar color
   */
  loading: { color: '#fff' },

  /*
   ** Global CSS
   */
  css: [
    '~/assets/scss/shards-dashboards.scss'
  ],

  // To use in client side
  env: {
    API_URL: process.env.API_URL,
  },

  /*
   ** Plugins to load before mounting the App
   */
  plugins: [
    '~/plugins/errorsValidator',
    '~/plugins/client.js',
    '~/plugins/bus.js'
  ],

  /*
   ** Nuxt.js modules
   */
  modules: [
    // Doc: https://axios.nuxtjs.org/usage
    '@nuxtjs/axios',
    '@nuxtjs/auth'
  ],
  /*
   ** Axios module configuration
   */
  axios: {
    // See https://github.com/nuxt-community/axios-module#options
  },

  /*
  ** Auth module configuration
  */
 auth: {
  strategies: {
    local: {
      endpoints: {
        login: { url: '/auth/login', method: 'post', propertyName: 'token' },
        logout: { url: '/auth/logout', method: 'post' },
        user: { url: '/auth/user', method: 'get', propertyName: 'data' }
      }
    }
  },
  redirect: {
    login: '/auth/login',
    logout: '/auth/login',
    callback: '/auth/login',
    home: '/'
  }
},


  /*
   ** Build configuration
   */
  build: {
    /*
     ** You can extend webpack config here
     */
    extend(config, ctx) {
      // Run ESLint on save
      if (ctx.isDev && ctx.isClient) {
        config.module.rules.push({
          enforce: 'pre',
          test: /\.(js|vue)$/,
          loader: 'eslint-loader',
          exclude: /(node_modules)/
        })
      }
    }
  }
}
