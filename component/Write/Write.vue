<template>
  <div class="container m-t-5">
    <div v-if="false">Loading...</div>
    <ui-tabs :tabs="['Edit Content','Save']" v-model:selected="selectedTab"></ui-tabs>
    <div class="grid m-t-3" :class="{'md:grid-3-9':selectedTab===0}" n-if="false">

      <div class="bg-primary-lighter-50 b-rounded m-x-2 p-t-2">

        <!--        content -->

        <div v-if="editable.article_content&&editable.article_content.length>0&&selectedTab===0">
          <draggable
              item-key="id"
              @end="resort"
              handle=".drag-handle"
              :list="editable.article_content">
            <template #item="{element,index}">
              <div>
                <div class="m-2 b-rounded p-x-3 d-flex"
                     :class="{'bg-accent-light':index===activeContent,'bg-white':index!==activeContent, 'bg-warning-50':element.delete_date}">
                  <div class="place-y-center grid" v-if="move">
                    <ui-icon class="drag-handle cursor-draggable hover:text-success">drag_handle</ui-icon>
                  </div>
                  <div class=" place-y-center">
                    <ui-icon title="markdown" class="font-md" v-if="element.content_type === 'markdown'">toc</ui-icon>
                    <ui-icon title="WYSIWYG" class="font-md" v-if="element.content_type === 'html'">ballot</ui-icon>
                    <ui-icon title="image" class="font-md" v-if="element.content_type === 'img'">image</ui-icon>
                    <ui-icon title="image" class="font-md" v-if="element.content_type === 'video'">ondemand_video</ui-icon>
                  </div>
                  <div class="f-1 p-x-1 m-r-1">
                    <ui-input v-if="['markdown','html'].includes(element.content_type)&&!move" v-model:value="element.title"/>
                    <p class="m-y-2" v-else-if="element.content_type==='img'&&!move">Image</p>
                    <div class="m-y-2 overflow-x-auto w-80p" v-else>{{element.title}}</div>
                  </div>
                  <ui-icon title="currently editing" class="font-lg place-y-center text-success"
                           v-if="index===activeContent&&!move">visibility
                  </ui-icon>
                  <ui-icon title="switch to this content element"
                           class="font-lg place-y-center text-gray hover:text-success-50 cursor-pointer"
                           v-if="index!==activeContent&&!move" @click="activeContent=index">visibility_off
                  </ui-icon>

                </div>
              </div>

            </template>
          </draggable>
          <div class="grid-6-6 md:grid-4-4-4 lg:grid-3-3-3-3 b-t-1 b-primary ">
            <ui-button title="add markdown element" @click="newContent('markdown')" class="m-2 font-default f-1" color="primary-filled"><ui-icon>add_circle_outline</ui-icon> <ui-icon>toc</ui-icon></ui-button>
            <ui-button title="add html/WYSIWYG element" @click="newContent('html')" class="m-2 font-default f-1" color="primary-filled"><ui-icon>add_circle_outline</ui-icon> <ui-icon>ballot</ui-icon></ui-button>
            <ui-button title="add image element" @click="newContent('img')" class="m-2 font-default f-1" color="primary-filled"><ui-icon>add_circle_outline</ui-icon> <ui-icon>image</ui-icon></ui-button>
            <ui-button title="add video element" @click="newContent('video')" class="m-2 font-default f-1" color="primary-filled"><ui-icon>add_circle_outline</ui-icon> <ui-icon>ondemand_video</ui-icon></ui-button>
          </div>
          <div>
            <ui-button @click="move=!move" class="m-2 font-default f-1" color="accent-filled" title="toggle sorting"><ui-icon>pan_tool</ui-icon></ui-button>
          </div>

        </div>
        <!--        meta -->
        <div class="p-3" v-if="editable.article_content&&editable.article_content.length>0&&selectedTab===1">
          <write-meta v-model:article="editable"></write-meta>

        </div>

      </div>
      <div class="m-2 md:m-0" v-if="editable.article_content && editable.article_content.length>0 && selectedTab===0">
        <div class=" b-2 p-1 b-primary b-rounded bg-primary-lighter-50">
          <h2 class="d-flex">
            <span class="f-1">
              Content: {{ editable.article_content[activeContent].sort }} | Type: {{ editable.article_content[activeContent].content_type }}
            </span>
            <ui-icon class="text-warning cursor-pointer place-y-center"
                     @click="editable.article_content[activeContent].delete_date='.'"
                     title="Delete content">delete</ui-icon></h2>
          <ui-alert v-if="editable.article_content[activeContent].delete_date" color="warning">
            Content will be permanently removed on save! <ui-button color="success-filled" @click="editable.article_content[activeContent].delete_date=null">undo</ui-button>
          </ui-alert>
          <div class="p-2" v-show="editable.article_content[activeContent].content_type==='html'">
            <n-write-html :content="editable.article_content[activeContent]"/>
          </div>
          <div class="p-2" v-show="editable.article_content[activeContent].content_type==='video'">
            <n-write-video :content="editable.article_content[activeContent]"/>
          </div>
          <div class="p-2 " v-show="editable.article_content[activeContent].content_type==='img'">
            <n-write-image :content="editable.article_content[activeContent]"/>
          </div>
          <div class="p-2" v-if="editable.article_content[activeContent].content_type==='markdown'">
            <n-write-markdown :content="editable.article_content[activeContent]"/>
          </div>
          <div class="grid-6-6">
            <div>~ {{ editable.article_content[activeContent].content.split(' ').filter(x => x.match(/[a-z]/i)).length }} words</div>
            <div class="place-x-end">
              <span v-if="editable.article_content[activeContent].content.length>maxContentLength">Consider creating a new content element </span>
              <span
                  :class="{'text-warning font-strong':editable.article_content[activeContent].content.length>maxContentLength}">{{ editable.article_content[activeContent].content.length }}/{{ maxContentLength }} characters</span>
            </div>

          </div>
        </div>

      </div>
    </div>
  </div>
