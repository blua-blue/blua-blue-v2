<template>
  <div>
    <h1>Minimal UI system</h1>
    <p class="m-y-3">
      In order to help you develop faster, we included a few components based on the
      <a href="https://gaudiamus-css.github.io/" target="_blank">Gaudiamus CSS framework builder</a>. <br>
      Ui components are not globally registered.
      Imports in vast-n3 work based on the absolute path
    </p>
    <ui-code>/vue/{component folder}/[/subfolder/]{file without .vue extension}</ui-code>
    <p class="m-y-3">
      Hence, in order to use our prebuilt components, please import them like this:
    </p>
    <ui-code class="w-100p" lang="js">
      <pre>
        // import uiComponent from '/vue/ui/lib/ui.component';

        // e.g.

        import uiCode from '/vue/ui/lib/ui.code';

        export default{
          ...
          components:{uiCode}
        }
      </pre>

    </ui-code>
  </div>
</template>

<script>
import uiCode from '/vue/ui/lib/ui.code';

export default {
  components:{uiCode},
  name: "viewIntroduction",
  template: `{{template}}`
}
</script>

<style>

</style>