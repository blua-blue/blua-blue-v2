<template>
  <div>
    <h1>Simple Accordion</h1>
    <p class="m-y-3">
      A simple accordion to structure content.
    </p>
    <p>
      <ui-button @click="toggleAlt=!toggleAlt">toggle "ONE"</ui-button>
    </p>
    <div class="grid md:grid-6-6">
      <div class="p-2">
        <ui-accordion v-model:open="toggleAlt" title="ONE"></ui-accordion>
        <ui-accordion v-model:open="toggle" title="TWO">
          <p>here 2</p>
        </ui-accordion>
      </div>
      <div class="p-2">
        <ui-code>
          <pre>
            &lt;ui-accordion :open="true" :title="title"> Revealing content &lt;/ui-accordion>
          </pre>
        </ui-code>
      </div>
    </div>
  </div>
</template>

<script>
import uiAccordion from '/vue/ui/lib/ui.accordion'
import uiButton from '/vue/ui/lib/ui.button';
import uiCode from '/vue/ui/lib/ui.code';
export default {
  name: "viewAccordion",
  components: {
    uiAccordion,
    uiButton,
    uiCode
  },
  data:() => ({
    toggle: false,
    toggleAlt: false
  }),
  template: `{{template}}`
}
</script>

<style scoped>

</style>