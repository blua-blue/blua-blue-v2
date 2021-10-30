<template>
  <div class="b-rounded b-1 w-100p b-gray"  style="display: flex">
    <div :class="classes" :style="styling"></div>
  </div>
</template>

<script>
const style = (percent) => {
  return {
    height: '.6rem',
    'flex-basis': percent + '%'
  }
}

export default {

  props: {
    value: Number,
    color: {
      type: String,
      default: 'primary-filled'
    },
    disabled: {
      type: Boolean,
      default: false
    },
    animate: {
      type:Boolean,
      default: false
    }
  },
  inject: ['theming'],
  computed: {
    classes(){
      return this.theming(this.color, this.disabled, this.animate)
    },
    styling() {
      return style(this.value)
    }
  },
  template: `{{template}}`
}
</script>
