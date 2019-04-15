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
            <d-row class="form-group">
              <d-col class="col-3 d-flex">
                <div class="col-9 text-right font-weight-lighter px-1">
                  Use Timestamp:
                </div>
                <d-checkbox v-model="form.table.use_timestamp"></d-checkbox>
              </d-col>
              <d-col class="col-2 d-flex">
                <div class="col-8 font-weight-lighter text-right px-1">
                  Soft Delete:
                </div>
                <d-checkbox v-model="form.table.soft_delete"></d-checkbox>
              </d-col>
              <d-col class="col-2 d-flex">
                <div class="col-8 font-weight-lighter text-right px-1">
                  Notificable:
                </div>
                <d-checkbox v-model="form.table.notify"></d-checkbox>
              </d-col>
              <d-col class="col-2 d-flex">
                <div class="col-10 font-weight-lighter text-right px-1">
                  Authenticable:
                </div>
                <d-checkbox v-model="form.table.auth"></d-checkbox>
              </d-col>
              <d-col class="col-3 d-flex">
                <div class="col-8 font-weight-lighter text-right px-1">
                  Use Many to Many:
                </div>
                <d-checkbox v-model="form.use_many"></d-checkbox>
              </d-col>
            </d-row>
            <div v-if="form.use_many" class="border">
              <TableMany
                v-for="(field, index) in form.tableMany"
                :key="field.id"
                :index="index"
                :field="field"
                :form="form"
                @remove="removeTableMany"
                @refresh="refreshTableMany"
              ></TableMany>
              <div class="d-flex p-3">
                <d-badge
                  pill
                  theme="secondary"
                  class="cursor-pointer"
                  @click.native="addTableMany"
                >
                  <i class="fas fa-plus"></i>
                </d-badge>
              </div>
            </div>
          </d-card-footer>
        </d-form>
      </d-card>
    </no-ssr>
  </div>
</template>

<script>
import TableField from '~/components/TableField.vue'
import TableMany from '~/components/TableMany.vue'

export default {
  components: {
    TableField,
    TableMany
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
        deletedId: [],
        tableMany: [],
        deletedManyId: [],
        use_many: false
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
    },
    'form.use_many': {
      handler(value, old) {
        if (value === true) {
          this.form.tableMany = this.form.tableMany
        }
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
          this.form.tableMany = res.table_many
          this.form.use_many = this.form.tableMany.length > 0
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
      const data = this.form
      if (this.form.table.use_timestamp === false) {
        delete data.table.use_timestamp
      }
      if (this.form.table.soft_delete === false) {
        delete data.table.soft_delete
      }
      if (this.form.table.auth === false) {
        delete data.table.auth
      }
      if (this.form.table.notify === false) {
        delete data.table.notify
      }

      this.$validator.validateAll().then(x => {
        if (x) {
          if (this.tableId) {
            this.$axios.$put(`/tables/edit/${this.tableId}`, data).then(res => {
              this.form.table = res
              this.form.fields = res.table_fields
              this.alert = 'Record has been Successfully updated!'
              this.timeUntilDismissed = this.duration
              this.$store.commit('updateTable', res)
            })
          } else {
            this.$axios.$post('/tables', data).then(res => {
              this.alert = 'Record has been Successfully added!'
              this.timeUntilDismissed = this.duration
              this.$store.commit('appendTable', res)
              this.form.fields = []
              this.form.table.project_id = ''
              this.form.table.name = ''
              this.form.table.sequence = ''
              this.form.use_many = false
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
      if (payload.id) {
        const fieldIndex = this.form.fields.findIndex(x => x.id === payload.id)
        this.$set(this.form.fields, fieldIndex, payload)
      }
    },
    addTableMany() {
      this.form.tableMany.push({
        table_key: '',
        foreign_table: '',
        foreign_key: ''
      })
    },
    removeTableMany(fields) {
      if (this.tableId) {
        this.form.deletedManyId.push(fields.id)
      }

      this.form.tableMany.splice(this.form.tableMany.indexOf(fields), 1)
    },
    refreshTableMany(payload) {
      if (payload.id) {
        const fieldIndex = this.form.tableMany.findIndex(
          x => x.id === payload.id
        )
        this.$set(this.form.tableMany, fieldIndex, payload)
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
