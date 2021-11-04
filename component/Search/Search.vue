<template>
  <div>
    <div class="position-relative grid">
      <input class="b-rounded-3 b-1 p-2" placeholder="search articles" v-model="search"/>
      <button v-if="search.length>0" @click="search=''" class="position-absolute b-rounded-3 b-0 bg-transparent text-warning" title="clear" style="right: 10px; top:5px">x</button>
    </div>
    <div style="z-index:1" class="grid-4-4-4 position-absolute search-results bg-black-50 p-3 b-rounded" n-if="false" v-if="searchResults.length>0">
      <div class="bg-white-95 p-x-3 b-rounded m-x-1 m-b-2 raise-1-primary" v-for="result in searchResults">
        <h4 class="cursor-pointer b-b-1 b-transparent hover:b-black" @click="navigate('/article/'+result.slug)">{{result.name}}</h4>
        <keywords @keyword-clicked="search=''" :keywords="result.keywords"/>
        <p class="cursor-pointer" @click="navigate('/profile/'+result.author.user_name)">@{{result.author.user_name}}</p>
      </div>
    </div>
  </div>
</template>

<script>
import keywords from '/vue/keywords';
export default {
  components:{keywords},
  data:()=>({
    search:'',
    searchResults:[]
  }),
  inject:['neoanStore'],
  watch:{
    search(newV, oldV){
      this.searchResults = [];
      if(newV.length>2){
        this.neoanStore.getAll('article').then(allKnown => {
          this.searchResults = allKnown.filter(single => {
            return single.name.toLowerCase().includes(newV.toLowerCase()) || (single.keywords && single.keywords.includes(newV.toLowerCase())) || single.author.user_name.includes(newV)
          })
        })
      }
    }
  },
  methods:{
    navigate(to){
      this.$router.push(to);
      this.search = '';
    },
  },
  template: `{{template}}`,
}
</script>