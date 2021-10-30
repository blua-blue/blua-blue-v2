<template>
  <div>
    <h1>Code</h1>
    <p>
      Sometimes you just need to display some "nerdism".
    </p>
    <div class="grid-6-6">
      <div class="p-2">
        <ui-code>
          /*code*/
        </ui-code>
      </div>
      <div class="p-2">
        <ui-code>
          &lt;ui-code>/*code*/&lt;/ui-code>
        </ui-code>
      </div>
    </div>
  </div>
</template>

<script>
import UiCode from '/vue/ui/lib/ui.code'

export default {
  name: "viewCode",
  components: {
    UiCode
  },
  template: `{{template}}`
}
</script>

<style scoped>

</style>