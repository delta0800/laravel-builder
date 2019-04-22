<template>
  <div>
    <div class="d-flex mb-4">
      <button
        type="button"
        class="btn btn-brand btn-elevate btn-pill btn-elevate-air ml-auto mr-3"
        data-toggle="modal"
        data-target="#kt_modal_6"
      >
        <i class="fas fa-plus mr-2"></i>Add New
      </button>
    </div>
    <div
      id="kt_modal_6"
      class="modal fade"
      tabindex="-1"
      role="dialog"
      aria-labelledby="exampleModalCenterTitle"
      aria-hidden="true"
      :style="showModal"
    >
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <form @submit="handleOnSubmit">
            <div class="modal-header">
              <h5 id="exampleModalLongTitle" class="modal-title">
                Add Project
              </h5>
              <button
                type="button"
                class="close"
                data-dismiss="modal"
                aria-label="Close"
              ></button>
            </div>
            <div class="modal-body">
              <div class="row form-group mx-5">
                <div class="col-12">
                  <label>Title</label>
                  <input
                    v-model="form.title"
                    v-validate="'required'"
                    name="title"
                    :class="[
                      'mb-2 form-control',
                      { 'is-invalid': errors.has('title') }
                    ]"
                    placeholder="title"
                  />
                  <span
                    v-if="errors.has('title')"
                    class="invalid-feedback"
                    v-text="errors.first('title')"
                  />
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button
                type="button"
                class="btn btn-secondary"
                data-dismiss="modal"
              >
                Close
              </button>
              <button type="submit" class="btn btn-primary">
                Add
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      showModal: '',
      form: {
        title: ''
      }
    }
  },
  methods: {
    handleOnSubmit(e) {
      e.preventDefault()
      this.$validator.validateAll().then(x => {
        if (x) {
          this.$axios.$post('/projects', this.form).then(res => {
            this.$store.commit('appendProject', res)
            console.log(res)
            this.showModal = 'display: none;'
            this.form.title = ''
          })
        }
      })
    }
  }
}
</script>
