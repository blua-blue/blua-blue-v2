<template>
  <div class="container m-y-3">
    <ui-tabs :tabs="['Library','Upload','External url']" v-model:selected="selectedTab"></ui-tabs>
    <div class="grid md:grid-6-6 lg:grid-4-4-4" v-if="selectedTab===0">
      <div v-for="image in images" class="b-1 b-rounded b-primary m-2 position-relative">
        <ui-icon :class="{'text-primary':image.id === selectedId,'text-transparent':image.id !== selectedId}" class="position-absolute font-md">check_circle</ui-icon>
        <img :src="image.path" :alt="image.id" class="w-100p">
        <div class="position-absolute bg-white bg-white-80" style="bottom: 0; left:0; right:0;">
          <div class="p-2  d-flex " style="max-width: 100%">
            <ui-button v-if="!hideSelect" @click="$emit('selected',image)">select</ui-button>
            <ui-button @click="selectedImage=image;showMarkdown=true"><ui-icon>code</ui-icon></ui-button>
            <ui-button @click="deleteImage(image)" color="warning"><ui-icon>delete</ui-icon></ui-button>
          </div>
        </div>
      </div>
    </div>
    <form @submit.prevent="upload('upload')" v-if="selectedTab===1" class="grid m-t-3">

      <div class=" b-rounded b-primary-50 grid place-x-center">
        <label class="m-b-5 grid">
          <span class="b-1 b-transparent b-rounded cursor-pointer font-md p-x-2 p-y-0 text-white bg-primary hover:raise-1-white place-x-center">
            <ui-icon>perm_media</ui-icon>
            choose file
          </span>
          <input class="d-hidden" name="image" type="file" @change="prepare">
          <p>Tip: reduce image size and use a next-gen image format like "WebP" or "JPEG 2000" for optimal performance.</p>
        </label>
        <div class="position-relative" v-if="newImage">
          <h3>Preview</h3>
          <img :src="newImage"  alt="preview" class="w-100p">
          <ui-button :disabled="uploading" type="submit" color="primary-filled" class="position-absolute font-md" style="bottom: 15px; right: 5px">
            <ui-icon>upload</ui-icon>
            upload
          </ui-button>
        </div>
        <ui-progress v-if="uploading" :value="100" color="success-light-filled" animate></ui-progress>
        <p class="text-warning">{{uploadError}}</p>
      </div>


    </form>
    <form @submit.prevent="upload('external')" v-if="selectedTab===2">
      <div class="d-flex">
        <ui-input placeholder="https://my-resource/img/my.webp" class="f-1 m-r-5" v-model:value="externalUrl" type="url"/>
        <div class="place-y-center m-t-2">
          <ui-button @click="newImage=externalUrl">get</ui-button>
        </div>
      </div>


      <div class="position-relative" v-if="newImage">
        <h3>Preview</h3>
        <img :src="newImage"  alt="preview" class="w-100p">
        <ui-button :disabled="uploading" type="submit" color="primary-filled" class="position-absolute font-md" style="bottom: 15px; right: 5px">
          <ui-icon>upload</ui-icon>
          use
        </ui-button>
      </div>
    </form>
    <ui-modal :show="showMarkdown" title="copy markdown" @close="showMarkdown=false">
      <div class="m-3 p-b-3">
        <p>You can use the following code snippets in markdown elements as needed</p>
        <div class="d-flex">
          <ui-input class="f-1" ref="copyImg" title="Image only" :value="makeMarkdown(selectedImage?.path)"></ui-input>
          <div class="place-y-center">
            <ui-button @click="copyToClipboard('copyImg')"><ui-icon>content_copy</ui-icon></ui-button>
          </div>

        </div>
        <div class="d-flex">
          <ui-input class="f-1" ref="copyLink" title="Image link" :value="makeMarkdown(selectedImage?.path,'link')"></ui-input>
          <div class="place-y-center">
            <ui-button @click="copyToClipboard('copyLink')"><ui-icon>content_copy</ui-icon></ui-button>
          </div>

        </div>
        <input class="b-0 bg-transparent text-transparent" type="text" ref="copy">
      </div>
    </ui-modal>
  </div>
</template>

<script>
import uiTabs from '/vue/ui/lib/ui.tabs';
import uiButton from '/vue/ui/lib/ui.button';
import uiIcon from '/vue/ui/lib/ui.icon'
import uiProgress from '/vue/ui/lib/ui.progress'
import uiInput from '/vue/ui/lib/ui.input'
import uiModal from '/vue/ui/lib/ui.modal'
export default {
  props:{
    selectedId: String,
    hideSelect: {
      type: Boolean,
      default: false
    }
  },
  data:()=>({
    selectedTab:0,
    newImage:null,
    externalUrl:'',
    uploading: false,
    showMarkdown:false,
    selectedImage:null,
    showNotification:false,
    uploadError:''
  }),
  components:{
    uiTabs,
    uiButton,
    uiIcon,
    uiProgress,
    uiInput,
    uiModal,
  },
  watch:{
    images(newVal){
      if(newVal.length<1){
        this.selectedTab = 1
      }
    }
  },
  setup(){
    const images = Vue.ref([])
    const store = Vue.inject('neoanStore');
    store.subscribe('image', (storeImages)=>{
      storeImages = storeImages.sort((a,b)=> a.insert_date_st > b.insert_date_st ? -1 : 1)
      images.value = storeImages;
    })
    return {images,store}
  },

  methods:{
    copyToClipboard($ref){
      this.$refs.copy.value = this.$refs[$ref].value
      this.$refs.copy.select()
      document.execCommand('copy');
      this.$refs.copy.blur();
      this.$toast.info('Copied to clipboard')

    },
    deleteImage(image){
      const c = confirm('This image might be connected to articles you have written. Are you sure you want to proceed?')
      if(c){
        this.store.delete('image', image)
      }
    },
    makeMarkdown(path, mode){
      if(mode === 'link'){
        return '[![Alt text]('+path+')](https://your-link.com)'
      } else {
        return '![Alt text]('+path+' "a title")'
      }
    },
    prepare($ev){
      const reader = new FileReader();
      reader.readAsDataURL($ev.target.files[0])
      reader.onload = (data => {
        if($ev.target.files[0].size/1024>400){
          this.uploadError = 'Your file is too big! The optimal file size for a fast web-experience is less than 100kb. The maximum allowed file size is 400kb.'
        } else {
          this.newImage = data.target.result;
          this.uploadError ='';
        }
      })
    },
    async upload(mode){
      this.uploading = true;
      this.uploadError = '';
      try{
        await this.store.post('image',{image: this.newImage,mode:mode||'upload'}).then(res=>{
          this.newImage = null;
          this.selectedTab = 0;
          this.uploading = false;
        })
      } catch (e) {
        this.uploadError = 'Request failed. Does your file exceed 400kb or did your login expire?'
        this.newImage = null;
        this.uploading = false;

      }


    }

  },
  template: `{{template}}`,
}
</script>