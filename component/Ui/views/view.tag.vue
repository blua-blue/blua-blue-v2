<template>
  <section>
    <h1>Tag</h1>
    <p>
      Easily controllable tag component for various use-cases. Supports colors & disabled-state.
      The events "selected" and "removed" can be used to handle user interaction.
    </p>
    <div class="grid md:grid-6-6 m-t-3">
      <div class="p-3">
        <div>{{clicked}}</div>
        <div class="m-y-3">
          <ui-tag @selected="clicked='clicked: default'">tag</ui-tag>
          <ui-tag @selected="clicked='clicked: primary'" color="primary">primary</ui-tag>
        </div>
        <div class="m-y-3">
          <ui-tag @selected="clicked='clicked: accent-filled'" color="accent-filled">filled</ui-tag>
          <ui-tag @selected="clicked='clicked: disabled'" disabled>disabled</ui-tag>
        </div>
        <div class="m-y-3">
          <ui-tag @selected="clicked='clicked: removable'" v-if="removable" @removed="removable=false" removable>removable</ui-tag>
        </div>
      </div>
      <div>
        <ui-code >
          <pre>
          &lt;ui-tag @selected="onSelectMethod" @removed="onRemovedMethod" color="primary" :disabled="disabled">tag&lt;/ui-tag>
          </pre>
        </ui-code>
      </div>

    </div>

  </section>

</template>

<script>
import UiTag from '/vue/ui/lib/ui.tag';
import UiCode from '/vue/ui/lib/ui.code';

export default {
  name: "viewTag",
  components: {
    UiTag,
    UiCode
  },
  data:()=>({
    removable: true,
    clicked:''
  }),
  template: `{{template}}`
}
</script>