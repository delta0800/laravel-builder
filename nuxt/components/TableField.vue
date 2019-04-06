<template>
  <div class="py-3 px-2 border-bottom">
    <div id="my-accordion" class="d-flex" role="tab">
      <no-ssr>
        <div class="w-7 p-1">
          <d-badge
            pill
            theme="light"
            class="cursor-pointer"
            @click.native="$emit('remove', fields)"
          >
            <i class="fas fa-trash-alt"></i>
          </d-badge>
          <d-input v-model="fields.id" type="text" class="d-none" />
          <d-badge v-d-toggle.collsId pill theme="light" class="cursor-pointer">
            <i class="fas fa-arrow-right"></i>
          </d-badge>
        </div>
        <div class="w-15 py-1 pr-1">
          <d-input
            v-model="fields.name"
            v-validate="'required'"
            :class="[{ 'has-error': errors.has('name' + index) }]"
            :name="'name' + index"
            type="text"
            size="sm"
          />
          <span
            v-if="errors.has('name' + index)"
            class="text-danger small"
            v-text="errors.first('name' + index)"
          />
        </div>
        <div class="w-15 p-1">
          <d-form-select v-model="fields.type" :options="types" size="sm" />
        </div>
        <div class="w-10 p-1">
          <d-input v-model="fields.length" type="text" size="sm" />
        </div>
        <div class="w-10 py-1 px-5">
          <d-checkbox
            v-if="fields.type == 'integer'"
            v-model="fields.unsigned"
          ></d-checkbox>
        </div>
        <div class="w-10 py-1 px-3">
          <d-checkbox v-model="fields.allow_null"></d-checkbox>
        </div>
        <div class="w-15 p-1">
          <d-form-select v-model="fields.key" :options="key" size="sm" />
        </div>
        <div class="w-10 p-1">
          <d-input v-model="fields.default" type="text" size="sm" />
        </div>
        <div class="w-15 p-1">
          <d-input v-model="fields.extra" type="text" size="sm" />
        </div>
      </no-ssr>
    </div>

    <d-collapse id="collsId" accordion="my-accordion" role="tabpanel">
      <div class="ml-6 mr-1 mt-2 pb-2 border rounded">
        <div class="d-flex">
          <d-col class="col-3">
            <span class="font-weight-lighter small mb-0">Input Type</span>
            <d-form-select
              v-model="fields.inputType"
              :options="inputTypes"
              size="sm"
            />
          </d-col>
          <d-col v-show="fields.table && fields.key == 'foreign'" class="col-3">
            <span class="font-weight-lighter small mb-0">Display Field</span>
            <d-form-select
              v-model="fields.display_field"
              :options="tableFields"
              value-field="name"
              text-field="name"
              size="sm"
            />
          </d-col>
          <d-col class="col-3">
            <span class="font-weight-lighter small mb-0">Show On List</span>
            <d-checkbox v-model="fields.show_on"></d-checkbox>
          </d-col>
          <d-col class="col-3">
            <span class="font-weight-lighter small mb-0">Use On Form</span>
            <d-checkbox v-model="fields.use_on_form"></d-checkbox>
          </d-col>
        </div>
        <div class="d-flex">
          <d-col v-show="fields.key == 'foreign'" class="col-3">
            <span class="font-weight-lighter small mb-0">Table</span>
            <d-form-select
              v-model="fields.table"
              :options="filteredTable"
              value-field="name"
              text-field="name"
              size="sm"
            />
          </d-col>
          <d-col v-if="fields.table && fields.key == 'foreign'" class="col-3">
            <span class="font-weight-lighter small mb-0">Foreign key</span>
            <d-form-select
              v-model="fields.foreign_key"
              :options="filteredTableField"
              value-field="name"
              text-field="name"
              size="sm"
            />
          </d-col>
          <d-col
            v-if="fields.foreign_key && fields.key == 'foreign'"
            class="col-3"
          >
            <span class="font-weight-lighter small mb-0">OnDelete</span>
            <d-form-select
              v-model="fields.onDelete"
              :options="action"
              size="sm"
            />
          </d-col>
          <d-col
            v-if="fields.foreign_key && fields.key == 'foreign'"
            class="col-3"
          >
            <span class="font-weight-lighter small mb-0">OnUpdate</span>
            <d-form-select
              v-model="fields.onUpdate"
              :options="action"
              size="sm"
            />
          </d-col>
        </div>
      </div>
    </d-collapse>
  </div>
</template>

<script>
export default {
  props: {
    field: {
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
      types: [
        { value: 'string', text: 'String' },
        { value: 'text', text: 'Text' },
        { value: 'date', text: 'Date' },
        { value: 'dateTime', text: 'DateTime' },
        { value: 'integer', text: 'Integer' },
        { value: 'decimal', text: 'Decimal' },
        { value: 'boolean', text: 'Boolean' }
      ],
      inputTypes: [
        { value: 'text', text: 'Text' },
        { value: 'textarea', text: 'Textarea' },
        { value: 'email', text: 'email' },
        { value: 'date', text: 'Date' },
        { value: 'dateTime', text: 'DateTime' },
        { value: 'number', text: 'Number' },
        { value: 'password', text: 'Password' },
        { value: 'checkbox', text: 'Checkbox' },
        { value: 'select', text: 'Select' },
        { value: 'rich-textbox', text: 'Rich Textbox' },
        { value: 'file', text: 'File' },
        { value: 'image', text: 'Image' }
      ],
      key: [
        { value: '', text: '' },
        { value: 'primary', text: 'PRIMARY' },
        { value: 'foreign', text: 'FOREIGN' }
      ],
      action: [{ value: 'cascade', text: 'cascade' }],
      tableFields: [],
      fields: {},
      inputType: '',
      display_field: ''
    }
  },
  computed: {
    filteredTable() {
      return this.tables.filter(x => {
        return x.id !== this.fields.table_id
      })
    },
    filteredTableField() {
      return this.tableFields.filter(x => {
        return x.type === 'integer'
      })
    },
    tables() {
      return this.$store.state.tables
    },
    collsId() {
      return this.fields.id
    }
  },
  watch: {
    fields: {
      handler() {
        this.$emit('refresh', this.fields)
      },
      deep: true
    },
    'fields.type': {
      handler(value, old) {
        if (!old) {
          this.fields.length = this.fields.length
          this.fields.inputType = this.fields.inputType
            ? this.fields.inputType
            : 'text'
        } else {
          this.fields.length = value === 'string' ? '255' : ''
          this.fields.inputType =
            this.fields.type === 'string'
              ? 'text'
              : this.fields.type === 'text'
              ? 'textarea'
              : this.fields.type === 'integer'
              ? 'number'
              : this.fields.type === 'boolean'
              ? 'checkbox'
              : this.fields.type
        }
      }
    },
    'fields.key': {
      handler(value, old) {
        /* eslint-disable no-console */
        // console.log(old)
        this.fields.extra = value === 'primary' ? 'auto_increment' : ''
      },
      deep: true
    },
    'fields.allow_null': {
      handler(value) {
        this.fields.default = this.fields.allow_null ? 'NULL' : ''
      },
      deep: true
    },
    'fields.table': {
      handler(value) {
        this.tables.forEach(table => {
          if (table.name === value) {
            this.tableFields = table.table_fields
          }
        })
      },
      deep: true
    },
    tables() {
      this.tables.forEach(table => {
        if (table.name === this.fields.table) {
          this.tableFields = table.table_fields
        }
      })
    }
  },
  mounted() {
    this.fields = this.field
    this.fields.display_field = this.fields.foreign_key
  }
}
</script>
