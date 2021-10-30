<template>
  <div>
    <div class="grid">
      <div style="max-width: 80%">
        <img class="w-100p" :src="content.content" alt="selected image" v-if="content.content">
      </div>
      <ui-input label="Caption/Contribution" v-model:value="content.title"></ui-input>
    </div>
    <n-image class="f-1" @selected="applyImage"></n-image>
  </div>

</template>

<script>
import uiInput from '/vue/ui/lib/ui.input';
export default {
  props:{
    content:Object
  },
  components:{
    uiInput
  },
  methods: {
    applyImage(ev){
      this.content.content = ev.path;
    }
  },
  template: `{{template}}`,
}
</script>