</template>
<style>
.expanded-textarea textarea {
  min-height: 15rem;
}
</style>

<script>
import uiInput from '/vue/ui/lib/ui.input';
import uiButton from '/vue/ui/lib/ui.button';
import uiIcon from '/vue/ui/lib/ui.icon';
import uiTabs from '/vue/ui/lib/ui.tabs';
import uiModal from '/vue/ui/lib/ui.modal';
import uiAlert from '/vue/ui/lib/ui.alert';
import writeMeta from '/vue/writeMeta';

export default {
  components: {
    uiInput,
    uiButton,
    uiIcon,
    uiTabs,
    uiModal,
    uiAlert,
    draggable: vuedraggable,
    writeMeta,
  },
  data: () => ({
    editable: {},
    activeContent: 0,
    maxContentLength: 3500,
    selectedTab: 0,
    move: false,
    showImageModal:false,
  }),
  watch: {
    article(newV, oldV) {
      this.editable = newV
    },
    activeContent(newV) {

    }
  },
  setup() {
    const article = Vue.ref({article_content: [], name: '', teaser: ''});
    const categories = Vue.ref([])
    const user = Vue.ref(null);
    const route = VueRouter.useRoute();
    const store = Vue.inject('neoanStore');
    store.getAll('category').then(allKnown => categories.value = allKnown)
    store.getAll('auth').then(auth => {
      if(auth.length>0){
        user.value = auth[0].user;
      }
    })
    function fix(){
      // still empty??
      if(article.value.article_content.length < 1){
        article.value.article_content.push({sort: 1, content: '## Welcome', content_type: 'markdown'})
      }

    }
    function load() {
      if (route.params.id && route.params.id !== 'new') {
        neoanStore.find('article').then(finder => {
          const findKnown = finder('id', route.params.id)
          if (findKnown.length > 0 && findKnown[0].author_user_id === user.id) {
            article.value = findKnown[0];
            fix()
          } else {
            API.get('/article/id/' + route.params.id).then(res => {
              article.value = res.data;
              fix()
            })
          }
        })

      } else if (route.params.id && route.params.id === 'new') {
        article.value = {
          article_content: [{sort: 1, content: '## Welcome', content_type: 'markdown'}],
          name: '',
          teaser: '',
          keywords:'',
          is_public: true
        }
      }
    }

    load();
    Vue.watch(() => route.params.id, load)
    return {article, categories}
  },
  methods: {
    resort(ev) {
      this.activeContent = ev.newIndex;
      this.editable.article_content.forEach((a, i) => {
        this.editable.article_content[i].sort = i + 1;
      })
    },
    newContent(type){
      this.editable.article_content.push({sort: this.editable.article_content.length+1, content: '', content_type: type})
      this.activeContent = this.editable.article_content.length - 1;
    },

  },
  mounted() {
    this.editable = this.article;
  },
  template: `{{template}}`,
}
</script>