<template>
  <div class="d-flex m-y-1 font-sm">
    <div>
      <ui-icon class="font-lg">share</ui-icon>
    </div>

    <a class="text-decoration-none b-1 b-rounded b-gray text-white p-x-3 p-y-1 m-x-1 f-1 hover:raise-1-primary" style="background: #1d9bf0" :href="'https://twitter.com/share?url='+url">
      Twitter
    </a>
    <a class="text-decoration-none b-1 b-rounded b-gray text-white p-x-3 p-y-1 m-r-1 f-1 hover:raise-1-primary" style="background: #1877f2" :href="'https://www.facebook.com/sharer/sharer.php?u='+url">
      Facebook
    </a>
    <a class="text-decoration-none b-1 b-rounded b-gray text-white p-x-3 p-y-1 m-r-1 f-1 hover:raise-1-primary" style="background: #FF4500" :href="'https://reddit.com/submit?url='+url">
      Reddit
    </a>
    <a class="text-decoration-none b-1 b-rounded b-gray text-white p-x-3 p-y-1 m-r-1 f-1 hover:raise-1-primary" style="background: rgb(255, 102, 0);" :href="'https://news.ycombinator.com/submitlink?u='+url">
      Hacker news
    </a>
    <a class="text-decoration-none b-1 b-rounded b-gray text-white p-x-3 p-y-1 m-r-1 f-1 hover:raise-1-primary" style="background: #0073b1" :href="'https://www.linkedin.com/shareArticle?url='+url">
      Linked In
    </a>
  </div>
</template>

<script>
import uiIcon from '/vue/ui/lib/ui.icon';
export default {
  components:{uiIcon},
  setup(){
    const route = VueRouter.useRoute();
    const url = Vue.ref('{{base}}'+ route.fullPath.substr(1))
    Vue.watch(()=>route.fullPath, path => {
      url.value = '{{base}}' + route.fullPath.substr(1);
    })

    return {url}
  },
  template: `{{template}}`,
}
</script>