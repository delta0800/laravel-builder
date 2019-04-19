<template>
  <div class="pt-3 pb-1">
    <div class="row px-2" role="tab">
      <input v-model="fields.id" type="text" class="d-none" />
      <div class="col-2">
        <input v-model="fields.name" type="text" class="form-control" />
      </div>
      <div class="col-2 form-group mb-0">
        <select v-model="fields.type" class="form-control">
          <option v-for="type in types" :key="type.index" :value="type.value">
            {{ type.text }}
          </option>
        </select>
      </div>
      <div class="col-1">
        <input v-model="fields.length" type="text" class="form-control" />
      </div>
      <div class="col-1">
        <input
          v-if="fields.type == 'integer'"
          v-model="fields.unsigned"
          class="form-control"
          type="checkbox"
        />
      </div>
      <div class="col-1">
        <input
          v-model="fields.allow_null"
          type="checkbox"
          class="form-control"
        />
      </div>
      <div class="col-1">
        <select v-model="fields.key" class="form-control">
          <option v-for="k in key" :key="k.index" :value="k.value">
            {{ k.text }}
          </option>
        </select>
      </div>
      <div class="col-1">
        <input v-model="fields.default" type="text" class="form-control" />
      </div>
      <div class="col-2">
        <input v-model="fields.extra" type="text" class="form-control" />
      </div>
      <div class="col-1 px-0">
        <div
          class="collapsed btn btn-label-primary btn-pill btn-sm p-2"
          @click="accrodian(index)"
        >
          <i class="fas fa-ellipsis-h"></i>
        </div>
        <span
          class="btn btn-label-danger btn-pill btn-sm p-2"
          @click="$emit('remove', fields)"
          ><i class="fas fa-trash-alt"></i
        ></span>
      </div>
    </div>

    <div v-if="show == true && index == selectedIndex">
      <div class="pb-2 border-bottom">
        <div class="row my-3 px-3">
          <div class="col-3">
            <label class="mb-0">Input Type</label>
            <select v-model="fields.inputType" class="form-control">
              <option
                v-for="type in inputTypes"
                :key="type.index"
                :value="type.value"
              >
                {{ type.text }}
              </option>
            </select>
          </div>
          <div v-show="fields.table && fields.key == 'foreign'" class="col-3">
            <label class="mb-0">Display Field</label>
            <select v-model="fields.display_field" class="form-control">
              <option
                v-for="tabFild in tableFields"
                :key="tabFild.index"
                :value="tabFild.name"
              >
                {{ tabFild.name }}
              </option>
            </select>
          </div>
          <div class="col-2">
            <label class="mb-0">Show On List</label>
            <input
              v-model="fields.show_on"
              type="checkbox"
              class="form-control"
            />
          </div>
          <div class="col-2">
            <label class="mb-0">Use On Form</label>
            <input
              v-model="fields.use_on_form"
              type="checkbox"
              class="form-control"
            />
          </div>
        </div>
        <div v-show="fields.key == 'foreign'" class="row mb-3 px-3">
          <div v-show="fields.key == 'foreign'" class="col-3">
            <label class="mb-0">Table</label>
            <select v-model="fields.table" class="form-control">
              <option
                v-for="fltTab in filteredTable"
                :key="fltTab.index"
                :value="fltTab.name"
              >
                {{ fltTab.name }}
              </option>
            </select>
          </div>
          <div v-if="fields.table && fields.key == 'foreign'" class="col-3">
            <label class="mb-0">Foreign key</label>
            <select
              v-model="fields.foreign_key"
              :options="filteredTableField"
              class="form-control"
            >
              <option
                v-for="fltTabFld in filteredTableField"
                :key="fltTabFld.index"
                :value="fltTabFld.name"
              >
                {{ fltTabFld.name }}
              </option>
            </select>
          </div>
          <div
            v-if="fields.foreign_key && fields.key == 'foreign'"
            class="col-3"
          >
            <label class="mb-0">OnDelete</label>
            <select v-model="fields.onDelete" class="form-control">
              <option v-for="act in action" :key="act.index" :value="act.value">
                {{ act.text }}
              </option>
            </select>
          </div>
          <div
            v-if="fields.foreign_key && fields.key == 'foreign'"
            class="col-3"
          >
            <label class="font-weight-lighter small mb-0">OnUpdate</label>
            <select v-model="fields.onUpdate" class="form-control">
              <option v-for="act in action" :key="act.index" :value="act.value">
                {{ act.text }}
              </option>
            </select>
          </div>
        </div>
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
      display_field: '',
      selectedIndex: '',
      show: false
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
        if (value !== 'foreign') {
          this.fields.display_field = ''
          this.fields.table = ''
          this.fields.foreign_key = ''
          this.fields.onDelete = ''
          this.fields.onUpdate = ''
        }
      },
      deep: true
    },
    'fields.allow_null': {
      handler(value, old) {
        if (!old) {
          this.fields.default = this.fields.default
        } else {
          this.fields.default = this.fields.allow_null ? 'NULL' : ''
        }
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
  },
  methods: {
    accrodian(index) {
      this.selectedIndex = index
      this.show = !this.show
    }
  }
}
</script>
