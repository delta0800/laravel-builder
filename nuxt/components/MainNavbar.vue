<template>
  <div id="kt_header" class="kt-header kt-grid__item  kt-header--fixed ">
    <button
      id="kt_header_menu_mobile_close_btn"
      class="kt-header-menu-wrapper-close"
    >
      <i class="la la-close"></i>
    </button>
    <div id="kt_header_menu_wrapper" class="kt-header-menu-wrapper">
      <div
        id="kt_header_menu"
        class="kt-header-menu kt-header-menu-mobile  kt-header-menu--layout-default "
      >
        <ul class="kt-menu__nav ">
          <li
            class="kt-menu__item kt-menu__item--submenu kt-menu__item--rel"
            :class="classes"
            data-ktmenu-submenu-toggle="click"
            aria-haspopup="true"
          >
            <a href="javascript:;" class="kt-menu__link kt-menu__toggle"
              ><span class="kt-menu__link-text" @click="troggleMenu">{{
                selectedProject
              }}</span></a
            >
            <div
              class="kt-menu__submenu kt-menu__submenu--classic kt-menu__submenu--left"
            >
              <ul class="kt-menu__subnav">
                <li
                  v-for="(project, index) in projects"
                  :key="project.id"
                  :index="index"
                  class="kt-menu__item "
                  aria-haspopup="true"
                >
                  <a class="kt-menu__link" @click="selectProject(project)">
                    <span class="kt-menu__link-icon"> </span>
                    <span class="kt-menu__link-text">{{ project.title }}</span>
                  </a>
                </li>
              </ul>
            </div>
          </li>
        </ul>
      </div>
    </div>
    <div class="kt-header__topbar">
      <div class="kt-header__topbar-item kt-header__topbar-item--user">
        <div
          class="kt-header__topbar-wrapper"
          data-toggle="dropdown"
          data-offset="0px,0px"
        >
          <div class="kt-header__topbar-user py-4">
            <span class="kt-header__topbar-welcome kt-hidden-mobile">Hi,</span>
            <span class="kt-header__topbar-username kt-hidden-mobile"
              >Sean</span
            >
            <img class="kt-hidden" alt="Pic" src="/image/default-avatar.png" />
          </div>
        </div>
      </div>
      <div class="kt-header__topbar-item kt-header__topbar-item--user">
        <div
          class="kt-header__topbar-wrapper"
          data-toggle="dropdown"
          data-offset="0px,0px"
        >
          <div class="kt-header__topbar-user p-4">
            <span
              class="kt-header__topbar-username kt-hidden-mobile"
              @click="doLogout"
              >Logout</span
            >
          </div>
        </div>
      </div>
    </div>
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
      selectedProject: '',
      classes: ''
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
      this.classes = ''
    },
    troggleMenu() {
      this.classes =
        this.classes !== ''
          ? ''
          : 'kt-menu__item--active kt-menu__item--open-dropdown kt-menu__item--hover'
    },
    doLogout() {
      this.$auth.logout().then(res => {
        this.$router.replace({ path: '/auth/login' })
      })
    }
  }
}
</script>

<style>
.nav-link:hover {
  cursor: pointer;
}
</style>
