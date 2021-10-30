<template>
<div :class="classes" class="b-rounded p-4">
  <slot></slot>
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