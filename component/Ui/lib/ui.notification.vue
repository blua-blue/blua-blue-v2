<!--
uiNotification

primitive notification

@close - close event

:title - string - defaults to "info"

:show - boolean - defaults to false; controls whether modal is displayed
-->
<template>

    <transition name="swipe">
      <div v-if="show">
      <teleport to="body">
        <div class="position-fixed p-3" style="top:0; right:0; z-index: 99">
          <ui-alert color="accent-filled">
            <div class="w-250px">
              <div class="grid-8-4 b-b-1 b-b-white">
                <div>{{ title }}</div>
                <ui-icon @click="$emit('close')" class="font-sm text-warning place-x-end">clear</ui-icon>
              </div>
              <div><slot></slot></div>
            </div>
          </ui-alert>
        </div>
      </teleport>
      </div>
    </transition>



</template>


<script>
import uiButton from '/vue/ui/lib/ui.button';
import uiIcon from '/vue/ui/lib/ui.icon';
import uiAlert from '/vue/ui/lib/ui.alert';

const style = {
  backdrop: {
    position: 'absolute',
    left: 0,
    top: 0,
    bottom: 0,
    width: '100%',
    minHeight: '100vh',
    background: 'rgba(0,0,0,.7)'
  },
  uiAlert:{
    zIndex: 99
  },
  content: {
    zIndex:99,
    position: 'relative',
    top: '15%',

  },
  slot: {
    overflowY: 'auto',
    maxHeight: '70vh',
  }

}
export default {
  components: {
    uiButton,
    uiIcon,
    uiAlert
  },
  data() {
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
    show(newV){
      console.log('notification')
      if(newV){
        setTimeout(()=>this.$emit('update:show', false),4000)
      }

    }
  },

  mounted() {
  },
  template: `{{template}}`,

}
</script>

