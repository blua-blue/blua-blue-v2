<template>
  <section class="grid b-b-1 b-gray" :class="classRule()">
    <div :style="styles.uiTab" class="ui-tab b-b-2 hover:b-accent place-x-center font-strong"
         :class="{'b-primary':i===selected, 'b-transparent': i !== selected}"
         v-for="(tab,i) in tabs"
         @click="$emit('update:selected', i)"
         :key="i">
      <span :class="{'font-strong': i === selected, 'font-light': i !== selected}">{{tab}}</span>
    </div>
  </section>
</template>

<script>

const styles = {
  uiTab: {
    cursor: 'pointer'
  }
};


export default {
  data(){
    return {
      styles
    }
  },
  props: {
    selected: {
      type: Number,
      default: 0
    },
    tabs: {
      type: Array,
      default: ['Tab']
    }
  },
  methods:{
    classRule(){
      const granular = Math.ceil(12 / this.tabs.length) ;
      let returnObj = {};
      let str = 'grid';
      for(let i =0; i< this.tabs.length;i++){
        str += `-${granular}`
      }
      returnObj[str] = true;
      return returnObj;
    }
  },
  template: `{{template}}`
}
</script>
