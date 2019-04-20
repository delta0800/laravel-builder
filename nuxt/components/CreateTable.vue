<template>
  <div>
    <form @submit="handleOnSubmit">
      <div class="card py-3">
        <div class="row mb-4">
          <div class="col-5 form-group mb-0 d-flex">
            <label class="col-form-label col-4 text-right px-1">
              Table Name:
            </label>
            <div class="col-8">
              <input
                v-model="form.table.name"
                v-validate="'required'"
                :class="[
                  'form-control',
                  { 'has-error': errors.has('tablename') }
                ]"
                type="text"
                name="tablename"
              />
              <span
                v-if="errors.has('tablename')"
                class="text-danger small"
                v-text="errors.first('tablename')"
              />
            </div>
          </div>
          <div class="col-5 form-group mb-0 d-flex">
            <label class="col-form-label col-3 text-right px-1">
              Menu Label:
            </label>
            <div class="col-8">
              <input
                v-model="form.table.label"
                :class="['form-control', { 'has-error': errors.has('label') }]"
                type="text"
                name="label"
              />
              <span
                v-if="errors.has('label')"
                class="text-danger small"
                v-text="errors.first('label')"
              />
            </div>
          </div>
          <div class="col-2 text-center">
            <button
              v-if="tableId"
              type="submit"
              class="btn btn-brand btn-elevate btn-pill btn-elevate-air btn-sm"
            >
              <i class="fas fa-pencil-alt mr-1"></i>Edit
            </button>
          </div>
        </div>
        <div class="row">
          <div class="col-5 form-group mb-0 d-flex">
            <label class="col-form-label col-4 text-right px-1">
              Menu Icon:
            </label>
            <div class="col-8">
              <input
                v-model="form.table.icon"
                :class="['form-control']"
                type="text"
                name="icon"
              />
            </div>
          </div>
          <div class="col-5 form-group mb-0 d-flex">
            <label class="col-form-label col-3 text-right px-1">
              Sequence:
            </label>
            <div class="col-8">
              <input
                v-model="form.table.sequence"
                v-validate="'required'"
                :class="[
                  'form-control',
                  { 'has-error': errors.has('sequence') }
                ]"
                type="text"
                name="sequence"
              />
              <span
                v-if="errors.has('sequence')"
                class="text-danger small"
                v-text="errors.first('sequence')"
              />
            </div>
          </div>
          <div class="col-2 text-center px-0">
            <button
              v-if="tableId"
              type="button"
              class="btn btn-brand btn-elevate btn-pill btn-elevate-air btn-sm"
              @click="deleteTable"
            >
              <i class="fas fa-trash-alt mr-1"></i>Delete
            </button>
            <button
              v-else
              type="submit"
              class="btn btn-brand btn-elevate btn-pill btn-elevate-air btn-sm"
            >
              <i class="fas fa-plus mr-1"></i>Add
            </button>
          </div>
        </div>
      </div>
      <div class="row p-3 font-weight-bold">
        <div class="col-2">Name</div>
        <div class="col-2">Type</div>
        <div class="col-1">Length</div>
        <div class="col-1">Unsigned</div>
        <div class="col-1">Null ?</div>
        <div class="col-2">Key</div>
        <div class="col-1">Auto Inc.</div>
        <div class="col-1">Default</div>
        <div class="col-1"></div>
      </div>
      <div>
        <TableField
          v-for="(field, index) in form.fields"
          :key="field.id"
          :index="index"
          :field="field"
          @remove="remove"
          @refresh="refresh"
        ></TableField>
        <div class="d-flex px-3 pb-2">
          <span
            class="btn btn-label-primary btn-pill btn-sm p-2 ml-auto"
            @click="add"
            ><i class="fas fa-plus"></i
          ></span>
        </div>
      </div>
      <div class="card py-2 mb-2">
        <div class="row mb-0">
          <div class="col-3 form-group mb-0 d-flex">
            <label class="col-form-label col-9 text-right px-1">
              Use Timestamp:
            </label>
            <div class="col-3">
              <input
                v-model="form.table.use_timestamp"
                type="checkbox"
                class="form-control"
              />
            </div>
          </div>
          <div class="col-2 form-group mb-0 d-flex">
            <label class="col-form-label col-8 text-right px-1">
              Soft Delete:
            </label>
            <div class="col-4">
              <input
                v-model="form.table.soft_delete"
                type="checkbox"
                class="form-control"
              />
            </div>
          </div>
          <div class="col-2 form-group mb-0 d-flex">
            <label class="col-form-label col-8 text-right px-1">
              Notificable:
            </label>
            <div class="col-4">
              <input
                v-model="form.table.notify"
                type="checkbox"
                class="form-control"
              />
            </div>
          </div>
          <div class="col-2 form-group mb-0 d-flex">
            <label class="col-form-label col-9 text-right px-1">
              Authenticable:
            </label>
            <div class="col-3">
              <input
                v-model="form.table.auth"
                type="checkbox"
                class="form-control"
              />
            </div>
          </div>
          <div class="col-3 form-group mb-0 d-flex">
            <label class="col-form-label col-8 text-right px-1">
              Use Many to Many:
            </label>
            <div class="col-4">
              <input
                v-model="form.use_many"
                type="checkbox"
                class="form-control"
                :disabled="fieldsLength < 1"
              />
            </div>
          </div>
        </div>
      </div>
      <div v-if="form.use_many && fieldsLength > 0" class="mb-2">
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
          <span
            class="btn btn-label-primary btn-pill btn-sm p-2 ml-auto"
            @click="addTableMany"
            ><i class="fas fa-plus"></i
          ></span>
        </div>
      </div>
    </form>
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
          sequence: '',
          use_timestamp: true
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
  computed: {
    fieldsLength() {
      return this.form.fields.length
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
    'form.table.name': {
      handler(value, old) {
        if (!old) {
          this.form.table.label = this.form.table.label
        } else if (value) {
          this.form.table.label = value
            .replace('_', ' ')
            .toLowerCase()
            .replace(/\b(\w)/g, s => s.toUpperCase())
        }
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
          sequence: '',
          use_timestamp: true
        }
        this.form.fields.push({
          name: '',
          type: 'string',
          length: '255',
          unsigned: false,
          allow_null: false,
          key: '',
          default: '',
          auto_increment: false,
          show_on: false,
          use_on_form: true,
          table: '',
          foreign_key: '',
          onDelete: '',
          onUpdate: ''
        })
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
        auto_increment: false,
        show_on: false,
        use_on_form: true,
        table: '',
        foreign_key: '',
        onDelete: '',
        onUpdate: ''
      })
    },
    remove(fields) {
      if (confirm('Do you really want to delete?')) {
        if (this.tableId) {
          this.form.deletedId.push(fields.id)
        }
        this.form.fields.splice(this.form.fields.indexOf(fields), 1)
      }
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
              this.form.table.label = ''
              this.form.table.icon = ''
              this.form.use_many = false
            })
          }
        }
      })
    },
    deleteTable() {
      if (confirm('Do you really want to delete?')) {
        this.$axios.$delete(`/tables/delete/${this.tableId}`).then(res => {
          this.$router.replace({
            path: `/project/${this.$route.params.slug}/table/`
          })
        })
      }
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
.btn i {
  padding: 4px;
  line-height: 1rem;
  cursor: pointer;
}
.border-bottom {
  border-bottom: 1px solid #e8eaee !important;
}
</style>
