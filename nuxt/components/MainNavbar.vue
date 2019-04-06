<template>
  <div class="main-navbar sticky-top bg-white">
    <d-navbar type="light" class="align-items-center flex-md-nowrap p-0">
      <d-navbar-nav class="flex-row">
        <li class="nav-item dropdown ml-3">
          <no-ssr>
            <d-dropdown id="drop" :text="selectedProject" theme="light" is-nav>
              <div v-for="project in projects" :key="project.id">
                <d-dropdown-item @click.native="selectProject(project)">
                  <i class="fa fa-tasks-alt"></i>
                  {{ project.title }}
                </d-dropdown-item>
              </div>
            </d-dropdown>
          </no-ssr>
        </li>
      </d-navbar-nav>

      <d-navbar-nav class="flex-row ml-auto">
        <li class="nav-item dropdown mr-3">
          <a
            v-d-toggle.user-actions
            class="nav-link dropdown-toggle text-nowrap px-3"
          >
            <img
              src="/image/default-avatar.png"
              class="user-avatar rounded-circle mr-2"
              alt="user-avatar"
              width="30"
            />
            <span class="d-none d-md-inline-block">dhara</span>
          </a>

          <no-ssr>
            <d-collapse
              id="user-actions"
              class="dropdown-menu dropdown-menu-small"
            >
              <d-dropdown-item href="#">
                <i class="fa fa-user"></i> My Profile
              </d-dropdown-item>
              <d-dropdown-item
                href="#"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
              >
                <i class="material-icons text-danger">&#xE879;</i> Logout
              </d-dropdown-item>
              <form
                id="logout-form"
                action="/auth/login"
                method="POST"
                style="display: none;"
              >
                @csrf
              </form>
            </d-collapse>
          </no-ssr>
        </li>
      </d-navbar-nav>
    </d-navbar>
  </div>
</template>

<script>
export default {
  props: {
    projects: {
      type: Array,
      default: null
    }
  },
  data() {
    return {
      selectedProject: ''
    }
  },
  mounted() {
    this.selectedProject = this.$route.params.slug
      ? this.$route.params.slug
      : 'Projects'
  },
  methods: {
    selectProject(project) {
      this.selectedProject = project.title
      this.$router.replace({ path: `/project/${project.slug}/table/` })
    }
  }
}
</script>

<style>
.nav-link:hover {
  cursor: pointer;
}
</style>
