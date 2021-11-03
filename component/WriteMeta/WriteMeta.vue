<template>
  <form @submit.prevent="storeArticle" >
    <h1 class="font-md text-center b-b-1 b-primary-50">General</h1>
    <div class="grid md:grid-6-6">
      <div>
        <ui-input required label="Title" v-model:value="article.name"></ui-input>
      </div>
      <div  class=" md:m-l-5">
        <ui-input label="Teaser (max 255 characters)" required type="textarea"
                  v-model:value="article.teaser"></ui-input>
      </div>
      <div>
        <ui-input required label="Category" option-value="id" option-title="name" :options="categories" placeholder="Choose category" type="select" v-model:value="article.category_id"></ui-input>
      </div>
      <div class="place-y-center md:m-l-5">
        <div class="text-accent m-t-1">Keywords</div>
        <div class="d-inline-block">
          <ui-tag v-if="article.keywords&&article.keywords.length>0" @removed="removeKeyword(keyword)" class="m-t-1" color="accent-filled" removable v-for="keyword in article.keywords.split(',')">{{keyword}}</ui-tag>
          <div class="b-1 p-x-2 d-inline-block bg-white m-t-1" style="border-radius: 15px; ">
            <input @blur="keywordProcess($event,true)" @keyup="keywordProcess" v-model="newKeyword" placeholder="add keyword (comma-separated)" type="text" class="b-0">
          </div>

        </div>

      </div>
      <div class="d-flex">
        <ui-input class="f-1 place-y-center" :label="'Public on blua.blue ' + (!article.publish_date?'once published':'')" type="checkbox" v-model:value="article.is_public"></ui-input>
        <ui-icon @click="infoModal=true" title="click" class="font-md cursor-pointer text-primary m-l-2 place-y-center p-b-2">info</ui-icon>

      </div>

      <div class=" md:m-l-5">
        <div class="text-accent m-t-1">Slug </div>
        <div><span class="font-strong text-primary" v-if="article.slug">{{article.slug}}</span><i class="font-light" v-else>..will be generated on save..</i></div>
      </div>
    </div>
    <h1 class="font-md text-center b-b-1 b-primary-50 m-t-4">Image(s)</h1>
    <div class="grid md:grid-5-7">
      <div class="m-r-3">
        <p>Post image</p>
        <img v-if="article.image" :src="article.image.path" alt="post image" class="w-100p">
        <div v-else>
          <strong>Select or upload image</strong>
        </div>
      </div>
      <section style="overflow-y:auto; max-height: 250px">
        <Image :selected-id="article.image_id" @selected="selectImage"></Image>
      </section>

    </div>
    <h1 class="font-md text-center b-b-1 b-primary-50 m-t-4">Webhooks</h1>
    <p class="font-sm">Webhooks can be created in your profile</p>
    <ui-alert color="primary" v-if="webhooks.length<1">
      You have not set up any webhooks to external sites/services.
    </ui-alert>
    <div class="m-r-5" v-for="webhook in webhooks">
      <ui-input type="checkbox" :label="'trigger '+webhook.name" v-model:value="webhook.active"></ui-input>
    </div>
    <h1 class="font-md text-center b-b-1 b-primary-50 m-t-4">Execute</h1>
    <div class="grid-4-4-4 m-y-3">
      <div class="place-y-center">
        <ui-button color="warning-filled" @click="deleteModal=true"><ui-icon>delete</ui-icon> Delete article</ui-button>
      </div>
      <div >
        <ui-input type="switch" v-model:value="publishStatus">
          <template #label>
            <div class="place-y-start">
              publish on save
              <span v-if="!article.publish_date"><ui-icon>public_off</ui-icon></span>
              <span v-if="article.publish_date"><ui-icon>public</ui-icon></span>
              <ui-icon @click="infoModal=true" title="click" class="font-md cursor-pointer text-primary">info</ui-icon>
            </div>

          </template>
        </ui-input>

      </div>
      <div class="place-x-end place-y-center">

        <ui-button class="font-md" type="submit">
          <ui-icon>save</ui-icon>
          <span v-if="article.publish_date"> Save & Publish</span>
          <span v-if="!article.publish_date"> Save as draft</span>
        </ui-button>
      </div>
    </div>
    <teleport to="body">
      <ui-modal :show="deleteModal" title="Delete article" @close="deleteModal=false">
        <div class="p-5">
          Do you really want to delete this article? This cannot be reversed. <br>
          <ui-button color="warning-filled" @click="deleteArticle">Yes, delete this article</ui-button>
        </div>
      </ui-modal>
    </teleport>
    <teleport to="body">
      <ui-modal :show="infoModal" title="Pubic vs Published" @close="infoModal=false">
        <div class="p-3">
          <p>Since blua.blue can be used in several ways, let's clarify:</p>
          <h3>Draft vs. Published</h3>
          <p>Each article can be saved as a draft prior to publishing it. Other than your login (including API, of course), access to your article isn't possible.</p>
          <p>As soon as an article is published, blua.blue considers it as "live". However, this doesn't mean people can find or access it via blua.blue:</p>
          <h3>Public</h3>
          <p>
            The public flag distinguishes between published content that is used somewhere else (like via API or webhook) only and content that is available via this blua.blue webdomain.
            <br>Without being <strong>published</strong>, content cannot be seen by others regardless of public-state. BUT: publicity impacts your (SEO-)strategy.
            On one hand, being public within the realms of blua.blue can provide exposure and readers.
            On the other hand, you may want your content to be canonical (interpreted as original) on your own web-presence.
          </p>
          <h3>Versioning</h3>
          <p>
            As of now, versioning is not included in blua.blue. If you make changes to a published article, they will be live. If you set a previously published article as a draft,
            the article will be removed from public availability.
          </p>
        </div>
      </ui-modal>
    </teleport>
    <ui-notification :show="saveNotification">Article saved</ui-notification>
  </form>

