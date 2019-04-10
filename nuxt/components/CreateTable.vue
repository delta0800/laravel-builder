<template>
  <div>
    <no-ssr>
      <d-alert
        dismissible
        :show="timeUntilDismissed"
        theme="success"
        class="mb-2"
        @alert-dismissed="timeUntilDismissed = 0"
        @alert-dismiss-countdown="handleTimeChange"
      >
        <b v-text="alert" />
      </d-alert>
      <d-card>
        <d-form @submit="handleOnSubmit">
          <d-card-header class="bg-white py-4">
            <d-row class="form-group">
              <d-col class="col-2 text-right">
                Table Name:
              </d-col>
              <d-col class="col-4">
                <d-input
                  v-model="form.table.name"
                  v-validate="'required'"
                  :class="[{ 'has-error': errors.has('tablename') }]"
                  type="text"
                  name="tablename"
                />
                <span
                  v-if="errors.has('tablename')"
                  class="text-danger small"
                  v-text="errors.first('tablename')"
                />
              </d-col>
              <d-col class="offset-3 col-2">
                <d-button v-if="tableId" type="submit" size="sm">
                  <i class="fas fa-pencil-alt mr-1"></i>Edit
                </d-button>
              </d-col>
            </d-row>
            <d-row class="form-group mb-0">
              <d-col class="col-2 text-right">
                Sequence:
              </d-col>
              <d-col class="col-4">
                <d-input
                  v-model="form.table.sequence"
                  v-validate="'required'"
                  :class="[{ 'has-error': errors.has('sequence') }]"
                  type="text"
                  name="sequence"
                />
                <span
                  v-if="errors.has('sequence')"
                  class="text-danger small"
                  v-text="errors.first('sequence')"
                />
              </d-col>
              <d-col class="offset-3 col-2">
                <d-badge
                  v-if="tableId"
                  theme="primary"
                  class="p-2 cursor-pointer"
                  @click.native="deleteTable"
                >
                  <i class="fas fa-trash-alt mr-1"></i>Delete
                </d-badge>
                <d-button v-else size="sm" type="submit">
                  <i class="fas fa-plus mr-1"></i>Add
                </d-button>
              </d-col>
            </d-row>
          </d-card-header>
          <d-card-body class="p-0">
            <div class="d-flex bg-teallight align-items-end px-2">
              <div class="w-7 py-1 pl-3">#</div>
              <div class="w-15 p-1">NAME</div>
              <div class="w-15 p-1">TYPE</div>
              <div class="w-10 p-1">LENGTH</div>
              <div class="w-10 p-1">UNSIGNED</div>
              <div class="w-10 p-1">ALLOW NULL</div>
              <div class="w-15 p-1">KEY</div>
              <div class="w-10 p-1">DEFAULT</div>
              <div class="w-15 p-1">EXTRA</div>
            </div>
            <TableField
              v-for="(field, index) in form.fields"
              :key="field.id"
              :index="index"
              :field="field"
              @remove="remove"
              @refresh="refresh"
            ></TableField>
            <div class="d-flex p-3">
              <d-badge
                pill
                theme="light"
                class="cursor-pointer"
                @click.native="add"
              >
                <i class="fas fa-plus"></i>
              </d-badge>
            </div>
          </d-card-body>
          <d-card-footer class="bg-teallight">
            <d-row class="form-group mb-0">
              <d-col class="col-2 text-right font-weight-lighter">
                Use Timestamp:
              </d-col>
              <d-checkbox v-model="form.table.use_timestamp"></d-checkbox>
              <d-col class="col-2 font-weight-lighter text-right">
                Soft Delete
              </d-col>
              <d-checkbox v-model="form.table.soft_delete"></d-checkbox>
            </d-row>
          </d-card-footer>
        </d-form>
      </d-card>
    </no-ssr>
  </div>
</template>

<script>
import TableField from '~/components/TableField.vue'

export default {
  components: {
    TableField
  },
  data() {
    return {
      form: {
        table: {
          project_id: '',
          name: '',
          sequence: ''
        },
        fields: [],
        deletedId: []
      },
      tableId: null,
      selectedProject: '',
      duration: 3,
      timeUntilDismissed: 0,
      alert: ''
    }
  },
  watch: {
    '$route.params.id': {
      handler(value) {
        this.tableId = this.$route.params.id
        this.getTable()
      },
      deep: true
    },
    '$route.params.slug': {
      handler(value) {
        this.getSelectedProject()
        this.getTable()
      },
      deep: true
    }
  },
  mounted() {
    this.tableId = this.$route.params.id
    this.getTable()
    this.getSelectedProject()
  },
  methods: {
    getTable() {
      if (this.tableId) {
        this.$axios.$get(`/tables/${this.tableId}`).then(res => {
          this.form.table = res
          this.form.fields = res.table_fields
        })
      }
      if (!this.tableId) {
        this.form.table = {
          project_id: '',
          name: '',
          sequence: ''
        }
        this.form.fields = []
      }
    },
    getSelectedProject() {
      this.$axios.$get(`/project/${this.$route.params.slug}`).then(res => {
        this.selectedProject = res
      })
    },
    add() {
      this.form.fields.push({
        name: '',
        type: 'string',
        length: '255',
        unsigned: false,
        allow_null: false,
        key: '',
        default: '',
        extra: '',
        show_on: false,
        use_on_form: true,
        table: '',
        foreign_key: '',
        onDelete: '',
        onUpdate: ''
      })
    },
    remove(fields) {
      if (this.tableId) {
        this.form.deletedId.push(fields.id)
      }
      this.form.fields.splice(this.form.fields.indexOf(fields), 1)
    },
    handleOnSubmit(e) {
      e.preventDefault()
      this.form.table.project_id = this.selectedProject.id
      this.$validator.validateAll().then(x => {
        if (x) {
          if (this.tableId) {
            this.$axios
              .$put(`/tables/edit/${this.tableId}`, this.form)
              .then(res => {
                this.form.table = res
                this.form.fields = res.table_fields
                this.alert = 'Record has been Successfully updated!'
                this.timeUntilDismissed = this.duration
                this.$store.commit('updateTable', res)
              })
          } else {
            /* eslint-disable no-console */
            // console.log(this.form)
            this.$axios.$post('/tables', this.form).then(res => {
              this.alert = 'Record has been Successfully added!'
              this.timeUntilDismissed = this.duration
              this.$store.commit('appendTable', res)
              this.form.fields = []
              this.form.table.project_id = ''
              this.form.table.name = ''
              this.form.table.sequence = ''
            })
          }
        }
      })
    },
    deleteTable() {
      this.$axios.$delete(`/tables/delete/${this.tableId}`).then(res => {
        this.$router.replace({
          path: `/project/${this.$route.params.slug}/table/`
        })
      })
    },
    handleTimeChange(time) {
      this.timeUntilDismissed = time
    },
    refresh(payload) {
      /* eslint-disable no-console */
      if (payload.id) {
        const fieldIndex = this.form.fields.findIndex(x => x.id === payload.id)
        this.$set(this.form.fields, fieldIndex, payload)
      }
    }
  }
}
</script>

<style>
.cursor-pointer {
  cursor: pointer;
}
</style>
