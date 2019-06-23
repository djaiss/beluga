<template>
  <layout title="Home" :no-menu="false">
    <div class="ph2 ph0-ns">
      <div class="cf mt4 mw7 center br3 mb3 bg-white box">
        <div class="fn fl-ns w-50-ns pa3">
          Create a contact
        </div>
        <div class="fn fl-ns w-50-ns pa3">
          <!-- Form Errors -->
          <errors :errors="form.errors" />

          <form @submit.prevent="submit">
            <div class="">
              <label class="db fw4 lh-copy f6" for="first_name">Name</label>
              <input v-model="form.first_name" type="text" name="first_name" class="br2 f5 w-100 ba b--black-40 pa2 outline-0" required />
            </div>

            <!-- Actions -->
            <div class="">
              <div class="flex-ns justify-between">
                <div>
                  <loading-button :classes="'btn add w-auto-ns w-100 mb2 pv2 ph3'" :state="loadingState" :text="'register'" data-cy="create-company-submit" />
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </layout>
</template>

<script>

export default {
  props: {
    user: {
      type: Object,
      default: null,
    },
  },

  data() {
    return {
      form: {
        first_name: null,
        errors: [],
      },
      loadingState: '',
      errorTemplate: Error,
    };
  },

  methods: {
    submit() {
      this.loadingState = 'loading';

      axios.post('/contacts', this.form)
        .then(response => {
          Turbolinks.visit('/home');
        })
        .catch(error => {
          this.loadingState = null;
          this.form.errors = _.flatten(_.toArray(error.response.data));
        });
    },
  }
};
</script>
