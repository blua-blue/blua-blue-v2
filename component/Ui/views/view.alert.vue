<template>
  <section>
    <h1>Alert</h1>
    <p>
      Simple box-message
    </p>
    <div class="grid md:grid-6-6 m-t-3">
      <div class="p-3">
        <ui-alert class="grid grid-3-9" color="warning-light-filled">
          <ui-icon class="font-lg">warning</ui-icon>
          <div class="place-y-center">some message</div>
        </ui-alert>
        <ui-alert class="grid grid-3-9 m-t-3" color="warning-light">
          <ui-icon class="font-lg text-warning">warning</ui-icon>
          <div class="place-y-center">some message</div>
        </ui-alert>
        <ui-alert class="grid grid-3-9 m-t-3" color="success">
          <ui-icon class="font-lg text-success-darker">check</ui-icon>
          <div class="place-y-center">good news</div>
        </ui-alert>
      </div>
      <div>
        <ui-code>
          <pre>
          &lt;ui-alert class="grid grid-3-9 m-t-3" color="success">
            &lt;ui-icon class="font-lg text-success-darker">check&lt;/ui-icon>
            &lt;div class="place-y-center">good news&lt;/div>
          &lt;/ui-alert>
          </pre>
        </ui-code>
      </div>

    </div>

  </section>

</template>

<script>
import UiAlert from '/vue/ui/lib/ui.alert';
import UiCode from '/vue/ui/lib/ui.code';
import UiIcon from '/vue/ui/lib/ui.icon';

export default {
  name: "viewAlert",
  components: {
    UiAlert,
    UiCode,
    UiIcon
  },
  data:()=>({
    removable: true,
    clicked:''
  }),
  template: `{{template}}`
}
</script>