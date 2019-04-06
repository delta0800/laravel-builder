<template>
  <div>
    <div class="d-flex">
      <d-btn class="ml-auto mr-4" @click.native="handleClick">
        <i class="fas fa-plus mr-1"></i>Add New
      </d-btn>
    </div>
    <d-modal v-if="showModal" @close="handleClose">
      <d-modal-header>
        <d-modal-title>Add New Project</d-modal-title>
      </d-modal-header>
      <d-modal-body>
        <d-form @submit="handleOnSubmit">
          <d-row class="form-group">
            <label for="title col-2">Title</label>
            <d-col class="col-10 ml-sm-2">
              <d-input
                v-model="form.title"
                v-validate="'required'"
                name="title"
                :class="['mb-2', { 'has-error': errors.has('title') }]"
                placeholder="title"
              />
              <span
                v-if="errors.has('title')"
                class="text-danger small"
                v-text="errors.first('title')"
              />
            </d-col>
          </d-row>
          <d-row>
            <d-button type="submit" class="ml-auto mr-4">Add</d-button>
          </d-row>
        </d-form>
      </d-modal-body>
    </d-modal>
  </div>
</template>

<script>
export default {
  data() {
    return {
      showModal: false,
      form: {
        title: ''
      }
    }
  },
  methods: {
    handleClick() {
      this.showModal = true
    },
    handleClose() {
      this.showModal = false
    },
    handleOnSubmit(e) {
      e.preventDefault()
      this.$validator.validateAll().then(x => {
        if (x) {
          this.$axios.$post('/projects', this.form).then(res => {
            this.$emit('refresh')
            this.showModal = false
            this.form.title = ''
          })
        }
      })
    }
  }
}
</script>
