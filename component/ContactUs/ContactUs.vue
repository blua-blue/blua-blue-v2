<template>
  <div class="container m-t-5">
    <h1>Contact us</h1>
    <p>Use this form for general inquiries and questions.</p>
    <form @submit.prevent="sendMessage">
      <div class="m-y-2">
        <ui-input type="email" required v-model:value="form.sent_from" label="Email"></ui-input>
      </div>
      <div class="m-y-2">
        <ui-input type="textarea" required v-model:value="form.content" label="Your message"></ui-input>
      </div>
      <n-verification v-model:code="form.code"></n-verification>
      <div class="m-y-2 grid">
        <ui-button :disabled="form.code.length<12" class="place-x-end font-md" type="submit">send</ui-button>
      </div>
    </form>
    <ui-modal :show="showModal" @close="showModal=false">
      <div class="p-3">
        We received your message and will get back to you soon!
      </div>
    </ui-modal>
  </div>
</template>

<script>
import uiInput from '/vue/ui/lib/ui.input';
import uiButton from '/vue/ui/lib/ui.button';
import uiModal from '/vue/ui/lib/ui.modal';

export default {
  components: {uiInput, uiButton, uiModal},
  data: () => ({
    showModal: false,
    form:{
      code: '',
      content: '',
      sent_from: ''
    }

  }),
  inject:['API'],
  methods: {
    sendMessage(){
      this.API.post('/contact-us', this.form).then(res => {
        this.showModal = true;
      })
    }
  },
  template: `{{template}}`,
}
</script>