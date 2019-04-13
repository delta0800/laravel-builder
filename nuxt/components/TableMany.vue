<template>
  <div class="py-3 px-3 border-bottom">
    <div class="d-flex">
      <no-ssr>
        <div class="w-5">
          <d-badge
            pill
            theme="secondary"
            class="cursor-pointer"
            @click.native="$emit('remove', fields)"
          >
            <i class="fas fa-trash-alt"></i>
          </d-badge>
          <d-input v-model="fields.id" type="text" class="d-none" />
        </div>
        <d-col class="w-30 d-flex">
          <div class="col-5 text-right font-weight-lighter">
            Table Key:
          </div>
          <d-form-select
            v-model="fields.table_key"
            :options="filteredTableField"
            value-field="name"
            text-field="name"
            size="sm"
          />
        </d-col>
        <d-col class="w-30 d-flex">
          <div class="col-6 text-right font-weight-lighter">
            Foreign Table:
          </div>
          <d-form-select
            v-model="fields.foreign_table"
            :options="foreignTable"
            value-field="id"
            text-field="name"
            size="sm"
          />
        </d-col>
        <d-col class="w-30 d-flex">
          <div class="col-6 text-right font-weight-lighter">
            Foreign Key:
          </div>
          <d-form-select
            v-model="fields.foreign_key"
            :options="foreignKeyField"
            value-field="name"
            text-field="name"
            size="sm"
          />
        </d-col>
      </no-ssr>
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
        /* eslint-disable no-console */
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
