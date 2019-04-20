<template>
  <div class="d-flex">
    <div class="w-50 mx-auto">
      <form @submit="generator">
        <div class="card p-3 mb-2">
          <div class="row">
            <div class="col-6">
              <h5 class="mb-0">Table List</h5>
            </div>
            <div class="col-6">
              <h5 class="mb-0">Package List</h5>
            </div>
          </div>
        </div>
        <div class="card p-3 mb-2">
          <div class="row mb-3">
            <div class="col-6">
              <div class="form-group">
                <div
                  v-for="table in tables"
                  :key="table.id"
                  class="kt-checkbox-list"
                >
                  <label class="kt-checkbox">
                    <input
                      v-model="form.tabId"
                      type="checkbox"
                      :value="'' + table.id"
                    />
                    {{ table.name }}
                    <span></span>
                  </label>
                </div>
              </div>
            </div>
            <div class="col-6">
              <div class="form-group">
                <div
                  v-for="packag in packages"
                  :key="packag.id"
                  class="kt-checkbox-list"
                >
                  <label class="kt-checkbox kt-checkbox--brand">
                    <input
                      v-model="form.packageId"
                      type="checkbox"
                      :value="'' + packag.id"
                    />
                    <a :href="packag.link" target="_blank">
                      {{ packag.title }}
                    </a>
                    <span></span>
                  </label>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="card p-3">
          <div class="form-group d-flex align-items-center mb-0">
            <div class="kt-radio-inline">
              <label class="kt-radio">
                <input v-model="form.generat" type="radio" value="file" /> Only
                File
                <span></span>
              </label>
              <label class="kt-radio">
                <input v-model="form.generat" type="radio" value="project" />
                Full Project
                <span></span>
              </label>
            </div>
            <button
              type="submit"
              class="btn btn-brand btn-elevate btn-pill btn-elevate-air btn-sm ml-auto"
              size="sm"
              :disabled="isProcessing"
            >
              Generate
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      tables: [],
      packages: [],
      form: {
        tabId: [],
        packageId: [],
        generat: 'project',
        slug: null
      },
      isProcessing: false,
      selectedPackage: []
    }
  },
  computed: {
    tableId() {
      const tId = []
      this.tables.map(function(table) {
        tId.push(table.id)
      })
      return tId
    },
    packId() {
      const pId = []
      if (this.selectedPackage) {
        this.selectedPackage.map(function(packag) {
          pId.push(packag.id)
        })
      }
      return pId
    }
  },
  watch: {
    tables: {
      handler(value) {
        this.form.tabId = this.tableId
      }
    },
    selectedPackage: {
      handler(value) {
        this.form.packageId = this.packId
      }
    },
    '$route.params.slug': {
      handler(value) {
        this.form.slug = value
        this.getTable()
      },
      deep: true
    },
    'form.generat': {
      handler(value) {
        if (value === 'file') {
          this.form.packageId = []
        }
      }
    }
  },
  mounted() {
    this.form.slug = this.$route.params.slug
    this.getTable()
    this.getPackage()
    this.getProject()
    this.form.tabId = this.tableId
    this.form.packageId = this.packId
  },
  methods: {
    getTable() {
      if (this.form.slug) {
        this.$axios.$get(`/project/${this.form.slug}/tables`).then(tables => {
          this.tables = tables
        })
      }
    },
    getProject() {
      if (this.form.slug) {
        this.$axios.$get(`/project/${this.form.slug}`).then(res => {
          if (res.packages) {
            this.selectedPackage = res.packages
          }
          /* eslint-disable no-console */
          // console.log(this.selectedPackage)
        })
      }
    },
    getPackage() {
      this.$axios.$get(`/packages`).then(res => {
        this.packages = res
      })
    },
    generator(e) {
      e.preventDefault()
      this.isProcessing = true
      if (this.tableId) {
        this.$axios.$post('/generate/crud', this.form).then(tables => {
          alert('Successfully generate')
          this.isProcessing = false
        })
      }
    }
  }
}
</script>
