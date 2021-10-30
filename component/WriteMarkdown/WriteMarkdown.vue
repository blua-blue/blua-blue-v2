<template>
  <div>
    <div class="d-flex">
      <div class="f-1">
        <ui-button @click="showMarkdownPreview=!showMarkdownPreview; generateMarkdown()">
          <span v-if="!showMarkdownPreview"><ui-icon>preview</ui-icon> preview</span>
          <span v-else><ui-icon>edit</ui-icon> edit</span>
        </ui-button>
      </div>
      <div class="f-1">
        <ui-button @click="showImageModal=true"><ui-icon>image</ui-icon> images</ui-button>
      </div>

    </div>
    <div class="m-t-3" v-if="showMarkdownPreview">
      <div class="markdown" v-html="markdownPreview"></div>
      <p class="b-rounded b-1 b-warn p-2"><ui-icon class="font-lg">warning</ui-icon> This preview is not necessarily identical with how this content is ultimately going to be displayed.</p>
    </div>
    <ui-input v-else type="textarea" class="expanded-textarea"
              v-model:value="content.content"></ui-input>
    <ui-modal title="Images" :show="showImageModal" @close="showImageModal=false">
      <div class="p-3">
        <p></p>
        <Image :hideSelect="true"/>
      </div>
    </ui-modal>
  </div>
</template>

<script>
import uiButton from '/vue/ui/lib/ui.button';
import uiInput from '/vue/ui/lib/ui.input';
import uiModal from '/vue/ui/lib/ui.modal';
import uiIcon from '/vue/ui/lib/ui.icon';
import Image from '/vue/image';
export default {
  components:{
    uiButton,
    uiInput,
    uiModal,
    uiIcon,
    Image
  },
  data:()=>({
    markdownPreview:null,
    showMarkdownPreview:false,
    showImageModal:false
  }),
  watch:{
    content(newV){
      if(newV.content_type==='markdown'){
        this.generateMarkdown()
      }
    }
  },
  props: {
    content: Object
  },
  methods: {
    generateMarkdown(){
      const renderer = new showdown.Converter({tables: true});
      this.markdownPreview = renderer.makeHtml(this.content.content)
    },
  },
  template: `{{template}}`,
}
</script>