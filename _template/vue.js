<template>
  <div>{{name}}</div>
</template>

<script>
export default {
  mounted(){
    console.log('attached: {{name}}')
  },
  template: `{{template}}`,
}
</script>