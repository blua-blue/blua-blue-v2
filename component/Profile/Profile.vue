<template>
  <div class="container">
    <div class="grid-3-9 m-t-5">
      <div class="b-1 b-rounded b-primary p-2 position-relative">
        <img class="w-100p" src="{{user.image.path}}" alt="user profile picture" :src="user.image.path"
             n-if="user.image" v-if="user.image">
        <p class="text-center font-md text-primary">@{{ user.user_name }}</p>
        <ui-button n-if="false" v-if="isMe" color="primary-filled" class="position-absolute" style="top: 6px; left: 6px" @click="profilePictureModal=true"><ui-icon>upload</ui-icon></ui-button>
        <teleport to="body">
          <ui-modal title="Change profile picture" :show="profilePictureModal" @close="profilePictureModal=false">
            <div class="p-3">
              <n-image @selected="updateImage"/>
            </div>
          </ui-modal>
        </teleport>

      </div>
      <div class="p-l-3 grid">
        <p class="font-xl text-primary m-t-0">Published <span n-if="false">{{ writtenArticles }}</span><span
            v-if="false">{{ user.articles.length }}</span> articles</p>
        <div class="place-y-end" v-if="isMe" n-if="false">
          <ui-tabs :tabs="['Articles','Webhooks','Settings']" v-model:selected="selectedTab"></ui-tabs>
        </div>
      </div>
    </div>
    <section>
      <transition name="swipe">
        <div v-if="selectedTab===0">
          <div n-for="articles as article" v-for="article in user.articles"
               class="grid-6-6 b-rounded m-y-2 md:grid-4-4-4 lg:grid-4-2-3-2-1 b-1 b-gray-75 p-2">
            <div>
              <router-link class="text-decoration-none" :to="article.publish_date?'/article/'+article.slug:'/write/'+article.id">
                <a class="text-decoration-none text-primary"
                   href="{{base}}article/{{article.slug}}">{{ article.name }}</a>
              </router-link>
            </div>
            <div><span v-if="false">{{ article.keywords }}</span>
              <keywords :keywords="article.keywords"/>
            </div>
            <div>
              <input v-if="article.publish_date" style="font-family: 'Mukta', sans-serif" class="w-90p bg-transparent b-0" type="datetime-local" disabled
                        value="{{convertTime(article.publish_date)}}" v-model="article.universal"/>
              <span v-else>unpublished</span>
            </div>
            <div>{{article?.metrics?.unique}} Visitors</div>
            <div v-if="isMe">
              <router-link :to="'/write/'+article.id">
                <ui-button>
                  <ui-icon>edit_note</ui-icon>
                </ui-button>
              </router-link>
            </div>
          </div>
        </div>

      </transition>
      <transition name="swipe" n-if="false">
        <n-webhook v-if="selectedTab===1"></n-webhook>
      </transition>
      <transition name="swipe" n-if="false">
        <n-profile-settings :auth="authenticatedUser" v-if="selectedTab===2"></n-profile-settings>
      </transition>

    </section>
  </div>
</template>

<script>
import uiTabs from '/vue/ui/lib/ui.tabs';
import uiButton from '/vue/ui/lib/ui.button';
import uiIcon from '/vue/ui/lib/ui.icon';
import uiModal from '/vue/ui/lib/ui.modal';
import keywords from '/vue/keywords';

export default {
  components: {uiTabs, keywords, uiIcon, uiButton, uiModal},
  data: () => ({
    authenticatedUser: null,
    profilePictureModal: false
  }),
  computed: {
    writtenArticles() {
      if (this.user.articles) {
        return this.user.articles.filter(x => x.publish_date).length;
      }
      return 0;
    },
    isMe() {
      return this.authenticatedUser && this.authenticatedUser.id === this.user.id;
    }
  },
  inject: ['neoanStore'],
  mounted() {
    this.neoanStore.subscribe('auth', auth => {
      this.authenticatedUser = auth.length > 0 ? auth[0].user : null;
    })
  },
  setup() {
    const route = VueRouter.useRoute();
    const user = Vue.ref({})
    const store = Vue.inject('neoanStore');

    const hashes = {articles:0,webhooks:1,settings:2}

    console.log(hashes[route.hash.substr(1)] || 0)

    const selectedTab = Vue.ref(hashes[route.hash.substr(1)] || 0)
    const load = () => {
      API.get('/profile/' + route.params.userName).then(res => {
        res.data.articles.forEach((article, i) => {
          res.data.articles[i].metrics = {}
          if (article.publish_date) {
            const d = new Date(article.publish_date_st)
            res.data.articles[i].universal = d.toISOString().replace(/:\d{2}\.000Z/, '')
          }
          //asynchronously load stats
          API.get('/metric?endpoint=/article/' +article.slug).then(result => {
            user.value.articles.forEach((existing,j) =>{
              if(existing.id === article.id){
                user.value.articles[j].metrics = result.data;
              }
            })
          })
        })
        user.value = res.data;

      })
    }
    load()



    return {user,store,load, selectedTab}
  },
  methods:{
    updateImage(ev){
      this.user.image_id = ev.id;
      this.store.update('auth', this.user).then(()=>this.load())
    }
  },

  template: `{{template}}`,
}
</script>