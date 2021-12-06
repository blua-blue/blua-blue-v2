<template>
  <ui-notification v-model:show="showNotification"></ui-notification>
</template>
<script>
import uiNotification from "/vue/ui/lib/ui.notification";
const notificationTemplate = `{{template}}`;
export default {
  components:{uiNotification},
  data:()=>({
    showNotification:false,
    title: 'something'
  }),
  methods:{
    notify(options){
      const n = document.createDocumentFragment();
      n.innerHtml = notificationTemplate;
      this.$el.append(n)
      console.log(n.innerHtml)
      this.showNotification = true;
      console.log(this.showNotification)
    }
  },
}
</script>
