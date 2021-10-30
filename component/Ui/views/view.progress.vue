<template>
  <section>
    <h1>Progress</h1>
    <p>
      Uploading, downloading, processing: when waiting, the user is used to seeing a progress bar!
      This component understands a value and an interim (animated) state.
    </p>
    <div class="grid md:grid-6-6 m-t-3">
      <div class="p-3">
        <ui-progress class="m-b-3" :value="30" ></ui-progress>
        <ui-progress class="m-b-3" :value="50" color="accent-filled"></ui-progress>
        <ui-progress :value="100" color="success-light-filled" animate></ui-progress>
      </div>
      <div>
        <ui-code >
          <pre>
          &lt;ui-progress class="m-b-3" :value="30" >&lt;/ui-progress>
          &lt;ui-progress class="m-b-3" :value="50" color="accent-filled">&lt;/ui-progress>
          &lt;ui-progress :value="100" color="success-light-filled" animate>&lt;/ui-progress>
          </pre>
        </ui-code>
      </div>

    </div>

  </section>

</template>

<script>
import UiProgress from '/vue/ui/lib/ui.progress';
import UiCode from '/vue/ui/lib/ui.code';

export default {
  name: "viewProgress",
  components: {
    UiProgress,
    UiCode
  },
  data: () => ({
  }),
  template: `{{template}}`
}
</script>

<style scoped>

</style>