</template>

<script>
import uiInput from '/vue/ui/lib/ui.input';
import uiAlert from '/vue/ui/lib/ui.alert';
import uiTag from '/vue/ui/lib/ui.tag';
import uiButton from '/vue/ui/lib/ui.button';
import uiIcon from '/vue/ui/lib/ui.icon';
import uiModal from '/vue/ui/lib/ui.modal';
import uiNotification from '/vue/ui/lib/ui.notification';
import Image from "/vue/image";
export default {
  props:{
    article:Object
  },
  components:{
    uiInput,
    uiTag,
    uiButton,
    uiAlert,
    uiIcon,
    uiModal,
    uiNotification,
    Image
  },
  inject:['neoanStore'],
  computed:{
    publishStatus: {
      get(){
        return this.article.publish_date !== null
      },
      set(val){
        this.article.publish_date = val ? '.' : null;
      }
    }
  },
  setup(){
    const saveNotification = Vue.ref(false)
    const newKeyword = Vue.ref('')
    const categories = Vue.ref([])
    const webhooks = Vue.ref([])
    const infoModal = Vue.ref(false)
    const deleteModal = Vue.ref(false)
    const API = Vue.inject('API')
    API.get('/webhook').then(res => {
      res.data.map((hook, i)=>{
        res.data[i].active = true;
      })
      webhooks.value = res.data
    })
    const store = Vue.inject('neoanStore');
    store.getAll('category').then(allKnown => categories.value = allKnown)
    return {categories, newKeyword, webhooks, infoModal, deleteModal,saveNotification}
  },
  methods:{
    toggleNotification(){
      this.saveNotification = true;
      setTimeout(()=> this.saveNotification = false, 4000);
    },
    selectImage($ev){
      this.article.image = $ev;
      this.article.image_id = $ev.id;
    },
    removeKeyword(word){
      if(this.article.keywords.length === word.length){
        this.article.keywords = '';
      } else {
        const pattern = new RegExp(`,${word}`);
        this.article.keywords = this.article.keywords.replace(pattern,'');
      }
    },
    keywordProcess(ev, blur){
      if(ev.key === ','){
        this.article.keywords += (this.article.keywords.length>0 ? ',':'') + this.newKeyword.substring(0,this.newKeyword.length-1)
        this.newKeyword = '';
      } else if(blur){
        ev.preventDefault()
        this.article.keywords += (this.article.keywords.length>0 ? ',':'') + this.newKeyword
        this.newKeyword = '';
      }

    },
    deleteArticle(){
      this.neoanStore.delete('article', this.article).then(()=>{
        this.$router.push('/profile/'+this.article.author.user_name)
      })
    },
    storeArticle()
    {
      this.article.webhooks = this.webhooks;
      this.neoanStore[this.article.id ? 'update':'post']('article', this.article).then(success=>{
        this.toggleNotification()
      })
    }
  },
  template: `{{template}}`,
}
</script>