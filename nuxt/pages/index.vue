<template>
  <div>
    <Projects v-if="slug == null" />
    <CreateTable v-else />
  </div>
</template>

<script>
import Projects from '~/components/Projects.vue'
import CreateTable from '~/components/CreateTable.vue'

export default {
  components: {
    Projects,
    CreateTable
  },
  data() {
    return {
      slug: null
    }
  },
  asyncData({ params, redirect, $axios, app }) {
    return { slug: params.slug }
  }
}
</script>
