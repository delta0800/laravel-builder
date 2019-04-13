<template>
  <div>
    <CreateProject></CreateProject>
    <div class="d-flex flex-wrap mx-auto">
      <d-card v-for="project in projects" :key="project.id" class="w-30 m-3">
        <d-card-body>
          <span>{{ project.title }}</span>
        </d-card-body>
        <d-card-footer class="d-flex p-2">
          <d-button class="ml-auto" size="sm" @click="deleteProject(project)">
            <i class="fas fa-trash-alt mr-1"></i>Delete
          </d-button>
        </d-card-footer>
      </d-card>
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
    }
  }
}
</script>
