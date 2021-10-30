<template>
  <section>
    <div class="grid-10-2 b-b-1 p-y-2 p-x-3 hover:bg-gray-light-50 test"
         :class="{'b-accent':open,'b-gray':!open}"
         style="cursor: pointer"
         @click="$emit('update:open',!open)">
      <div class="place-y-center"><slot name="title">{{title}}</slot></div>
      <div class="place-x-end" v-if="!open"><ui-icon>keyboard_arrow_down</ui-icon></div>
      <div class="place-x-end" v-else><ui-icon>keyboard_arrow_up</ui-icon></div>
    </div>
    <transition name="fade">
      <div class="p-2 bg-gray-lighter" v-if="open">
        <slot>
          <p>This is the main slot of the accordion.</p>
        </slot>
      </div>
    </transition>

  </section>

</template>

<script>
import uiIcon from '/vue/ui/lib/ui.icon';
export default {
  components:{
    uiIcon
  },
  props: {
    title: String,
    color: {

    },
    open: {
      type: Boolean,
      default: false
    }
  },
  template:`{{template}}`
}
</script>
