<template>
  <aside
    :class="[
      'main-sidebar',
      'col-12',
      'col-md-3',
      'col-lg-2',
      'px-0',
      sidebarVisible ? 'open' : ''
    ]"
  >
    <div class="main-navbar">
      <d-navbar
        class="navbar align-items-stretch navbar-light bg-white flex-md-nowrap border-bottom p-0"
      >
        <a class="navbar-brand w-100 mr-0" href="/" style="line-height: 25px;">
          <div class="d-table m-auto">
            <img
              class="d-inline-block align-top mr-1"
              src="/image/shards-dashboards.svg"
              width="30"
            />
            <span class="d-none d-md-inline ml-1">Shards Dashboard</span>
          </div>
        </a>
        <a class="toggle-sidebar d-sm-inline d-md-none d-lg-none">
          <i class="material-icons">&#xE5C4;</i>
        </a>
      </d-navbar>
    </div>

    <div class="nav-wrapper">
      <d-nav v-if="slug == null" class="flex-column">
        <li class="nav-item">
          <d-link class="nav-link" to="/projects">
            <i class="fas fa-tasks"></i>
            <span>Projects</span>
          </d-link>
        </li>
      </d-nav>

      <d-nav v-else class="flex-column">
        <div v-if="tables">
          <li
            v-for="(table, index) in tables"
            :key="table.id"
            :index="index"
            class="nav-item"
          >
            <d-link class="nav-link" :to="`/project/${slug}/table/${table.id}`">
              <i class="fas fa-table"></i>
              <span>{{ table.name }}</span>
            </d-link>
          </li>
        </div>
        <li class="nav-item">
          <d-link class="nav-link" :to="`/project/${slug}/table/`">
            <i class="fas fa-tablet-alt"></i>
            <span>Add Table</span>
          </d-link>
        </li>
        <li class="nav-item">
          <d-link class="nav-link" :to="`/project/${slug}/generator`">
            <i class="fas fa-plus-circle"></i>
            <span>Generate</span>
          </d-link>
        </li>
      </d-nav>
    </div>
  </aside>
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
