<template>
  <div>
    <h1>Button</h1>
    <p>
      Every application needs buttons, right?
    </p>
    <div class="grid-6-6">
      <div class="p-2">
        <div class="position-relative">
          <ui-button color="primary-filled" class="m-r-2 m-b-1">color="primary-filled"</ui-button>
          <ui-button color="primary" class="m-r-2 m-b-1">color="primary"</ui-button>
          <ui-button color="accent" class="m-r-2 m-b-1">color="accent"</ui-button>
          <ui-button color="success" class="m-r-2 m-b-1">color="success"</ui-button>
          <ui-button color="warning" class="m-r-2 m-b-1">color="warning"</ui-button>
          <ui-button color="black" class="m-r-2 m-b-1">color="black"</ui-button>
          <ui-button :disabled="true" class="m-r-2 m-b-1">:disabled="true"</ui-button>
          <ui-button class="m-r-2 m-b-1 font-md">.font-md</ui-button>
        </div>
      </div>
      <div class="p-2">
        <ui-code>
          &lt;ui-button color="primary">primary&lt;/ui-button>
        </ui-code>
      </div>
    </div>


  </div>
</template>

<script>
import uiButton from '/vue/ui/lib/ui.button';
import uiCode from '/vue/ui/lib/ui.code';

export default {
  name: "viewButton",
  components: {
    uiButton,
    uiCode
  },
  template: `{{template}}`
}
</script>

<style scoped>

</style>