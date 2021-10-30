<template>
  <div>
    <h1>Powered by Gaudiamus CSS</h1>
    <p>
      <a href="https://gaudiamus-css.github.io/index.html" target="_blank" class="text-primary grid w-100p">
        <img class="w-50p place-x-center"
             src="https://repository-images.githubusercontent.com/283393446/997c6380-d599-11ea-8c37-b491f15db26e"
             alt="gaudiamus logo">
      </a>
    </p>
    <p class="m-y-3">
      In the folder <span class="font-strong">/frame/VastN3/css</span> you will find all necessary files for styling,
      customization and addition. Knowing
      <a href="https://gaudiamus-css.github.io/index.html" target="_blank" class="text-primary">Gaudiamus CSS</a>-
      its functions and mixins as well as its variables - will help you to create a project of magnificent beauty and
      individuality.
    </p>
    <p>
      Rather than providing you with a dedicated design, Gaudiamus allows you to implement your own behavior and assign
      css to preexisting classes
      as well as add & modify to the capabilities. Therefore, you shouldn't view this introduction as a finite toolbox,
      but rather as a
      demo implementation of what can be achieved.
    </p>
    <p>
      We provided a simple compiler that can be run with
      <ui-code>node compile</ui-code>
      , but feel free to implement your own
      file watcher however you see fit.
    </p>
    <ui-alert color="warning-light">
      <div class="font-lg">The state of SCSS</div>
      <p>
        In October of 2020 the LibSass implementation of Sass was officially deprecated
        (<a  href="https://sass-lang.com/blog/libsass-is-deprecated" target="_blank">statement</a>),
        embracing Dart Sass as the only way forward.
        This means that libraries like node-sass and scssphp are not save solutions for compiling SCSS files. <br>
        Versions 1.3+ of Gaudiamus already require SASS language functions that aren't part of these famous
        implementations.
        <br>
        <span class="font-strong">We leave it up to the user how to compile, but currently only see the following feasible options:</span>
      </p>
      <ul class="list">
        <li>Using the provided <div class="font-strong">compile.js</div></li>
        <li>Command line (compile frame/VastN3/css/index.scss to frame/VastN3/css/index.css)</li>
        <li>Using the dart Sass JS implementation (using the package sass, not e.g. node-sass)</li>
        <li>Creating own watchers/bundlers</li>
      </ul>
    </ui-alert>

  </div>
</template>

<script>
import uiAlert from '/vue/ui/lib/ui.alert';
import uiCode from '/vue/ui/lib/ui.code';

export default {
  name: "viewGaudiamus",
  components: {
    uiAlert,
    uiCode
  },

  template: `{{template}}`
}
</script>

<style scoped>

</style>