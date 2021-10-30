<!--
uiModal

simple slotted modal

@close - close event

:title - string - defaults to "info"

:show - boolean - defaults to false; controls whether modal is displayed
-->
<template>
  <div :style="style.backdrop" v-if="show" class="modal-backdrop">
    <section :style="style.content" class="grid m-t-5">
      <div class="w-85p md:w-50p place-x-center bg-white b-rounded raise-1-gray">
        <div class="grid-8-4 b-b-1 b-gray">
          <div class="p-t-3 p-b-2 p-l-4 font-strong">
            <slot name="title">{{title}}</slot>
          </div>
          <div class="place-x-end m-2">
            <ui-button @click="$emit('close')" color="warning">
              <ui-icon class="font-sm ">close</ui-icon>
            </ui-button>
          </div>
        </div>
        <div :style="style.slot">
          <slot></slot>
        </div>
      </div>
    </section>

  </div>
</template>


<script>
import uiButton from '/vue/ui/lib/ui.button';
import uiIcon from '/vue/ui/lib/ui.icon';

const style = {
  backdrop:{
    position: 'absolute',
    left: 0,
    top:0,
    bottom:0,
    zIndex:2,
    width: '100%',
    minHeight: '100vh',
    background: 'rgba(0,0,0,.7)'
  },
  content: {
    position: 'relative',
    top: '15%',

  },
  slot: {
    overflowY: 'auto',
    maxHeight: '70vh',
  }

}
const body = document.getElementsByTagName('body')[0];
export default {
  name: 'uiModal',
  components:{
    uiButton,
    uiIcon
  },
  data(){
    return {
      style
    }
  },
  props: {
    show: {
      type: Boolean,
      default: false
    },
    close: {
      type: Function
    },
    title: {
      type: String,
      default: 'Info'
    }
  },
  watch:{
    show(newVal){
      style.backdrop.top = window.scrollY + 'px';
      body.style.overflow = newVal ? 'hidden' : 'inherit';

    }
  },
  mounted(){
  },
  template: `{{template}}`,

}
</script>

