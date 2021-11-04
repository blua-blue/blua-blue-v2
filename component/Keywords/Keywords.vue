<template>
  <ui-tag class="m-t-1 md:m-t-0 m-b-1" color="accent-filled" @click="clicked(keyword)" v-if="keywords" v-for="keyword in keywordArray">{{keyword}}</ui-tag>
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
  },
  methods:{
    clicked(keyword){
      this.$emit('keyword-clicked',keyword)
      this.$router.push('/articles/'+keyword)
    }
  }
}
</script>