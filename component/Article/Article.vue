<template>
  <div class="container m-y-3">
    <div class="grid md:grid-9-3">
      <article class="md:m-r-3">
        <h1>{{article.name}}</h1>
        <div class="grid">
          <router-link class="place-x-end" :to="'/write/'+article.id" n-if="false" v-if="isMine">
            <ui-button><ui-icon>edit</ui-icon></ui-button>
          </router-link>
        </div>

        <p class="font-md">{{article.teaser}}</p>
        <div style="max-width: 100%" >
          <img n-if="article.image" class="w-100p b-2 b-primary raise-1-primary-50" v-if="article.image" src="{{article.image.path}}" :src="article.image.path" alt="article image">
        </div>

        <div style="min-height: 40px;">
          <Share></Share>
        </div>
        <div>
          <div class="b-white-dark-50 p-t-3" :class="{'markdown':content.content_type ==='markdown'}" n-for="contents as content" v-for="content in article.article_content" v-html="content.html" key="content.id">
            {{content.html}}
          </div>

        </div>
        <share/>
        <report :slug="article.slug"/>
        <comments :comments="article.article_comment"/>
      </article>
      <section class="md:m-l-2">
        <div>
          <p class="text-uppercase text-secondary b-b-1 b-secondary">Author</p>
          <router-link  :to="'/profile/'+article.author?.user_name" class="text-decoration-none">
            <a class="d-flex text-decoration-none text-primary" v-if="article.author">
              <img n-if="article.author.image" v-if="article.author.image" class="w-100px" src="{{article.author.image.path}}" alt="author image" :src="article.author.image.path">
              <p class="m-l-3">{{article.author.user_name}}</p>
            </a>
          </router-link>
          <p class="text-uppercase text-secondary b-b-1 b-secondary">Metrics</p>
          <div class="grid md:grid-6-6">
            <p>Readers: {{metrics.unique}}</p>
            <p>Total visits: {{metrics.total}}</p>
          </div>
          <p class="text-uppercase text-secondary b-b-1 b-secondary">Keywords</p>
          <Keywords :keywords="article.keywords">
            {{article.keywords}}
          </Keywords>
          <p class="text-uppercase text-secondary b-b-1 b-secondary">Suggestions</p>
          <div n-if="suggestions.length>0">
            <div class="b-rounded b-1 b-primary text-decoration-none grid m-y-2 w-100p"
                 v-if="suggestions.length>0"
                 n-for="suggestions as suggestion"
                 v-for="suggestion in suggestions">
              <div @click.prevent="navigate('/article/'+suggestion.slug)" >
                <a href="{{base}}article/{{suggestion.slug}}" class="text-decoration-none" >
                  <div class="bg-primary-light p-2 text-white font-md">{{suggestion.name}}</div>
                  <p class="p-x-3 text-black">{{suggestion.teaser}}</p>
                </a>

              </div>
            </div>

          </div>

        </div>

      </section>
    </div>

  </div>
</template>
<style>
p img{
  max-width: 100%;
}
</style>

<script>
import Keywords from "/vue/keywords";
import uiIcon from "/vue/ui/lib/ui.icon";
import uiButton from "/vue/ui/lib/ui.button";
import Share from '/vue/share';
import Comments from '/vue/comments';
import Report from '/vue/report';
export default {
  components:{Keywords,uiIcon,uiButton,Share,Comments,Report},
  setup(){
    const store = Vue.inject('neoanStore')
    const metrics = Vue.ref({total:0, unique:0})
    const API = Vue.inject('API')
    const route = VueRouter.useRoute();
    const article = Vue.ref({})
    const suggestions = Vue.ref([])
    const isMine = Vue.ref(false);
    store.subscribe('auth', loggedIn => {
      isMine.value = loggedIn.length>0 && article.value && article.value.author_user_id === loggedIn[0].user.id
    })
    function load(){
      neoanStore.getAll('article').then(allKnown => {
        article.value = allKnown.find(x => {
          if(x.slug === route.params.slug){
            suggestions.value = [];
            if(x.keywords){
              const keywords = x.keywords.split(',')
              suggestions.value = allKnown.filter(article => {
                let hit = false;
                if(article.id === x.id){
                  return false;
                }
                keywords.forEach(keyword => {
                  if(article.keywords && article.keywords.includes(keyword)){
                    hit = true;
                  }
                })
                return hit;
              })
            }

            return true;
          }
          return false;
        });
        if(route.params.slug){
          API.post('/metric',{endpoint:'/article/'+route.params.slug }).then(res => {
            metrics.value = res.data;
          })
        }


      })
    }
    load()
    Vue.watch(() => route.params.slug, load)

    return {article, suggestions,isMine, metrics}
  },
  methods:{
    navigate(to){
      this.$router.push(to)
    }
  },
  template: `{{template}}`,
}
</script>