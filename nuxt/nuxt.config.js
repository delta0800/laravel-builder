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
      { rel: 'stylesheet', href: 'https://fonts.googleapis.com/icon?family=Material+Icons' },
      { rel: 'stylesheet', href: 'https://use.fontawesome.com/releases/v5.8.1/css/all.css' },
      { rel: 'stylesheet', href: 'http://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700%7CRoboto:300,400,500,600,700' },
    ],
    script: [
      { src: 'https://ajax.googleapis.com/ajax/libs/webfont/1.6.16/webfont.js' },
      { src: '/vendors/general/jquery/dist/jquery.js' },
      { src: '/vendors/general/popper.js/dist/umd/popper.js' },
      { src: '/vendors/general/bootstrap/dist/js/bootstrap.min.js' },
      { src: '/vendors/general/js-cookie/src/js.cookie.js' },
      { src: '/vendors/general/moment/min/moment.min.js' },
      { src: '/vendors/general/tooltip.js/dist/umd/tooltip.min.js' },
      { src: '/vendors/general/perfect-scrollbar/dist/perfect-scrollbar.js' },
		  { src: '/vendors/general/sticky-js/dist/sticky.min.js' },
      { src: '/vendors/general/wnumb/wNumb.js' },
      { src: '/vendors/general/jquery-form/dist/jquery.form.min.js' },
		  { src: '/vendors/general/block-ui/jquery.blockUI.js' },
		  { src: '/vendors/general/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js' },
		  { src: '/vendors/custom/components/vendors/bootstrap-datepicker/init.js' },
		  { src: '/vendors/general/bootstrap-datetime-picker/js/bootstrap-datetimepicker.min.js' },
		  { src: '/vendors/general/bootstrap-timepicker/js/bootstrap-timepicker.min.js' },
		  { src: '/vendors/custom/components/vendors/bootstrap-timepicker/init.js' },
		  { src: '/vendors/general/bootstrap-daterangepicker/daterangepicker.js' },
		  { src: '/vendors/general/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.js' },
		  { src: '/vendors/general/bootstrap-maxlength/src/bootstrap-maxlength.js' },
		  { src: '/vendors/custom/vendors/bootstrap-multiselectsplitter/bootstrap-multiselectsplitter.min.js' },
		  { src: '/vendors/general/bootstrap-select/dist/js/bootstrap-select.js' },
		  { src: '/vendors/general/bootstrap-switch/dist/js/bootstrap-switch.js' },
		  { src: '/vendors/custom/components/vendors/bootstrap-switch/init.js' },
		  { src: '/vendors/general/select2/dist/js/select2.full.js' },
		  { src: '/vendors/general/ion-rangeslider/js/ion.rangeSlider.js' },
		  { src: '/vendors/general/typeahead.js/dist/typeahead.bundle.js' },
		  { src: '/vendors/general/handlebars/dist/handlebars.js' },
		  { src: '/vendors/general/inputmask/dist/jquery.inputmask.bundle.js' },
		  { src: '/vendors/general/inputmask/dist/inputmask/inputmask.date.extensions.js' },
      { src: '/vendors/general/inputmask/dist/inputmask/inputmask.numeric.extensions.js' },      
      { src: '/vendors/general/nouislider/distribute/nouislider.js' },
      { src: '/vendors/general/owl.carousel/dist/owl.carousel.js' },
      { src: '/vendors/general/autosize/dist/autosize.js' },
      { src: '/vendors/general/clipboard/dist/clipboard.min.js' },
      { src: '/vendors/general/dropzone/dist/dropzone.js' },
      { src: '/vendors/general/summernote/dist/summernote.js' },
      { src: '/vendors/general/markdown/lib/markdown.js' },
      { src: '/vendors/general/bootstrap-markdown/js/bootstrap-markdown.js' },
      { src: '/vendors/custom/components/vendors/bootstrap-markdown/init.js' },
      { src: '/vendors/general/bootstrap-notify/bootstrap-notify.min.js' },
      { src: '/vendors/custom/components/vendors/bootstrap-notify/init.js' },
      { src: '/vendors/general/jquery-validation/dist/jquery.validate.js' },
      { src: '/vendors/general/jquery-validation/dist/additional-methods.js' },
      { src: '/vendors/custom/components/vendors/jquery-validation/init.js' },
      { src: '/vendors/general/toastr/build/toastr.min.js' },
      { src: '/vendors/general/raphael/raphael.js' },
      { src: '/vendors/general/morris.js/morris.js' },
      { src: '/vendors/general/chart.js/dist/Chart.bundle.js' },
      { src: '/vendors/custom/vendors/bootstrap-session-timeout/dist/bootstrap-session-timeout.min.js' },
      { src: '/vendors/custom/vendors/jquery-idletimer/idle-timer.min.js' },
      { src: '/vendors/general/waypoints/lib/jquery.waypoints.js' },
      { src: '/vendors/general/counterup/jquery.counterup.js' },
      { src: '/vendors/general/es6-promise-polyfill/promise.min.js' },
      { src: '/vendors/general/sweetalert2/dist/sweetalert2.min.js' },
      { src: '/vendors/custom/components/vendors/sweetalert2/init.js' },
      { src: '/vendors/general/jquery.repeater/src/lib.js' },
      { src: '/vendors/general/jquery.repeater/src/jquery.input.js' },
      { src: '/vendors/general/jquery.repeater/src/repeater.js' },
      { src: '/vendors/general/dompurify/dist/purify.js' },    
      { src: '/demo/default/base/scripts.bundle.js' },
      { src: '/app/custom/login/login-v1.js' },
      { src: '/app/custom/general/base/dropdown.js' },
      { src: '/app/custom/general/dashboard.js' }, 
      { src: '/app/bundle/app.bundle.js' },
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
    '~/assets/app/custom/login/login-v1.default.css',
    '~/assets/vendors/custom/fullcalendar/fullcalendar.bundle.css',
    '~/assets/vendors/general/perfect-scrollbar/css/perfect-scrollbar.css',
    '~/assets/vendors/general/tether/dist/css/tether.css',
    '~/assets/vendors/general/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css',
		'~/assets/vendors/general/bootstrap-datetime-picker/css/bootstrap-datetimepicker.css',
		'~/assets/vendors/general/bootstrap-timepicker/css/bootstrap-timepicker.css',
		'~/assets/vendors/general/bootstrap-daterangepicker/daterangepicker.css',
		'~/assets/vendors/general/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.css',
		'~/assets/vendors/general/bootstrap-select/dist/css/bootstrap-select.css',
		'~/assets/vendors/general/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.css',
		'~/assets/vendors/general/select2/dist/css/select2.css',
    '~/assets/vendors/general/ion-rangeslider/css/ion.rangeSlider.css',
    '~/assets/vendors/general/nouislider/distribute/nouislider.css',
		'~/assets/vendors/general/owl.carousel/dist/assets/owl.carousel.css',
		'~/assets/vendors/general/owl.carousel/dist/assets/owl.theme.default.css',
		'~/assets/vendors/general/dropzone/dist/dropzone.css',
		'~/assets/vendors/general/summernote/dist/summernote.css',
		'~/assets/vendors/general/bootstrap-markdown/css/bootstrap-markdown.min.css',
		'~/assets/vendors/general/animate.css/animate.css',
		'~/assets/vendors/general/toastr/build/toastr.css',
		'~/assets/vendors/general/morris.js/morris.css',
		'~/assets/vendors/general/sweetalert2/dist/sweetalert2.css',
		'~/assets/vendors/general/socicon/css/socicon.css',
		'~/assets/vendors/custom/vendors/line-awesome/css/line-awesome.css',
		'~/assets/vendors/custom/vendors/flaticon/flaticon.css',
		'~/assets/vendors/custom/vendors/flaticon2/flaticon.css',
    '~/assets/vendors/custom/vendors/fontawesome5/css/all.min.css',
    '~/assets/demo/default/base/style.bundle.css',
    '~/assets/demo/default/skins/header/base/light.css',
    '~/assets/demo/default/skins/header/menu/light.css',
    '~/assets/demo/default/skins/brand/dark.css',
    '~/assets/demo/default/skins/aside/dark.css',
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
    '~/plugins/bus.js',
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
