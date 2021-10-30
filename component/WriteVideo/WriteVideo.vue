<template>
  <div >
    <ui-input v-model:value="content.title" type="select" :options="[{title:'rumble'},{title:'youtube'}]" option-value="title" placeholder="Select type"/>
    <ui-input title="Video" placeholder="YouTube: video-id | Rumble: IFRAME url" v-model:value="content.content" v-if="content.title"/>
    <iframe v-if="content.title==='rumble'" class="rumble w-100p"  height="360" :src="content.content"  allowfullscreen></iframe>
    <iframe v-if="content.title==='youtube'" class="w-100p" height="360" :src="'https://www.youtube.com/embed/'+content.content" title="YouTube video player" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
  </div>
</template>

<script>
import uiInput from '/vue/ui/lib/ui.input';
export default {
  props:{content:Object},
  components:{uiInput},
  template: `{{template}}`,
}
</script>