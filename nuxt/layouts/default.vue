<template>
  <div
    class="h-100 kt-header--fixed kt-header-mobile--fixed kt-subheader--fixed kt-subheader--enabled kt-subheader--solid kt-aside--enabled kt-aside--fixed kt-page--loading"
  >
    <!-- Main Sidebar -->
    <MainSidebar />
    <!-- Main Navbar -->
    <div
      id="kt_wrapper"
      class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor kt-wrapper"
    >
      <MainNavbar :projects="projects" />
      <!-- Content -->
      <section
        id="kt_content"
        class="kt-content kt-grid__item kt-grid__item--fluid"
      >
        <nuxt />
      </section>
    </div>
  </div>
</template>

<script>
import MainNavbar from '~/components/MainNavbar.vue'
import MainSidebar from '~/components/MainSidebar.vue'

export default {
  // middleware: 'auth',
  components: {
    MainSidebar,
    MainNavbar
  },
  computed: {
    projects() {
      return this.$store.state.projects
    }
  },
  mounted() {
    this.getProjects()
  },
  methods: {
    getProjects() {
      this.$axios.$get('/projects').then(res => {
        this.$store.commit('setProject', res)
      })
    }
  }
}
</script>
