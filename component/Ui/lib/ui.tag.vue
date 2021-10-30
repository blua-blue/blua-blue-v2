<template>
<div :class="classes" class="b-1 p-x-2 d-inline-block m-r-1" style="border-radius: 15px; ">
  <span @click="selected">
    <slot></slot>
  </span>
  <span @click.prevent="$emit('removed')" style="border-radius: 50%" v-if="removable" class="material-icons cursor-pointer font-sm m-t-1 m-l-1 hover:bg-warning hover:text-white">close</span>
</div>
</template>

<script>
import uiIcon from '/vue/ui/lib/ui.icon';
export default {
  props:{
    removable:{
      type: Boolean,
      default: false
    },
    disabled:{
      type: Boolean,
      default: false
    },
    color: {
      type: String,
      default: 'gray'
    }
  },
  computed: {
    classes(){
      return this.theming(this.color, this.disabled)
    }
  },
  inject: ['theming'],
  components: {
    uiIcon
  },
  methods:{
    selected(){
      if(!this.disabled){
        this.$emit('selected')
      }
    }
  },
  template: `{{template}}`
}
</script>

<style scoped>

</style>