<template>
  <ui-tag color="accent-filled" @click="$router.push('/articles/'+keyword)" v-if="keywords" v-for="keyword in keywordArray">{{keyword}}</ui-tag>
</template>

<script>
import uiTag from "/vue/ui/lib/ui.tag"
export default {
  components:{uiTag},
  props:{keywords:String},
  template: `{{template}}`,
  computed:{
    keywordArray(){
      return this.keywords.split(',');
    }
  }
}
</script>