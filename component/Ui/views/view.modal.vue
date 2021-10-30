<template>
  <section>
    <h1>Modal</h1>
    <p>
      May it be for a login-form, terms & conditions or any additional content interfering with the flow: A modal
      is always helpful.
    </p>
    <div class="grid md:grid-6-6 m-t-3">
      <div class="p-3">
        <ui-button @click="showModal=true" color="accent">
          <ui-icon>open_in_browser</ui-icon>
          <span>open</span>

        </ui-button>
      </div>
      <div>
        <ui-code >
          <pre>
          &lt;ui-modal :show="showModal" @close="showModal =! showModal">
            &lt;div class="p-5">
              hi there
            &lt;/div>
          &lt;/ui-modal>
          </pre>
        </ui-code>
      </div>

    </div>




    <ui-modal :show="showModal" @close="showModal =! showModal">
      <div class="p-5">
        This is a modal
      </div>
    </ui-modal>
  </section>

</template>

<script>
import UiButton from '/vue/ui/lib/ui.button';
import UiModal from '/vue/ui/lib/ui.modal';
import UiIcon from '/vue/ui/lib/ui.icon';
import UiCode from '/vue/ui/lib/ui.code';

export default {
  name: "viewModal",
  components: {
    UiButton,
    UiModal,
    UiIcon,
    UiCode
  },
  data: () => ({
    showModal: false
  }),
  template: `{{template}}`
}
</script>

<style scoped>

</style>