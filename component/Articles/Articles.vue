<template>
  <div class="container m-t-5 grid">

    <div :class="{'grid':!inline, 'w-100p d-flex overflow-x-auto':inline}">
      <a href="{{base}}article/{{article.slug}}" @click.prevent="$router.push('/article/'+article.slug)" n-for="articles as article"
         v-for="article in articles.filter(filterByKeyword)" :class="{'f-1 bare-minimum':inline}"
         :style="generateStyle(article)"
         class="bg-white m-x-1 m-b-2 b-1 b-primary b-rounded-2 raise-1-gray cursor-pointer hover:raise-2-gray d-block text-decoration-none default-bg">
        <div class="b-b-1 b-primary bg-primary-90 text-white p-3 article-title" style="border-radius: .5rem .5rem 0 0">
          <h3 class="font-md">{{ article.name }}</h3>
        </div>
        <div class="grid-8-4 position-relative h-250px" >
          <p class="p-x-2 text-black position-absolute bg-white-90 place-x-start">{{ article.teaser }}</p>
        </div>
      </a>
    </div>
  </div>
</template>
<style>
.article-title {
  height: 4rem;
  overflow: hidden;
  text-overflow: ellipsis;
  display: -webkit-box;
  -webkit-box-orient: vertical;
  -webkit-line-clamp: 2;
}

.bare-minimum {
  min-width: 15rem;
}
.default-bg{
  background-image: url('{{base}}asset/img/pexels-sean-whang-804269.webp');
}
</style>

<script>
export default {
  props: {
    inline: {type: Boolean, default: false}
  },

  setup(){
    const store = Vue.inject('neoanStore');
    const api = Vue.inject('API');
    const route = VueRouter.useRoute();
    const articles = Vue.ref([])
    function byKeyword(){
      if(route.params.keyword){
        api.get('/article/keyword/'+route.params.keyword).then(res => store.pushToStore('article', res.data))
      }
    }
    const filterByKeyword = item => {
      if(route.params.keyword){
        return item.keywords && item.keywords.includes(route.params.keyword||'')
      }
      return true;
    }
    if(route.params.keyword){
      byKeyword();
    }

    Vue.watch(()=>route.params.keyword, byKeyword)
    store.subscribe('article', storeArticles => {
      articles.value = storeArticles;
    })

    return {articles, filterByKeyword}
  },

  methods:{
    generateStyle(article){
      return {
        backgroundImage: article.image ? 'url('+article.image.path +')' : 'url({{base}}asset/img/pexels-sean-whang-804269.webp)',
        backgroundPosition: 'center center',
        backgroundSize:'cover',
        backgroundRepeat: 'no-repeat'
      }
    }
  },
  template: `{{template}}`,
}
</script>