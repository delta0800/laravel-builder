<template>
  <div class="d-flex">
    <d-card class="w-50 mx-auto">
      <d-card-body class="px-5">
        <no-ssr>
          <d-form @submit="generator">
            <d-row class="mb-3">
              <d-col class="col-6">
                <h6>Table List</h6>
                <div v-for="table in tables" :key="table.id">
                  <d-row>
                    <d-checkbox v-model="form.tabId" :value="'' + table.id">
                    </d-checkbox>
                    <div class="font-weight-lighter text-right">
                      {{ table.name }}
                    </div>
                  </d-row>
                </div>
              </d-col>
              <d-col class="col-6">
                <h6>Package List</h6>
                <div v-for="packag in packages" :key="packag.id">
                  <d-row>
                    <d-checkbox
                      v-model="form.packageId"
                      :value="'' + packag.id"
                    >
                    </d-checkbox>
                    <div class="font-weight-lighter text-right">
                      <d-link :href="packag.link" target="_blank">
                        {{ packag.title }}
                      </d-link>
                    </div>
                  </d-row>
                </div>
              </d-col>
            </d-row>
            <d-row>
              <d-radio v-model="form.generat" inline class="mr-4" value="file">
                Only File
              </d-radio>
              <d-radio v-model="form.generat" inline value="project">
                Full Project
              </d-radio>
              <d-button type="submit" class="ml-auto" size="sm">
                Generat
              </d-button>
            </d-row>
          </d-form>
        </no-ssr>
      </d-card-body>
    </d-card>
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
      }
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
      this.packages.map(function(packag) {
        pId.push(packag.id)
      })
      return pId
    }
  },
  watch: {
    tables: {
      handler(value) {
        this.form.tabId = this.tableId
      }
    },
    // packages: {
    //   handler(value) {
    //     this.form.packageId = ''
    //   }
    // },
    '$route.params.slug': {
      handler(value) {
        this.form.slug = value
        this.getTable()
      },
      deep: true
    }
  },
  mounted() {
    this.form.slug = this.$route.params.slug
    this.getTable()
    this.getPackage()
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
    getPackage() {
      this.$axios.$get(`/packages`).then(res => {
        this.packages = res
        /* eslint-disable no-console */
        // console.log(this.packages)
      })
    },
    generator(e) {
      e.preventDefault()
      if (this.tableId) {
        this.$axios.$post('/generate/crud', this.form).then(tables => {
          alert('Successfully generate')
        })
      }
    }
  }
}
</script>
