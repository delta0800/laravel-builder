<template>
  <div class="py-3 px-2 border-bottom">
    <div class="row">
      <div class="col-3 form-group mb-0 d-flex">
        <label class="col-form-label col-5 text-right px-1">
          Table key:
        </label>
        <div class="col-7">
          <select v-model="fields.table_key" class="form-control">
            <option
              v-for="fltTabFlt in filteredTableField"
              :key="fltTabFlt.index"
              :value="fltTabFlt.name"
            >
              {{ fltTabFlt.name }}
            </option>
          </select>
        </div>
      </div>
      <div class="col-4 form-group mb-0 d-flex">
        <div class="col-form-label col-5 text-right">
          Foreign Table:
        </div>
        <div class="col-7">
          <select v-model="fields.foreign_table" class="form-control">
            <option
              v-for="frnTab in foreignTable"
              :key="frnTab.index"
              :value="frnTab.id"
            >
              {{ frnTab.name }}
            </option>
          </select>
        </div>
      </div>
      <div class="col-4 form-group mb-0 d-flex">
        <div class="col-form-label col-5 text-right">
          Foreign Key:
        </div>
        <div class="col-7">
          <select v-model="fields.foreign_key" class="form-control">
            <option
              v-for="keyFld in foreignKeyField"
              :key="keyFld.index"
              :value="keyFld.name"
            >
              {{ keyFld.name }}
            </option>
          </select>
        </div>
      </div>
      <div class="col-1 text-center">
        <span
          class="btn btn-label-danger btn-pill btn-sm p-2"
          @click="$emit('remove', fields)"
          ><i class="fas fa-trash-alt"></i
        ></span>
        <d-input v-model="fields.id" type="text" class="d-none" />
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    field: {
      type: Object,
      default: null
    },
    form: {
      type: Object,
      default: null
    },
    index: {
      type: [Boolean, Number],
      default: false
    }
  },
  data() {
    return {
      fields: {},
      foreignKeyField: []
    }
  },
  computed: {
    tables() {
      return this.$store.state.tables
    },
    filteredTableField() {
      if (!this.form.fields) {
        return
      }
      return this.form.fields.filter(x => {
        return x.type === 'integer'
      })
    },
    foreignTable() {
      if (!this.form.table.name) {
        return
      }
      return this.tables.filter(x => {
        return x.name !== this.form.table.name
      })
    }
  },
  watch: {
    fields: {
      handler() {
        this.$emit('refresh', this.fields)
      },
      deep: true
    },
    'fields.foreign_table': {
      handler(value, old) {
        if (!old) {
          this.fields.foreign_table = this.field.foreign_table
          this.fields.foreign_key = this.field.foreign_key
        }
        const table = this.foreignTable.find(x => {
          return x.id === value
        })
        if (table) {
          this.foreignKeyField = table.table_fields.filter(x => {
            return x.type === 'integer'
          })
          this.fields.foreign_key = this.foreignKeyField[0].name
        }
        // console.log(this.fields.foreign_key)
      },
      deep: true
    }
  },
  mounted() {
    this.fields = this.field
    this.fields.table_key = this.field.table_key
      ? this.field.table_key
      : this.filteredTableField[0].name
  }
}
</script>
