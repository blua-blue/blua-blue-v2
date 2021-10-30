<template>
  <div>
    <h1>Icons</h1>
    <p>
      We are using <a href="https://material.io/resources/icons/" target="_blank">material icons</a> and render them
      according to your color needs.
    </p>
    <div class="grid md:grid-6-6">
      <div>
        <p class="m-t-3 m-b-2">single color:</p>
        <ui-icon class="text-accent">api</ui-icon>
        <p class="m-t-3 m-b-2">hover effect:</p>
        <ui-icon class="text-primary-light hover:text-accent">api</ui-icon>
        <p class="m-t-3 m-b-2">big:</p>
        <ui-icon class="text-primary-light hover:text-accent">api</ui-icon>
        <p class="m-t-3 m-b-2">in a button:</p>
        <ui-button color="primary">
          <ui-icon>api</ui-icon>
        </ui-button>
        <p class="m-t-3 m-b-2">in a button with distinct icon color:</p>
        <ui-button color="primary">
          <ui-icon class="text-primary-light">api</ui-icon>
        </ui-button>

      </div>
      <div class="p-2">

        <ui-code class="w-100p" >
                  <pre style="white-space: pre-wrap;">
                    &lt;ui-icon class="text-accent">api&lt;/ui-icon>
                    &lt;ui-icon
                      class="text-primary-light hover:text-accent">
                      api
                    &lt;/ui-icon>
                    &lt;ui-button color="primary">
                      &lt;ui-icon>api&lt;/ui-icon>
                    &lt;/ui-button>
                    &lt;ui-button color="primary">
                      &lt;ui-icon class="text-primary-light">api&lt;/ui-icon>
                    &lt;/ui-button>
                  </pre>
        </ui-code>
      </div>
    </div>
  </div>
</template>

<script>
import UiButton from '/vue/ui/lib/ui.button';
import UiIcon from '/vue/ui/lib/ui.icon';
import UiCode from '/vue/ui/lib/ui.code'

export default {
  name: "viewIcon",
  components: {
    UiButton,
    UiIcon,
    UiCode
  },
  template: `{{template}}`
}
</script>

<style scoped>

</style>