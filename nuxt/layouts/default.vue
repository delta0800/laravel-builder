<template>
  <d-container fluid class="h-100">
    <d-row>
      <!-- Main Sidebar -->
      <MainSidebar />
      <d-col
        class="main-content offset-lg-2 offset-md-3 p-0 col-sm-12 col-md-9 col-lg-10"
        tag="main"
        lg="10"
        md="9"
        sm="12"
      >
        <!-- Main Navbar -->
        <MainNavbar :projects="projects" />
        <!-- Content -->
        <section class="main-content-container p-4 container-fluid">
          <nuxt />
        </section>
      </d-col>
    </d-row>
  </d-container>
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
