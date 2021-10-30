<template>
  <span class="material-icons" :style="style">
    <slot></slot>
  </span>
</template>

<script>

export default {
  computed: {
    style() {

      let s = {
        verticalAlign: 'middle',
      }
      if (!this.size) {
        s.fontSize = 'inherit'
      }
      return s;
    }
  },
  mounted() {
    if (this.$el.className.includes('font-')) {
      this.size = true;
    }
  },
  data: () => ({
    size: false
  }),
  template: `{{template}}`
}
</script>

<style scoped>

</style>