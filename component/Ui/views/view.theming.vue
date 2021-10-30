<template>
  <div>
    <h1>Theming</h1>

    <h2>Basic theming</h2>
    <p class="m-y-3">
      SCSS variables can be found in the file <span class="font-strong">frame/VastN3/css/index.scss</span>.
      The section <span class="text-accent-dark">/* Colors */</span> along with maps regulating color shades, opacity etc.
      You may also want to change other variables to account for you specific styling needs.
    </p>
    <h2>Ui components</h2>
    <p>
      When adding new colors or variations to your SCSS, not all ui-components will be able to act in accordance with properties that derive from this addition.
      Many ui-components use a property named color if conditional matching of classes to CSS becomes necessary.
      The file <span class="font-strong">frame/VastN3/component-theming.js</span> can be used to cover such edge-cases and provide theming behavior to
      all components (including the pre-built ui components).
    </p>
    <h2>Optimization</h2>
    <p>
      Once your application is ready for a release (or in between to check for performance), run
      <ui-code>php optimize.php</ui-code> to purge any non-used CSS rules.
      All files within the component folder are considered when a purge is run. Additionally, you can add "holy" to the $save variable in the file
      <span class="font-strong">optimize.php</span> if you need to protect certain classes (like animations) not to be purged in any case.
    </p>

  </div>
</template>

<script>

export default {
  name: "viewGaudiamus",
  components: {

  },

  template: `{{template}}`
}
</script>

<style scoped>

</style>