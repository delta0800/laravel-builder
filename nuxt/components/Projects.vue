<template>
  <div>
    <CreateProject></CreateProject>
    <div class="row mx-auto">
      <div v-for="project in projects" :key="project.id" class="col-4">
        <div class="card mb-4">
          <div class="card-body">
            <div class="d-flex justify-content-between">
              <h5 class="cursor-pointer" @click="selectProject(project)">
                {{ project.title }}
              </h5>
              <span
                class="kt-badge kt-badge--unified-success kt-badge--md kt-badge--bold"
                >{{ project.tables.length }}</span
              >
            </div>
          </div>
          <div class="card-footer d-flex">
            <span
              class="kt-badge kt-badge--unified-danger kt-badge--md ml-auto"
              @click="deleteProject(project)"
            >
              <i class="fas fa-trash-alt fa-sm"></i>
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import CreateProject from '~/components/CreateProject.vue'

export default {
  components: {
    CreateProject
  },
  data() {
    return {
      title: '',
      selectedProject: ''
    }
  },
  computed: {
    projects() {
      return this.$store.state.projects
    }
  },
  methods: {
    getProjects() {
      this.$axios.$get('/projects').then(res => {
        this.$store.commit('setProject', res)
      })
    },
    deleteProject(project) {
      this.$axios.$get(`projects/${project.id}`).then(res => {
        this.getProjects()
      })
    },
    selectProject(project) {
      this.selectedProject = project.title
      this.$router.replace({ path: `/project/${project.slug}/table/` })
    }
  }
}
</script>

<style>
.cursor-pointer {
  cursor: pointer;
}
.kt-badge i {
  padding: 4px;
  line-height: 1rem;
  cursor: pointer;
}
</style>
