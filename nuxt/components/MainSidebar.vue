<template>
  <div
    id="kt_aside"
    class="kt-aside  kt-aside--fixed  kt-grid__item kt-grid kt-grid--desktop kt-grid--hor-desktop"
  >
    <!-- begin:: Aside -->
    <div
      id="kt_aside_brand"
      class="kt-aside__brand kt-grid__item "
      kt-hidden-height="65"
      style=""
    >
      <div class="kt-aside__brand-logo">
        <a href="/">
          <img alt="Logo" src="../assets/media/logo/logo-light.png" />
        </a>
      </div>
      <div class="kt-aside__brand-tools">
        <button id="kt_aside_toggler" class="kt-aside__brand-aside-toggler">
          <span></span>
        </button>
      </div>
    </div>

    <!-- end:: Aside -->

    <!-- begin:: Aside Menu -->
    <div
      id="kt_aside_menu_wrapper"
      class="kt-aside-menu-wrapper kt-grid__item kt-grid__item--fluid"
    >
      <div
        id="kt_aside_menu"
        class="kt-aside-menu kt-scroll ps ps--active-y"
        data-ktmenu-vertical="1"
        data-ktmenu-scroll="1"
        data-ktmenu-dropdown-timeout="500"
        style="height: 227px; overflow: hidden;"
      >
        <ul v-if="slug == null" class="kt-menu__nav ">
          <li class="kt-menu__item " aria-haspopup="true">
            <a href="/projects" class="kt-menu__link ">
              <span class="kt-menu__link-icon">
                <i class="fas fa-tasks"></i>
              </span>
              <span class="kt-menu__link-text">Projects</span>
            </a>
          </li>
        </ul>
        <ul v-else class="kt-menu__nav ">
          <li
            v-for="(table, index) in tables"
            :key="table.id"
            :index="index"
            class="kt-menu__item "
            aria-haspopup="true"
          >
            <nuxt-link
              :to="`/project/${slug}/table/${table.id}`"
              class="kt-menu__link"
            >
              <span class="kt-menu__link-icon">
                <i class="fas fa-table"></i>
              </span>
              <span class="kt-menu__link-text">{{ table.label }}</span>
            </nuxt-link>
          </li>
          <li class="kt-menu__item " aria-haspopup="true">
            <nuxt-link :to="`/project/${slug}/table/`" class="kt-menu__link ">
              <span class="kt-menu__link-icon">
                <i class="fas fa-plus-circle"></i>
              </span>
              <span class="kt-menu__link-text">Add Table</span>
            </nuxt-link>
          </li>
          <li
            v-show="tables.length"
            class="kt-menu__item "
            aria-haspopup="true"
          >
            <nuxt-link
              :to="`/project/${slug}/generator`"
              class="kt-menu__link "
            >
              <span class="kt-menu__link-icon">
                <i class="fas fa-cloud-download-alt"></i>
              </span>
              <span class="kt-menu__link-text">Generate</span>
            </nuxt-link>
          </li>
          <li
            v-show="tables.length"
            class="kt-menu__item "
            aria-haspopup="true"
          >
            <nuxt-link :to="`/project/${slug}/requests`" class="kt-menu__link ">
              <span class="kt-menu__link-icon">
                <i class="fas fa-download"></i>
              </span>
              <span class="kt-menu__link-text">Download Requests</span>
            </nuxt-link>
          </li>
        </ul>
      </div>
    </div>
    <!-- end:: Aside Menu -->
  </div>
</template>

<script>
export default {
  props: {
    selectedProjectId: {
      type: Number,
      default: null
    }
  },
  data() {
    return {
      sidebarVisible: false,
      slug: null
    }
  },
  computed: {
    tables() {
      return this.$store.state.tables
    },
    tableId() {
      const tabId = []
      this.tables.map(function(table) {
        tabId.push(table.id)
      })
      return tabId
    }
  },
  watch: {
    '$route.params.slug': {
      handler(value) {
        this.slug = value
        this.getTable()
      },
      deep: true
    }
  },
  mounted() {
    this.slug = this.$route.params.slug
    this.getTable()
  },
  methods: {
    handleToggleSidebar() {
      this.sidebarVisible = !this.sidebarVisible
    },
    getTable() {
      if (this.slug) {
        this.$axios.$get(`/project/${this.slug}/tables`).then(tables => {
          this.$store.commit('setTable', tables)
        })
      }
    }
  }
}
</script>

<style>
.item-icon-wrapper {
  display: inline-block;
}
.dropdown-menu {
  display: block;
}
</style>
