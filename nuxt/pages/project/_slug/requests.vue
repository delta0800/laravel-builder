<template>
  <div class="d-flex">
    <div class="w-75 mx-auto">
      <div class="card p-3 mb-2 bg-grey">
        <div class="row">
          <div class="col-4">
            <h5 class="mb-0">version</h5>
          </div>
          <div class="col-4">
            <h5 class="mb-0">created_at</h5>
          </div>
          <div class="col-4">
            <h5 class="mb-0">Action</h5>
          </div>
        </div>
      </div>
      <div
        v-for="request in downloadRequest"
        :key="request.id"
        class="card p-2 mb-2"
      >
        <div class="d-flex align-items-center">
          <div class="col-4">
            {{ request.version }}
          </div>
          <div class="col-4">
            {{ request.created_at }}
          </div>
          <div class="col-4">
            <span
              class="btn btn-label-primary btn-pill btn-sm p-1 mr-2"
              @click="download(request)"
              ><i class="fas fa-download"></i
            ></span>
            <span
              class="btn btn-label-primary btn-pill btn-sm p-1"
              @click="sendMail(request)"
              ><i class="fas fa-envelope-open-text"></i
            ></span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      slug: null,
      downloadRequest: []
    }
  },
  mounted() {
    this.slug = this.$route.params.slug
    this.getRequest()
  },
  methods: {
    getRequest() {
      if (this.slug) {
        this.$axios.$get(`/project/${this.slug}`).then(res => {
          if (res.download_request) {
            this.downloadRequest = res.download_request
          }
        })
      }
    },
    sendMail(request) {
      this.$axios.$get(`/sendMail/Request/${request.id}`).then(res => {
        alert('Mail Send Successfully')
      })
    },
    download(request) {
      this.$axios.$get(`/download/Request/${request.id}`).then(res => {
        window.open(res, '_blank')
      })
    }
  }
}
</script>

<style>
.bg-grey {
  background-color: rgb(230, 233, 235);
}
</style>
