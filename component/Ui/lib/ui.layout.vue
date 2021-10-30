<template>
  <div v-if="design === 'left'" class="h-100p d-flex f-column">
    <section class="grid-12  md:grid-3-9 lg:grid-2-10 f-1">
      <div class="bg-gray-lighter p-3">
        <span class="cursor-pointer material-icons" @click="$emit('update:menu-expanded', !menuExpanded)" v-if="client.width<769">menu</span>
        <div class="b-r-1 b-gray-light p-2" v-if="client.width>768||menuExpanded">
          <slot name="menu">
            <p>Side menu slot #menu</p>
          </slot>
        </div>
      </div>
      <div class="bg-gray-lighter">
        <slot></slot>
      </div>
    </section>
  </div>
</template>

<script>

const client = Vue.reactive({
  width: window.innerWidth
});

window.addEventListener('resize',()=>{
  client.width = window.innerWidth;
})
export default {
  data:()=>({
    client,
    expandedMenu: false
  }),
  props: {
    design: {
      type: String,
      default: 'left'
    },
    menuExpanded: {
      type: Boolean,
      default: false
    }
  },


  template: `{{template}}`
}
</script>
